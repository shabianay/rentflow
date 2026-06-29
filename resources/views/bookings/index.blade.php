@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
  <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Booking Saya</h1>
  <form method="get" action="{{ route('bookings.index') }}" class="flex gap-2">
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari booking..." class="input-field w-48 sm:w-56">
    <button type="submit" class="btn-primary"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
  </form>
</div>

@if($bookings->isEmpty())
  <div class="card py-12 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500">
      <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-slate-600 dark:text-slate-300">Belum Ada Booking</h3>
    <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">Anda belum melakukan booking apapun.</p>
    <a href="{{ route('catalog') }}" class="btn-primary mt-4 inline-flex">Cari Unit</a>
  </div>
@else
  <div class="space-y-4">
    @foreach ($bookings as $b)
      <div class="card-hover">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <div class="flex items-center gap-2">
              <span class="badge-{{ in_array($b->status, ['confirmed','active']) ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'info')) }}">
                {{ $b->status_label }}
              </span>
              <span class="text-xs text-slate-400 dark:text-slate-500">#{{ $b->booking_number }}</span>
            </div>
            <h3 class="mt-2 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $b->units_list }}</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $b->start_date->format('d M Y') }} — {{ $b->end_date->format('d M Y') }} ({{ $b->total_days }} hari)</p>
          </div>
          <div class="text-right">
            <div class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
            <a href="{{ route('bookings.show', $b) }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Detail →</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="mt-6">{{ $bookings->links() }}</div>
@endif
@endsection
