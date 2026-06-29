<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\NotificationService;
use App\Jobs\SendPaymentReceiptJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans,
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $payments = Payment::with('booking.unit', 'booking.user')
            ->when($search, fn ($q) => $q->where('payment_number', 'like', "%{$search}%")
                ->orWhereHas('booking', fn ($b) => $b->where('booking_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))))
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments', 'search'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $booking->load(['unit', 'payment']);

        return view('payments.show', compact('booking'));
    }

    public function adminShow(Payment $payment)
    {
        $payment->load(['booking.unit', 'booking.user', 'booking.items.unit']);

        return view('admin.payments.show', compact('payment'));
    }

    public function process(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        try {
            $request->validate([
                'method' => 'nullable|in:qris,virtual_account,transfer,credit_card,ewallet',
            ]);

            $payment = $booking->payment;

            if (! $payment) {
                return response()->json(['error' => 'Pembayaran tidak ditemukan.'], 404);
            }

            if ($payment->snap_token && $payment->status !== 'paid') {
                return response()->json([
                    'snap_token' => $payment->snap_token,
                    'snap_redirect_url' => $payment->snap_redirect_url,
                ]);
            }

            if ($payment->status === 'paid') {
                return response()->json(['error' => 'Pembayaran sudah lunas.'], 400);
            }

            $data = [];
            if ($request->filled('method')) {
                $data['method'] = $request->method;
            }
            $payment->update($data);

            $snap = $this->midtrans->createSnapTransaction($booking);

            $payment->update([
                'snap_token' => $snap['token'],
                'snap_redirect_url' => $snap['redirect_url'],
                'snap_order_id' => $snap['order_id'],
            ]);

            return response()->json([
                'snap_token' => $snap['token'],
                'snap_redirect_url' => $snap['redirect_url'],
            ]);
        } catch (\Exception $e) {
            Log::error('Process payment error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                return response()->json([
                    'message' => 'Notification endpoint aktif. Gunakan POST untuk menerima notifikasi Midtrans.',
                    'status' => 'ok',
                ]);
            }

            // Validasi signature
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::warning('Midtrans notification: Invalid signature', ['order_id' => $request->order_id]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Cari payment berdasarkan order_id
            $payment = Payment::where('snap_order_id', $request->order_id)->first();

            if (!$payment) {
                Log::warning('Payment not found for order_id: ' . $request->order_id);
                // Fallback: parse booking_number dari order_id
                $parts = explode('-', $request->order_id);
                array_pop($parts); // hapus timestamp
                $bookingNumber = implode('-', $parts);
                $booking = Booking::where('booking_number', $bookingNumber)->first();
                if (!$booking) {
                    return response()->json(['message' => 'Booking not found'], 404);
                }
                $payment = $booking->payment;
                if (!$payment) {
                    return response()->json(['message' => 'Payment not found for booking'], 404);
                }
            }

            $status = match ($request->transaction_status) {
                'capture', 'settlement' => 'paid',
                'deny', 'cancel', 'expire' => 'failed',
                'refund', 'partial_refund' => 'refunded',
                default => $payment->status,
            };

            $payment->update([
                'midtrans_transaction_id' => $request->transaction_id ?? $request->transaction_id,
                'status' => $status,
                'paid_at' => $status === 'paid' ? now() : $payment->paid_at,
            ]);

            if ($status === 'paid') {
                $payment->booking->update(['status' => 'active']);
                $payment->booking->syncUnitStatus('on_rent');

                Invoice::firstOrCreate(
                    ['booking_id' => $payment->booking_id],
                    [
                        'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                        'user_id' => $payment->booking->user_id,
                        'amount' => $payment->booking->total_amount,
                        'status' => 'paid',
                    ]
                );

                // Kirim email receipt pembayaran
                SendPaymentReceiptJob::dispatch($payment);
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    public function result(Request $request, string $status, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $status = in_array($status, ['success', 'pending', 'failed']) ? $status : 'failed';

        $booking->load(['unit', 'payment', 'invoice']);

        $payment = $booking->payment;

        if ($payment && $payment->status === 'pending' && $payment->snap_order_id) {
            $midtransStatus = $this->midtrans->getStatus($payment->snap_order_id);

            if ($midtransStatus['success'] && $midtransStatus['status'] === 'paid') {
                $payment->update([
                    'midtrans_transaction_id' => $midtransStatus['transaction_id'],
                    'method' => $midtransStatus['payment_type'],
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $payment->booking->update(['status' => 'active']);
                $payment->booking->syncUnitStatus('on_rent');

                Invoice::firstOrCreate(
                    ['booking_id' => $payment->booking_id],
                    [
                        'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                        'user_id' => $payment->booking->user_id,
                        'amount' => $payment->booking->total_amount,
                        'status' => 'paid',
                    ]
                );

                SendPaymentReceiptJob::dispatch($payment);

                $booking->load('invoice');
                $status = 'success';
            } elseif ($midtransStatus['success']) {
                $payment->update([
                    'status' => $midtransStatus['status'],
                    'midtrans_transaction_id' => $midtransStatus['transaction_id'],
                    'paid_at' => $midtransStatus['status'] === 'paid' ? now() : null,
                ]);
            }
        }

        $booking->load(['unit', 'payment', 'invoice']);

        return view('payments.result', compact('booking', 'status'));
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403);

        try {
            $payment = $booking->payment;

            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan.'], 404);
            }

            $payload = $request->all();
            $orderId = $payload['order_id'] ?? $payment->snap_order_id;

            if (!$orderId) {
                $midtransStatus = $this->midtrans->getStatus($payment->snap_order_id);
                if ($midtransStatus['success']) {
                    $status = $midtransStatus['status'];
                    $transactionId = $midtransStatus['transaction_id'];
                    $paymentType = $midtransStatus['payment_type'];
                } else {
                    return response()->json(['success' => false, 'message' => 'Order ID tidak ditemukan.'], 400);
                }
            } else {
                $status = match ($payload['transaction_status'] ?? '') {
                    'capture', 'settlement' => 'paid',
                    'pending', 'authorize' => 'pending',
                    'deny', 'cancel', 'failure', 'expire' => 'failed',
                    'refund', 'partial_refund' => 'refunded',
                    default => $payment->status ?? 'pending',
                };
                $transactionId = $payload['transaction_id'] ?? null;
                $paymentType = $payload['payment_type'] ?? null;
            }

            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'method' => $paymentType ?? $payment->method,
                'status' => $status,
                'paid_at' => $status === 'paid' ? now() : null,
            ]);

            if ($status === 'paid') {
                $payment->booking->update(['status' => 'active']);
                $payment->booking->syncUnitStatus('on_rent');

                Invoice::firstOrCreate(
                    ['booking_id' => $payment->booking_id],
                    [
                        'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                        'user_id' => $payment->booking->user_id,
                        'amount' => $payment->booking->total_amount,
                        'status' => 'paid',
                    ]
                );

                SendPaymentReceiptJob::dispatch($payment);
            }

            return response()->json([
                'success' => true,
                'status' => $status,
                'booking_status' => $payment->fresh()->booking->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Confirm payment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checkStatus(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403);

        try {
            $payment = $booking->payment;

            if (!$payment || !$payment->snap_order_id) {
                return response()->json([
                    'success' => true,
                    'status' => $payment->status ?? 'pending',
                    'booking_status' => $booking->status,
                ]);
            }

            $midtransStatus = $this->midtrans->getStatus($payment->snap_order_id);

            if ($midtransStatus['success']) {
                $status = $midtransStatus['status'];

                $payment->update([
                    'midtrans_transaction_id' => $midtransStatus['transaction_id'],
                    'method' => $midtransStatus['payment_type'],
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? now() : null,
                ]);

                if ($status === 'paid') {
                    $payment->booking->update(['status' => 'active']);
                    $payment->booking->syncUnitStatus('on_rent');

                    Invoice::firstOrCreate(
                        ['booking_id' => $payment->booking_id],
                        [
                            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                            'user_id' => $payment->booking->user_id,
                            'amount' => $payment->booking->total_amount,
                            'status' => 'paid',
                        ]
                    );

                SendPaymentReceiptJob::dispatch($payment);

                NotificationService::sendToAdmins(
                    'payment',
                    'Pembayaran Diterima',
                    "Pembayaran booking #{$booking->booking_number} sebesar Rp " . number_format($payment->amount, 0, ',', '.') . " berhasil.",
                    route('admin.bookings.show', $booking)
                );
            }
            }

            return response()->json([
                'success' => true,
                'status' => $payment->fresh()->status,
                'booking_status' => $booking->fresh()->status,
            ]);
        } catch (\Exception $e) {
            Log::error('Check status error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
