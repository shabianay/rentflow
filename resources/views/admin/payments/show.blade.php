@extends('layouts.admin')
@section('title', 'Detail Pembayaran')
@section('page_title', 'Payment #' . $payment->payment_number)
@section('page_subtitle', $payment->booking?->user?->name ?? '-')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.payments.index') }}"
            class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="card lg:col-span-2 space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $payment->payment_number }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Dibuat {{ $payment->created_at->diffForHumans() }}</p>
                </div>
                <span
                    class="badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : ($payment->status === 'failed' || $payment->status === 'refunded' ? 'danger' : 'info')) }} text-sm px-3 py-1">
                    {{ $payment->status === 'refunded' ? 'Cancel' : ucfirst($payment->status) }}
                </span>
            </div>

            <div class="grid gap-4 text-sm sm:grid-cols-2">
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">No. Booking</span>
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="mt-1 block font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">
                        {{ $payment->booking?->booking_number ?? '-' }}
                    </a>
                </div>
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Customer</span>
                    <span class="mt-1 block font-medium text-slate-800 dark:text-slate-100">{{ $payment->booking?->user?->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Unit</span>
                    <span class="mt-1 block font-medium text-slate-800 dark:text-slate-100">{{ $payment->booking?->units_list ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Tanggal</span>
                    <span class="mt-1 block font-medium text-slate-800 dark:text-slate-100">
                        {{ $payment->booking?->start_date ? $payment->booking->start_date->format('d M Y') : '-' }}
                        —
                        {{ $payment->booking?->end_date ? $payment->booking->end_date->format('d M Y') : '-' }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Metode</span>
                    <span class="mt-1 block font-medium text-slate-800 dark:text-slate-100 capitalize">
                        {{ $payment->method ? str_replace('_', ' ', $payment->method) : '-' }}
                    </span>
                </div>
                <div>
                    <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Dibayar Pada</span>
                    <span class="mt-1 block font-medium text-slate-800 dark:text-slate-100">
                        {{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}
                    </span>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-slate-700">

            <div>
                <span class="block text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500">Midtrans Info</span>
                <div class="mt-2 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Order ID</span>
                        <span class="font-mono text-xs text-slate-800 dark:text-slate-100">{{ $payment->snap_order_id ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Transaction ID</span>
                        <span class="font-mono text-xs text-slate-800 dark:text-slate-100">{{ $payment->midtrans_transaction_id ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-gradient-to-br from-indigo-600 to-purple-700 !border-0 text-white h-fit">
            <p class="text-sm text-indigo-100">Jumlah Pembayaran</p>
            <p class="mt-1 text-3xl font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <div class="mt-4 space-y-2 text-sm text-indigo-100">
                <div class="flex justify-between">
                    <span>Booking Total</span>
                    <span class="text-white">Rp {{ number_format($payment->booking?->total_amount ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
