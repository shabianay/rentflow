@extends('layouts.admin')
@section('title', 'Invoice')
@section('page_title', 'Invoice')
@section('page_subtitle', 'Semua invoice')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $invoices->total() }} invoice</div>
            </div>
        </div>
        <form method="get" action="{{ route('admin.invoices.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari invoice..."
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
            <table class="min-w-[820px] w-full text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-6 py-4 whitespace-nowrap">No. Invoice</th>
                        <th class="px-6 py-4 whitespace-nowrap">Customer</th>
                        <th class="px-6 py-4 whitespace-nowrap">Booking</th>
                        <th class="px-6 py-4 whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $i)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">{{ $i->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800 dark:text-slate-100">{{ $i->user->name }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $i->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $i->booking?->booking_number ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium">Rp {{ number_format($i->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="badge-{{ $i->status === 'paid' ? 'success' : ($i->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($i->status) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.invoices.show', $i) }}"
                                    class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada invoice</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $invoices->links() }}</div>
@endsection
