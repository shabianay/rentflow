<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Unit;
use App\Services\NotificationService;
use App\Services\PricingService;
use App\Jobs\SendBookingConfirmationJob; // Tambahkan ini
use App\Jobs\SendCancelBookingJob; // Tambahkan ini
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $bookings = Booking::with(['user', 'unit', 'payment'])
            ->when($search, fn ($q) => $q->where('booking_number', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10);

        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $units = Unit::where('is_active', true)->where('status', 'ready')->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'search', 'customers', 'units'));
    }

    public function myBookings(Request $request)
    {
        $search = $request->get('search');
        $bookings = Booking::with(['unit', 'payment', 'invoice'])
            ->where('user_id', Auth::id())
            ->when($search, fn ($q) => $q->where('booking_number', 'like', "%{$search}%")
                ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings', 'search'));
    }

    public function create(Unit $unit)
    {
        $unit->load('category');

        return view('bookings.create', compact('unit'));
    }

    public function createMulti()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('catalog')->with('warning', 'Keranjang masih kosong. Pilih unit terlebih dahulu.');
        }

        $units = Unit::with('category')->whereIn('id', array_keys($cart))->get();

        return view('bookings.create-multi', compact('units', 'cart'));
    }

    public function store(Request $request, PricingService $pricing)
    {
        $data = $request->validate([
            'unit_id' => 'sometimes|required|exists:units,id',
            'unit_ids' => 'sometimes|required|array',
            'unit_ids.*' => 'exists:units,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
            'terms_accepted' => 'required|accepted',
        ]);

        $unitIds = $data['unit_ids'] ?? [$data['unit_id']];

        // Cek duplikasi sebelum transaksi agar bisa redirect ke payment existing
        $existingBooking = Booking::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($data) {
                $q->where('start_date', '<=', $data['end_date'])
                    ->where('end_date', '>=', $data['start_date']);
            })
            ->whereHas('items', fn ($q) => $q->whereIn('unit_id', $unitIds))
            ->first();

        if ($existingBooking) {
            if ($existingBooking->payment) {
                $request->session()->flash('info', 'Anda sudah memiliki booking pending. Silakan selesaikan pembayaran.');
                return redirect()->route('payments.show', $existingBooking);
            }
        }

        $booking = DB::transaction(function () use ($unitIds, $data, $pricing) {
            $units = Unit::whereIn('id', $unitIds)->lockForUpdate()->get();

            if ($units->count() !== count($unitIds)) {
                throw new \RuntimeException('Unit tidak ditemukan.');
            }

            // Cek ketersediaan semua unit
            foreach ($units as $unit) {
                if (! $unit->isAvailable($data['start_date'], $data['end_date'])) {
                    throw new \RuntimeException("Unit {$unit->name} tidak tersedia di tanggal tersebut.");
                }
            }

            // Hitung total harga semua unit
            $totalSubtotal = 0;
            $totalDeposit = 0;
            $items = [];
            $maxDays = 0;

            foreach ($units as $unit) {
                $calc = $pricing->calculateTotal($unit, $data['start_date'], $data['end_date']);
                $totalSubtotal += $calc['subtotal'];
                $totalDeposit += $calc['deposit'];
                $items[] = [
                    'unit' => $unit,
                    'calc' => $calc,
                ];
                if ($calc['days'] > $maxDays) $maxDays = $calc['days'];
            }

            $totalAmount = $totalSubtotal + $totalDeposit;

            $booking = Booking::create([
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'unit_id' => $units->first()->id, // backward compat
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_days' => $maxDays,
                'subtotal' => $totalSubtotal,
                'discount' => 0,
                'deposit_amount' => $totalDeposit,
                'total_amount' => $totalAmount,
                'notes' => $data['notes'] ?? null,
            ]);

            // Simpan detail setiap unit ke BookingItem
            foreach ($items as $item) {
                $booking->items()->create([
                    'unit_id' => $item['unit']->id,
                    'price_per_day' => $item['unit']->price_per_day,
                    'days' => $item['calc']['days'],
                    'subtotal' => $item['calc']['subtotal'],
                ]);
                $item['unit']->update(['status' => 'booked']);
            }

            Payment::create([
                'payment_number' => 'PAY-' . strtoupper(Str::random(8)),
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Kirim email konfirmasi booking
            SendBookingConfirmationJob::dispatch($booking);

            return $booking;
        });

        // Hapus session keranjang setelah booking berhasil
        session()->forget('cart');

        NotificationService::sendToAdmins(
            'booking',
            'Booking Baru',
            Auth::user()->name . " membuat booking baru #{$booking->booking_number}.",
            route('admin.bookings.show', $booking)
        );

        return redirect()->route('payments.show', $booking)->with('success', 'Booking berhasil, silakan lanjutkan pembayaran.');
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load(['unit', 'payment', 'invoice', 'items.unit']);

        $existingReviews = \App\Models\Review::where('booking_id', $booking->id)
            ->get()
            ->keyBy('unit_id');

        return view('bookings.show', compact('booking', 'existingReviews'));
    }

    public function adminShow(Booking $booking)
    {
        $booking->load(['unit', 'user', 'payment', 'invoice', 'items.unit']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,active,completed,cancelled']);
        $booking->update(['status' => $request->status]);

        if (in_array($request->status, ['cancelled', 'completed'], true)) {
            $booking->syncUnitStatus('ready');

            if ($request->status === 'cancelled') {
                SendCancelBookingJob::dispatch($booking);
            }
        }

        if ($request->status === 'active') {
            $booking->syncUnitStatus('on_rent');
        }

        if (in_array($request->status, ['cancelled', 'completed'], true)) {
            if ($booking->payment) {
                $booking->payment->update([
                    'status' => $request->status === 'cancelled' ? 'refunded' : 'paid',
                    'paid_at' => $request->status === 'completed' ? now() : $booking->payment->paid_at,
                ]);
            }
            if ($booking->invoice) {
                $booking->invoice->update([
                    'status' => $request->status === 'cancelled' ? 'cancelled' : 'paid',
                ]);
            }
        }

        return back()->with('success', 'Status booking diperbarui.');
    }

    public function getBookedDates(Unit $unit)
    {
        $bookings = $unit->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->get(['start_date', 'end_date']);

        $dates = [];
        foreach ($bookings as $booking) {
            $start = $booking->start_date;
            $end = $booking->end_date;
            while ($start <= $end) {
                $dates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }

        return response()->json(array_unique($dates));
    }

    public function calculatePrice(Request $request, PricingService $pricing)
    {
        $data = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $unit = Unit::findOrFail($data['unit_id']);
        $calc = $pricing->calculateTotal($unit, $data['start_date'], $data['end_date']);

        return response()->json($calc);
    }

    public function customerCancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status Pending yang dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->syncUnitStatus('ready');

        if ($booking->payment) {
            $booking->payment->update(['status' => 'refunded']);
        }

        SendCancelBookingJob::dispatch($booking);

        return redirect()->route('bookings.show', $booking)->with('success', 'Booking berhasil dibatalkan.');
    }

    public function calendar(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::with(['unit', 'user', 'items.unit'])
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where('start_date', '<=', $endOfMonth)
            ->where('end_date', '>=', $startOfMonth)
            ->get();

        // Build date→bookings lookup
        $dateBookings = [];
        foreach ($bookings as $b) {
            $start = $b->start_date->copy();
            $end = $b->end_date->copy();
            while ($start <= $end) {
                $dateStr = $start->format('Y-m-d');
                $dateBookings[$dateStr][] = $b;
                $start->addDay();
            }
        }

        // Build calendar grid
        $weeks = [];
        $current = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endGrid = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        while ($current <= $endGrid) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $current->format('Y-m-d');
                $week[] = [
                    'date' => $current->copy(),
                    'isCurrentMonth' => $current->month === (int)$month,
                    'isToday' => $current->isToday(),
                    'bookings' => $dateBookings[$dateStr] ?? [],
                ];
                $current->addDay();
            }
            $weeks[] = $week;
        }

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->locale('id')->isoFormat('MMMM');
        }

        $prevMonth = $startOfMonth->copy()->subMonth()->month;
        $prevYear = $startOfMonth->copy()->subMonth()->year;
        $nextMonth = $startOfMonth->copy()->addMonth()->month;
        $nextYear = $startOfMonth->copy()->addMonth()->year;

        return view('admin.bookings.calendar', compact('weeks', 'month', 'year', 'months', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear', 'bookings'));
    }

    public function calendarData(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $bookings = Booking::with(['unit', 'user', 'items.unit'])
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where('start_date', '<=', $endOfMonth)
            ->where('end_date', '>=', $startOfMonth)
            ->get();

        $dateBookings = [];
        foreach ($bookings as $b) {
            $start = $b->start_date->copy();
            $end = $b->end_date->copy();
            while ($start <= $end) {
                $dateStr = $start->format('Y-m-d');
                $dateBookings[$dateStr][] = $b;
                $start->addDay();
            }
        }

        $weeks = [];
        $current = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endGrid = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        while ($current <= $endGrid) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $current->format('Y-m-d');
                $week[] = [
                    'date' => $current->copy(),
                    'isCurrentMonth' => $current->month === (int)$month,
                    'isToday' => $current->isToday(),
                    'bookings' => $dateBookings[$dateStr] ?? [],
                ];
                $current->addDay();
            }
            $weeks[] = $week;
        }

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[$m] = Carbon::create()->month($m)->locale('id')->isoFormat('MMMM');
        }

        $prevMonth = $startOfMonth->copy()->subMonth()->month;
        $prevYear = $startOfMonth->copy()->subMonth()->year;
        $nextMonth = $startOfMonth->copy()->addMonth()->month;
        $nextYear = $startOfMonth->copy()->addMonth()->year;

        $html = view('admin.bookings._calendar', compact('weeks', 'month', 'year', 'months', 'prevMonth', 'prevYear', 'nextMonth', 'nextYear'))->render();

        return response()->json(['html' => $html, 'title' => $months[$month] . ' ' . $year]);
    }

    public function adminCreate()
    {
        $units = Unit::with('category')->where('is_active', true)->where('status', 'ready')->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.bookings.create', compact('units', 'customers'));
    }

    public function adminStore(Request $request, PricingService $pricing)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'new_customer_name' => 'nullable|string|max:255',
            'new_customer_email' => 'nullable|email|max:255|unique:users,email',
            'new_customer_phone' => 'nullable|string|max:20',
            'new_customer_password' => 'nullable|string|min:6',
            'unit_ids' => 'required|array|min:1',
            'unit_ids.*' => 'exists:units,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
            'mark_paid' => 'boolean',
            'terms_accepted' => 'required|accepted',
        ]);

        if (!$request->input('user_id') && !$request->input('new_customer_name')) {
            return back()->withErrors(['user_id' => 'Pilih customer atau isi data customer baru.'])->withInput();
        }

        if ($name = $request->input('new_customer_name')) {
            $data['user_id'] = User::create([
                'name' => $name,
                'email' => $request->input('new_customer_email', 'offline-' . Str::random(6) . '@rentflow.app'),
                'phone' => $request->input('new_customer_phone'),
                'password' => Hash::make($request->input('new_customer_password', 'password')),
                'role' => 'customer',
            ])->id;
        }

        $booking = DB::transaction(function () use ($data, $pricing) {
            $units = Unit::whereIn('id', $data['unit_ids'])->lockForUpdate()->get();

            foreach ($units as $unit) {
                if (!$unit->isAvailable($data['start_date'], $data['end_date'])) {
                    throw new \RuntimeException("Unit {$unit->name} tidak tersedia.");
                }
            }

            $totalSubtotal = 0;
            $totalDeposit = 0;
            $items = [];
            $maxDays = 0;

            foreach ($units as $unit) {
                $calc = $pricing->calculateTotal($unit, $data['start_date'], $data['end_date']);
                $totalSubtotal += $calc['subtotal'];
                $totalDeposit += $calc['deposit'];
                $items[] = ['unit' => $unit, 'calc' => $calc];
                if ($calc['days'] > $maxDays) $maxDays = $calc['days'];
            }

            $totalAmount = $totalSubtotal + $totalDeposit;
            $status = $data['mark_paid'] ?? false ? 'active' : 'pending';

            $booking = Booking::create([
                'booking_number' => 'BK-' . strtoupper(Str::random(8)),
                'user_id' => $data['user_id'],
                'unit_id' => $units->first()->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'total_days' => $maxDays,
                'subtotal' => $totalSubtotal,
                'discount' => 0,
                'deposit_amount' => $totalDeposit,
                'total_amount' => $totalAmount,
                'status' => $status,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $booking->items()->create([
                    'unit_id' => $item['unit']->id,
                    'price_per_day' => $item['unit']->price_per_day,
                    'days' => $item['calc']['days'],
                    'subtotal' => $item['calc']['subtotal'],
                ]);
                $item['unit']->update(['status' => $status === 'active' ? 'on_rent' : 'booked']);
            }

            $paymentStatus = $data['mark_paid'] ?? false ? 'paid' : 'pending';
            $payment = Payment::create([
                'payment_number' => 'PAY-' . strtoupper(Str::random(8)),
                'booking_id' => $booking->id,
                'amount' => $totalAmount,
                'status' => $paymentStatus,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
            ]);

            if ($paymentStatus === 'paid') {
                Invoice::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                        'user_id' => $data['user_id'],
                        'amount' => $totalAmount,
                        'status' => 'paid',
                    ]
                );
            }

            return $booking;
        });

        NotificationService::sendToAdmins(
            'booking',
            'Booking Baru (oleh Admin)',
            "Admin membuat booking #{$booking->booking_number} untuk {$booking->user->name}.",
            route('admin.bookings.show', $booking)
        );

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dibuat.');
    }
}
