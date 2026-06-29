<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyRevenue = Booking::whereIn('status', ['confirmed', 'active', 'completed'])
            ->whereYear('created_at', now()->year)
            ->selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $data = [
            'total_units' => Unit::count(),
            'units_ready' => Unit::where('status', 'ready')->where('is_active', true)->count(),
            'units_on_rent' => Unit::where('status', 'on_rent')->count(),
            'bookings_today' => Booking::whereDate('start_date', today())->count(),
            'revenue' => Booking::whereIn('status', ['confirmed', 'active', 'completed'])->sum('total_amount'),
            'bookings' => Booking::with(['user', 'unit'])->latest()->take(3)->get(),
            'monthlyRevenue' => $monthlyRevenue,
        ];

        return view('dashboard.index', $data);
    }

    public function customers(Request $request)
    {
        $search = $request->get('search');

        $customers = User::where('role', 'customer')
            ->withCount(['bookings as total_booking'])
            ->withSum(['bookings as total_spent' => fn ($q) => $q->whereIn('status', ['confirmed', 'active', 'completed'])], 'total_amount')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_card_number', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(10);

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function customerShow(User $user)
    {
        $bookings = Booking::with('unit')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $totalSpent = $bookings->filter(fn ($b) => in_array($b->status, ['confirmed', 'active', 'completed']))->sum('total_amount');

        return view('admin.customers.show', compact('user', 'bookings', 'totalSpent'));
    }

    public function customerUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'id_card_number' => 'nullable|string|max:30|unique:users,id_card_number,' . $user->id,
        ]);

        $user->update($data);

        return redirect()->route('admin.customers.show', $user)->with('success', 'Data customer berhasil diperbarui.');
    }

    public function customerToggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun customer berhasil {$status}.");
    }

    public function customer()
    {
        $user = Auth::user();
        $bookings = Booking::with('unit')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $activeBooking = $bookings->first(fn($b) => in_array($b->status, ['confirmed', 'active'], true));
        $totalSpent = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'active', 'completed'])
            ->sum('total_amount');
        $bookingCount = Booking::where('user_id', $user->id)->count();
        $completedCount = Booking::where('user_id', $user->id)->where('status', 'completed')->count();

        return view('dashboard.customer', compact(
            'user',
            'bookings',
            'activeBooking',
            'totalSpent',
            'bookingCount',
            'completedCount'
        ));
    }
}
