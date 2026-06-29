<div class="flex items-center justify-between mb-4">
  <button type="button" onclick="loadCalendar({{ $prevMonth }}, {{ $prevYear }})" class="btn-ghost p-2">&larr; {{ $months[$prevMonth] }}</button>
  <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100" id="calModalTitle">{{ $months[$month] }} {{ $year }}</h3>
  <button type="button" onclick="loadCalendar({{ $nextMonth }}, {{ $nextYear }})" class="btn-ghost p-2">{{ $months[$nextMonth] }} &rarr;</button>
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

<div class="mt-3 flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-amber-100 dark:bg-amber-900/40"></span> Pending</span>
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-blue-100 dark:bg-blue-900/40"></span> Dikonfirmasi</span>
  <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-emerald-100 dark:bg-emerald-900/40"></span> Aktif</span>
</div>