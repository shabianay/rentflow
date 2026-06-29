@extends('layouts.app')

@section('title', match ($status) {
    'success' => 'Pembayaran Berhasil',
    'pending' => 'Pembayaran Tertunda',
    'failed' => 'Pembayaran Gagal',
    default => 'Status Pembayaran',
})

@section('content')
<div>

    @php
        $config = match ($status) {
            'success' => [
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg' => 'bg-emerald-50 dark:bg-emerald-900/30',
                'border' => 'border-emerald-200 dark:border-emerald-800',
                'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/40',
                'iconText' => 'text-emerald-600 dark:text-emerald-400',
                'title' => 'Pembayaran Berhasil',
                'message' => 'Terima kasih! Pembayaran Anda telah berhasil dikonfirmasi.',
            ],
            'pending' => [
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg' => 'bg-amber-50 dark:bg-amber-900/30',
                'border' => 'border-amber-200 dark:border-amber-800',
                'iconBg' => 'bg-amber-100 dark:bg-amber-900/40',
                'iconText' => 'text-amber-600 dark:text-amber-400',
                'title' => 'Pembayaran Tertunda',
                'message' => 'Pembayaran Anda sedang diproses. Kami akan mengirim notifikasi setelah pembayaran dikonfirmasi.',
            ],
            'failed' => [
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg' => 'bg-red-50 dark:bg-red-900/30',
                'border' => 'border-red-200 dark:border-red-800',
                'iconBg' => 'bg-red-100 dark:bg-red-900/40',
                'iconText' => 'text-red-600 dark:text-red-400',
                'title' => 'Pembayaran Gagal',
                'message' => 'Maaf, pembayaran Anda gagal diproses. Silakan coba kembali.',
            ],
        };
    @endphp

    <div class="card border-2 {{ $config['border'] }} {{ $config['bg'] }} py-10 text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full {{ $config['iconBg'] }} {{ $config['iconText'] }}">
            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
            </svg>
        </div>

        <h1 class="mt-6 text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $config['title'] }}</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ $config['message'] }}</p>

        {{-- Booking details --}}
        <div class="mx-auto mt-8 max-w-sm space-y-3 rounded-lg border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 text-left text-sm">
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-400">No. Booking</span>
                <span class="font-medium text-slate-800 dark:text-slate-100">{{ $booking->booking_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500 dark:text-slate-400">Unit</span>
                <span class="font-medium text-slate-800 dark:text-slate-100">{{ $booking->units_list }}</span>
            </div>
            <hr class="border-slate-100 dark:border-slate-700">
            <div class="flex justify-between text-base">
                <span class="text-slate-500 dark:text-slate-400">Total</span>
                <span class="font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
            </div>
            @if ($booking->payment)
                <div class="flex justify-between">
                    <span class="text-slate-500 dark:text-slate-400">Status</span>
                    <span class="badge-{{ $booking->payment->status === 'paid' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($booking->payment->status) }}
                    </span>
                </div>
            @endif
        </div>

        <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            @if ($status === 'success' && $booking->invoice)
                <a href="{{ route('invoices.show', $booking->invoice) }}" class="btn-primary">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Lihat Invoice
                </a>
            @elseif ($status === 'failed')
                <a href="{{ route('payments.show', $booking) }}" class="btn-primary">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Lagi
                </a>
            @endif
            <a href="{{ route('bookings.show', $booking) }}" class="btn-secondary">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Detail Booking
            </a>
        </div>
    </div>

    @if ($status === 'pending')
        <div class="mt-4 rounded-lg border border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 text-sm text-slate-500 dark:text-slate-400">
            Halaman ini akan otomatis memperbarui status dalam beberapa saat.
            <a href="{{ request()->url() }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Refresh halaman</a> untuk mengecek status terbaru.
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if ($booking->payment && $booking->payment->status === 'pending' && $booking->payment->snap_token)
<script>
    // Cek status langsung ke Midtrans API via server
    fetch('{{ route('payments.status', $booking) }}')
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.status === 'paid') {
                window.location.reload();
            } else if (d.status === 'pending') {
                // Poll every 5 seconds
                setTimeout(function poll() {
                    fetch('{{ route('payments.status', $booking) }}')
                        .then(function (r) { return r.json(); })
                        .then(function (d2) {
                            if (d2.status === 'paid') {
                                window.location.reload();
                            } else if (d2.status === 'pending') {
                                setTimeout(poll, 5000);
                            }
                        });
                }, 5000);
            }
        });
</script>
@endif
@endpush
