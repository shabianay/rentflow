@extends('layouts.admin')
@section('title', 'Detail Booking')
@section('page_title', 'Booking #' . $booking->booking_number)
@section('page_subtitle', $booking->user->name . ' — ' . $booking->units_list)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.bookings.index') }}"
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
                    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $booking->booking_number }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Dibuat {{ $booking->created_at->diffForHumans() }}</p>
                </div>
                <span
                    class="badge-{{ in_array($booking->status, ['confirmed', 'active']) ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} text-sm px-3 py-1">
                    {{ $booking->status_label }}
                </span>
            </div>

            <div class="grid gap-4 text-sm sm:grid-cols-2">
                <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Customer</div>
                    <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->user->name }}</div>
                    <div class="text-xs text-slate-400 dark:text-slate-500">{{ $booking->user->email }}</div>
                </div>
                <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal Sewa</div>
                    <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->start_date->format('d M Y') }} —
                        {{ $booking->end_date->format('d M Y') }}</div>
                    <div class="text-xs text-slate-400 dark:text-slate-500">{{ $booking->total_days }} hari</div>
                </div>
            </div>

            {{-- Detail Unit --}}
            <div class="rounded-lg border border-slate-100 dark:border-slate-700">
                <div class="border-b border-slate-100 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">Detail Unit</div>
                <div class="divide-y divide-slate-50 dark:divide-slate-700">
                    @forelse ($booking->items as $item)
                        <div class="flex items-center justify-between px-4 py-3">
                            <div>
                                <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $item->unit->name }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $item->days }} hari x Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $booking->units_list }}</div>
                    @endforelse
                </div>
            </div>

            @if ($booking->notes)
                <div>
                    <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Catatan</div>
                    <div class="mt-1 rounded-lg bg-slate-50 p-3 text-sm text-slate-600 dark:bg-slate-700/50 dark:text-slate-300">{{ $booking->notes }}</div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="card">
                <h3 class="section-title">Status Booking</h3>
                @if ($booking->status === 'active')
                    <form method="post" action="{{ route('admin.bookings.status', $booking) }}" class="mt-4"
                        onsubmit="return confirm('Konfirmasi unit sudah dikembalikan?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn-success w-full">
                            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Konfirmasi Pengembalian
                        </button>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Booking menjadi selesai dan unit otomatis kembali ready.</p>
                    </form>
                @else
                    <form method="post" action="{{ route('admin.bookings.status', $booking) }}" class="mt-4 space-y-3">
                        @csrf @method('PATCH')
                        <select name="status" class="select-field">
                            <option value="confirmed" @selected($booking->status == 'confirmed')>Konfirmasi</option>
                            <option value="active" @selected($booking->status == 'active')>Lunas</option>
                            <option value="completed" @selected($booking->status == 'completed')>Selesai</option>
                            <option value="cancelled" @selected($booking->status == 'cancelled')>Batalkan</option>
                        </select>
                        <button type="submit" class="btn-primary w-full">Update Status</button>
                    </form>
                @endif
            </div>

            <div class="card">
                <h3 class="section-title">Ringkasan Keuangan</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Subtotal</span><span
                            class="font-medium">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Deposit</span><span
                            class="font-medium">Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500 dark:text-slate-400">Diskon</span><span class="font-medium">Rp
                            {{ number_format($booking->discount, 0, ',', '.') }}</span></div>
                    <hr class="border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between text-base"><span class="font-semibold">Total</span><span
                            class="font-bold text-indigo-600 dark:text-indigo-400">Rp
                            {{ number_format($booking->total_amount, 0, ',', '.') }}</span></div>
                </div>
                @if ($booking->payment)
                    <hr class="my-3 border-slate-100 dark:border-slate-700">
                    <div class="flex justify-between text-sm"><span class="text-slate-500 dark:text-slate-400">Pembayaran</span><span
                            class="badge-{{ $booking->payment->status === 'paid' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($booking->payment->status) }}</span>
                    </div>
                @endif
                @if ($booking->invoice)
                    <div class="mt-3">
                        <a href="{{ route('admin.invoices.show', $booking->invoice) }}"
                            class="btn-secondary w-full text-center">
                            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Lihat Invoice
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
