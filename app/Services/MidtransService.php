<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key');
        $this->clientKey = config('midtrans.client_key');
        $this->isProduction = config('midtrans.is_production');

        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$clientKey = $this->clientKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function createSnapTransaction(Booking $booking): array
    {
        $user = $booking->user;

        // Gunakan timestamp agar order_id unik jika user mencoba bayar ulang (retry)
        // Format: {booking_number}-{timestamp}
        $orderId = $booking->booking_number . '-' . time();

        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $booking->total_amount,
        ];

        $itemDetails = [];

        foreach ($booking->items as $item) {
            $itemDetails[] = [
                'id' => $item->unit->asset_number ?? ('unit-' . $item->unit_id),
                'price' => (int) $item->subtotal,
                'quantity' => 1,
                'name' => 'Sewa ' . $item->unit->name . ' (' . $item->days . ' hari)',
            ];
        }

        if (empty($itemDetails)) {
            $itemDetails[] = [
                'id' => $booking->unit->asset_number,
                'price' => (int) $booking->subtotal,
                'quantity' => 1,
                'name' => 'Sewa ' . $booking->unit->name . ' (' . $booking->total_days . ' hari)',
            ];
        }

        if ($booking->deposit_amount > 0) {
            $itemDetails[] = [
                'id' => 'deposit',
                'price' => (int) $booking->deposit_amount,
                'quantity' => 1,
                'name' => 'Deposit',
            ];
        }

        $customerDetails = [
            'first_name' => $user->name,
            'email' => $user->email,
        ];

        $callbacks = [
            'finish' => route('payments.result', ['status' => 'success', 'booking' => $booking]),
            'unfinish' => route('payments.result', ['status' => 'pending', 'booking' => $booking]),
            'error' => route('payments.result', ['status' => 'failed', 'booking' => $booking]),
        ];

        $payload = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => $callbacks,
        ];

        $snapResponse = \Midtrans\Snap::createTransaction($payload);

        return [
            'token' => $snapResponse->token,
            'redirect_url' => $snapResponse->redirect_url,
            'order_id' => $orderId,
        ];
    }

    public function getStatus(string $orderId): array
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);
            return [
                'success' => true,
                'transaction_id' => $status->transaction_id ?? null,
                'transaction_status' => $status->transaction_status ?? null,
                'status' => match ($status->transaction_status ?? '') {
                    'capture', 'settlement' => 'paid',
                    'pending', 'authorize' => 'pending',
                    'deny', 'cancel', 'expire' => 'failed',
                    'refund', 'partial_refund' => 'refunded',
                    default => 'pending',
                },
                'payment_type' => $status->payment_type ?? null,
                'order_id' => $status->order_id ?? $orderId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
