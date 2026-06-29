@extends('layouts.app')

@section('title', 'Invoice Saya')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
  <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Invoice Saya</h1>
  <form method="get" action="{{ route('invoices.index') }}" class="flex gap-2">
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari invoice..." class="input-field w-48 sm:w-56">
    <button type="submit" class="btn-primary"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
  </form>
</div>

@if($invoices->isEmpty())
  <div class="card py-12 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500">
      <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-slate-600 dark:text-slate-300">Belum Ada Invoice</h3>
    <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">Invoice akan muncul setelah pembayaran.</p>
  </div>
@else
  <div class="space-y-4">
    @foreach ($invoices as $i)
      <div class="card-hover">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-2">
              <span class="badge-{{ $i->status === 'paid' ? 'success' : ($i->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($i->status) }}</span>
              <span class="text-xs text-slate-400 dark:text-slate-500">{{ $i->invoice_number }}</span>
            </div>
            <h3 class="mt-2 text-base font-semibold text-slate-800 dark:text-slate-100">{{ $i->booking->units_list }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $i->created_at->format('d M Y') }}</p>
          </div>
          <div class="text-right">
            <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($i->amount, 0, ',', '.') }}</div>
            <a href="{{ route('invoices.show', $i) }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Detail →</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="mt-6">{{ $invoices->links() }}</div>
@endif
@endsection
