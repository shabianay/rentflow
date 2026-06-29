@extends('layouts.admin')
@section('title', 'Kalender Booking')
@section('page_title', 'Kalender')
@section('page_subtitle', 'Lihat semua booking dalam tampilan kalender')

@push('styles')
<style>
  .cal-cell { min-height: 100px; }
  .cal-cell.today { background: #f0f4ff; }
  .dark .cal-cell.today { background: #1e1b4b; }
  .cal-cell.other-month { opacity: 0.3; }
  .cal-badge { font-size: 10px; padding: 1px 4px; border-radius: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: block; margin-top: 1px; cursor: pointer; }
  .cal-badge:hover { opacity: 0.8; }
</style>
@endpush

@section('content')
<div class="card">
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}"
      class="btn-ghost p-2">&larr; {{ $months[$prevMonth] }}</a>
    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $months[$month] }} {{ $year }}</h2>
    <a href="{{ route('admin.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}"
      class="btn-ghost p-2">{{ $months[$nextMonth] }} &rarr;</a>
  </div>

  <div class="grid grid-cols-7 gap-px bg-slate-200 dark:bg-slate-700 rounded-lg overflow-hidden">
    @foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
      <div class="bg-slate-50 dark:bg-slate-800 px-2 py-2 text-center text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">{{ $dayName }}</div>
    @endforeach

    @foreach ($weeks as $week)
      @foreach ($week as $day)
        <div class="cal-cell bg-white dark:bg-slate-800/50 p-1.5 {{ $day['isToday'] ? 'today' : '' }} {{ $day['isCurrentMonth'] ? '' : 'other-month' }}">
          <div class="text-xs font-medium {{ $day['isToday'] ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400' }} mb-1">
            {{ $day['date']->format('j') }}
          </div>
          <div class="space-y-0.5 max-h-[80px] overflow-y-auto">
            @foreach ($day['bookings'] as $booking)
              <a href="{{ route('admin.bookings.show', $booking) }}"
                class="cal-badge
                  @if($booking->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300
                  @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300
                  @elseif($booking->status === 'active') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300
                  @else bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 @endif"
                title="{{ $booking->booking_number }} - {{ $booking->units_list }}">
                {{ $booking->units_list }}
              </a>
            @endforeach
          </div>
        </div>
      @endforeach
    @endforeach
  </div>
</div>

<div class="mt-4 flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-amber-100 dark:bg-amber-900/40"></span> Pending</span>
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-blue-100 dark:bg-blue-900/40"></span> Dikonfirmasi</span>
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-emerald-100 dark:bg-emerald-900/40"></span> Aktif</span>
  <a href="{{ route('admin.bookings.create') }}" class="btn-primary text-xs ml-auto">
    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
    Booking Baru
  </a>
</div>
@endsection
