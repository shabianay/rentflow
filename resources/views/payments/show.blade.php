@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="mb-6">
    <a href="{{ route('bookings.show', $booking) }}"
        class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Booking
    </a>
</div>

<div class="grid gap-6 lg:grid-cols-5">

        {{-- Payment Method & Snap --}}
        <div class="lg:col-span-3">
            <div class="card">
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Selesaikan Pembayaran</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih metode pembayaran, lalu klik bayar untuk melanjutkan ke halaman Midtrans.</p>

                @if ($booking->payment->status === 'paid')
                    <div class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 p-6 text-center dark:border-emerald-800 dark:bg-emerald-900/30">
                        <svg class="mx-auto h-12 w-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-3 text-lg font-semibold text-emerald-700 dark:text-emerald-400">Pembayaran Lunas</p>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400">Terima kasih, pembayaran Anda telah diterima.</p>
                        <a href="{{ route('bookings.show', $booking) }}" class="mt-4 btn-primary inline-flex">Lihat Booking</a>
                    </div>
                @else
                    <div class="mt-6 space-y-4">
                        <div class="rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm text-indigo-700 dark:border-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                            Klik tombol di bawah untuk melanjutkan pembayaran melalui Midtrans.
                        </div>
                        <div id="payment-loading" class="hidden">
                            <div class="flex items-center justify-center py-4">
                                <div class="h-8 w-8 animate-spin rounded-full border-4 border-indigo-200 border-t-indigo-600 dark:border-indigo-800 dark:border-t-indigo-400"></div>
                            </div>
                        </div>
                        <button id="pay-button" class="btn-primary w-full">
                            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>
                @endif

                <div id="payment-error" class="mt-4 hidden rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400"></div>
            </div>
        </div>

        {{-- Booking Summary --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">No. Booking</p>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $booking->booking_number }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-title">Detail Pesanan</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Unit</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">{{ $booking->units_list }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Tanggal</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">
                            {{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 dark:text-slate-400">Durasi</span>
                        <span class="font-medium text-slate-800 dark:text-slate-100">{{ $booking->total_days }} hari</span>
                    </div>
                    <hr class="border-slate-100 dark:border-slate-700">

                    @if ($booking->payment)
                        <div class="flex justify-between">
                            <span class="text-slate-500 dark:text-slate-400">Status</span>
                            <span class="badge-{{ $booking->payment->status === 'paid' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : ($booking->payment->status === 'failed' ? 'danger' : 'info')) }}">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card bg-gradient-to-br from-indigo-600 to-purple-700 !border-0 text-white">
                <p class="text-sm text-indigo-100">Total Pembayaran</p>
                <p class="mt-1 text-3xl font-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                <div class="mt-4 space-y-2 text-sm text-indigo-100">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="text-white">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($booking->deposit_amount > 0)
                        <div class="flex justify-between">
                            <span>Deposit</span>
                            <span class="text-white">Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function openSnap(token, confirmUrl, resultUrl) {
        window.snap.pay(token, {
            onSuccess: function (result) {
                fetch(confirmUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(result)
                }).then(function (r) { return r.json(); }).then(function (d) {
                    window.location.href = d.status === 'paid'
                        ? resultUrl('success')
                        : resultUrl('pending');
                });
            },
            onPending: function (result) {
                fetch(confirmUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(result)
                }).then(function () {
                    window.location.href = resultUrl('pending');
                });
            },
            onError: function (result) {
                fetch(confirmUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(result)
                }).then(function () {
                    window.location.href = resultUrl('failed');
                });
            },
            onClose: function () {
                var el = document.getElementById('payment-error');
                if (el) { el.textContent = 'Pembayaran dibatalkan.'; el.classList.remove('hidden'); }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var payButton = document.getElementById('pay-button');
        var errorEl = document.getElementById('payment-error');
        var loadingEl = document.getElementById('payment-loading');
        var confirmUrl = '{{ route('payments.confirm', $booking) }}';
        function resultUrl(s) { return '{{ route('payments.result', ['status' => '__STATUS__', 'booking' => $booking]) }}'.replace('__STATUS__', s); }

        if (!payButton) return;

        payButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (payButton.disabled) return;
            payButton.disabled = true;
            payButton.innerHTML = '<div class="h-5 w-5 animate-spin rounded-full border-2 border-white border-t-transparent mx-auto"></div>';
            if (loadingEl) loadingEl.classList.remove('hidden');

            fetch('{{ route('payments.process', $booking) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(function (res) { return res.json().then(function (data) { if (!res.ok) throw new Error(data.error || 'Server error'); return data; }); })
            .then(function (data) {
                if (data.snap_token) {
                    openSnap(data.snap_token, confirmUrl, resultUrl);
                } else {
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Sekarang';
                    if (loadingEl) loadingEl.classList.add('hidden');
                    if (errorEl) { errorEl.textContent = 'Gagal mendapatkan token pembayaran.'; errorEl.classList.remove('hidden'); }
                }
            })
            .catch(function (err) {
                payButton.disabled = false;
                payButton.innerHTML = 'Bayar Sekarang';
                if (loadingEl) loadingEl.classList.add('hidden');
                if (errorEl) { errorEl.textContent = DOMPurify.sanitize(err.message || 'Gagal memproses pembayaran.'); errorEl.classList.remove('hidden'); }
            });
        });
    });
</script>
@endpush