<?php

namespace App\Jobs;

use App\Mail\PaymentReceipt;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPaymentReceiptJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function handle(): void
    {
        Mail::to($this->payment->booking->user->email)->send(new PaymentReceipt($this->payment));
    }
}
