@extends('layouts.app')

@section('title', 'Detail Invoice')

@section('content')
<div class="mb-4">
  <a href="{{ route('invoices.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Invoice
  </a>
</div>

<div class="mx-auto max-w-7xl">
  <div class="card">
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Invoice {{ $invoice->invoice_number }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $invoice->created_at->diffForHumans() }}</p>
      </div>
      <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }} text-sm px-3 py-1">
        {{ ucfirst($invoice->status) }}
      </span>
    </div>

    <div class="mt-6 grid gap-4 text-sm">
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3 sm:col-span-2">
        <div class="text-xs text-slate-500 dark:text-slate-400">Customer</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $invoice->user->name }}</div>
        <div class="text-xs text-slate-400 dark:text-slate-500">{{ $invoice->user->email }}</div>
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">No. Booking</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $invoice->booking->booking_number }}</div>
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal Sewa</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $invoice->booking->start_date->format('d M Y') }} — {{ $invoice->booking->end_date->format('d M Y') }}</div>
      </div>

      {{-- Detail Barang --}}
      <div class="rounded-lg border border-slate-100 dark:border-slate-700 sm:col-span-2">
        <div class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50 px-4 py-2 text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Detail Barang</div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700">
          @forelse ($invoice->booking->items as $item)
            <div class="flex items-center justify-between px-4 py-3">
              <div>
                <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $item->unit->name }}</div>
                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $item->days }} hari x Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</div>
              </div>
              <div class="text-sm font-semibold text-slate-700 dark:text-slate-200">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
          @empty
            <div class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $invoice->booking->units_list }}</div>
          @endforelse
        </div>
      </div>

      {{-- Ringkasan --}}
      <div class="sm:col-span-2 space-y-1 text-sm">
        <div class="flex justify-between px-4"><span class="text-slate-500 dark:text-slate-400">Subtotal</span><span>Rp {{ number_format($invoice->booking->subtotal, 0, ',', '.') }}</span></div>
        @if ($invoice->booking->deposit_amount > 0)
          <div class="flex justify-between px-4"><span class="text-slate-500 dark:text-slate-400">Deposit</span><span>Rp {{ number_format($invoice->booking->deposit_amount, 0, ',', '.') }}</span></div>
        @endif
        @if ($invoice->booking->discount > 0)
          <div class="flex justify-between px-4"><span class="text-slate-500 dark:text-slate-400">Diskon</span><span class="text-red-600">-Rp {{ number_format($invoice->booking->discount, 0, ',', '.') }}</span></div>
        @endif
        <hr class="border-slate-100 dark:border-slate-700">
        <div class="flex justify-between px-4 font-bold text-indigo-700 dark:text-indigo-300"><span>Total</span><span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span></div>
      </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
      <a href="{{ route('invoices.download', $invoice) }}" class="btn-primary">Download PDF</a>
      <button onclick="window.print()" class="btn-secondary">Cetak</button>
    </div>
  </div>
</div>
@endsection
