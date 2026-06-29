@extends('layouts.admin')
@section('title', 'Pembayaran')
@section('page_title', 'Pembayaran')
@section('page_subtitle', 'Verifikasi pembayaran masuk')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $payments->total() }} pembayaran</div>
            </div>
        </div>
        <form method="get" action="{{ route('admin.payments.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari pembayaran..."
                class="input-field w-48 sm:w-56">
            <button type="submit" class="btn-primary"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg></button>
        </form>
    </div>
    <div class="card overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-[800px] w-full text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-6 py-4 whitespace-nowrap">No. Payment</th>
                        <th class="px-6 py-4 whitespace-nowrap">Booking</th>
                        <th class="px-6 py-4 whitespace-nowrap">Customer</th>
                        <th class="px-6 py-4 whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-4 whitespace-nowrap">Metode</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $p)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">{{ $p->payment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $p->booking?->booking_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $p->booking?->user?->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 capitalize">{{ $p->method ? str_replace('_', ' ', $p->method) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="badge-{{ $p->status === 'paid' ? 'success' : ($p->status === 'pending' ? 'warning' : ($p->status === 'failed' || $p->status === 'refunded' ? 'danger' : 'info')) }}">
                                    {{ $p->status === 'refunded' ? 'Cancel' : ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.payments.show', $p) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada
                                pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $payments->links() }}</div>
@endsection
