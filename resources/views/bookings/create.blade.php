@extends('layouts.app')

@section('title', 'Booking '.$unit->name)
@section('page_title', 'Booking Unit')

@push('styles')
<style>
  .flatpickr-day.booked {
    background: #fecaca !important;
    color: #dc2626 !important;
    border-color: #fca5a5 !important;
    cursor: not-allowed !important;
    text-decoration: line-through;
  }
  .flatpickr-day.booked:hover {
    background: #fca5a5 !important;
  }
  .flatpickr-day.range-start, .flatpickr-day.range-end {
    background: #4f46e5 !important;
    color: #fff !important;
    border-color: #4f46e5 !important;
  }
  .flatpickr-day.in-range {
    background: #e0e7ff !important;
    color: #4338ca !important;
    border-color: #c7d2fe !important;
  }
  .dark .flatpickr-calendar { background: #1e293b; border-color: #334155; }
  .dark .flatpickr-day { color: #cbd5e1; }
  .dark .flatpickr-day.flatpickr-disabled, .dark .flatpickr-day.flatpickr-disabled:hover { color: #475569; }
  .dark .flatpickr-day.today { border-color: #6366f1; }
  .dark .flatpickr-day.selected, .dark .flatpickr-day.startRange, .dark .flatpickr-day.endRange { background: #4f46e5; border-color: #4f46e5; }
  .dark .flatpickr-months .flatpickr-month { color: #e2e8f0; fill: #e2e8f0; }
  .dark .flatpickr-current-month .flatpickr-monthDropdown-months { color: #e2e8f0; }
  .dark .flatpickr-weekday { color: #94a3b8; }
  .dark .flatpickr-day.inRange { background: #1e1b4b; border-color: #312e81; }
</style>
@endpush

@section('content')
<div class="mb-4">
  <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
  </a>
</div>

<div>
  <div class="card">
    <div class="mb-6">
      <span class="badge-info">{{ $unit->category->name }}</span>
      <h1 class="mt-2 text-2xl font-bold text-slate-800 dark:text-slate-100">Booking {{ $unit->name }}</h1>
      <p class="text-sm text-slate-500 dark:text-slate-400">Pilih tanggal sewa dan sistem akan menghitung harga otomatis.</p>
    </div>

    <form method="post" action="{{ route('bookings.store') }}" class="space-y-5">
      @csrf
      <input type="hidden" name="unit_id" value="{{ $unit->id }}">
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label for="start_date" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Mulai</label>
          <input type="text" name="start_date" id="start_date" class="mt-1.5 input-field" placeholder="Pilih tanggal mulai" value="{{ old('start_date', request('start_date')) }}" required readonly>
        </div>
        <div>
          <label for="end_date" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Selesai</label>
          <input type="text" name="end_date" id="end_date" class="mt-1.5 input-field" placeholder="Pilih tanggal selesai" value="{{ old('end_date', request('end_date')) }}" required readonly>
        </div>
      </div>
      <div>
        <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Catatan</label>
        <textarea name="notes" id="notes" rows="3" class="mt-1.5 input-field" placeholder="Catatan tambahan untuk admin">{{ old('notes') }}</textarea>
      </div>
      <div id="price-preview" class="hidden rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm dark:border-indigo-800 dark:bg-indigo-900/30"></div>
      <label class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
        <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1" class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
        Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" class="font-medium text-indigo-600 underline hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">syarat & ketentuan</a> yang berlaku.
      </label>
      <button id="submit-btn" class="btn-primary w-full" disabled>Booking Sekarang</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
  const deposit = {{ (float) $unit->deposit_amount }};
  const unitId = {{ $unit->id }};
  const startInput = document.getElementById('start_date');
  const endInput = document.getElementById('end_date');
  const preview = document.getElementById('price-preview');
  const submitBtn = document.getElementById('submit-btn');
  let calcTimeout;

  // Fetch booked dates from server
  let bookedDates = [];

  fetch('{{ route('units.booked-dates', $unit) }}')
    .then(function (r) { return r.json(); })
    .then(function (data) {
      bookedDates = data || [];
      ensureFlatpickr(initFlatpickr);
    })
    .catch(function () {
      ensureFlatpickr(initFlatpickr);
    });

  function ensureFlatpickr(callback) {
    if (typeof flatpickr !== 'undefined') { callback(); return; }
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
    s.onload = callback;
    document.head.appendChild(s);
  }

  function isBooked(date) {
    const d = date.toISOString().slice(0, 10);
    return bookedDates.includes(d);
  }

  function initFlatpickr() {
    const commonConfig = {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      locale: { firstDayOfWeek: 1 },
      disable: [isBooked],
      onDayCreate: function(dObj, dStr, fp, dayElem) {
        const dateStr = dayElem.dateObj.toISOString().slice(0, 10);
        if (bookedDates.includes(dateStr)) {
          dayElem.classList.add('booked');
        }
      },
    };

    const startPicker = flatpickr(startInput, {
      ...commonConfig,
      onChange: function(selectedDates, dateStr) {
        endPicker.set('minDate', dateStr || 'today');
        if (endInput.value && endInput.value < dateStr) {
          endPicker.clear();
          endPicker.setDate(dateStr);
        }
        triggerCalc();
      },
    });

    const endPicker = flatpickr(endInput, {
      ...commonConfig,
      minDate: startInput.value || 'today',
      onChange: function() {
        triggerCalc();
        if (!startInput.value) {
          startPicker.open();
        }
      },
    });
  }

  function triggerCalc() {
    clearTimeout(calcTimeout);
    calcTimeout = setTimeout(calcPrice, 400);
  }

  function calcPrice() {
    const s = startInput.value;
    const e = endInput.value;
    if (!s || !e) return;

    const d1 = new Date(s + 'T00:00:00');
    const d2 = new Date(e + 'T00:00:00');
    const days = Math.floor((d2 - d1) / 86400000) + 1;
    if (days < 1) {
      preview.classList.remove('hidden');
      preview.className = 'rounded-lg border border-red-100 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400';
      preview.textContent = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
      submitBtn.disabled = true;
      return;
    }

    fetch('{{ route('calculate.price') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ unit_id: unitId, start_date: s, end_date: e })
    })
    .then(r => r.json())
    .then(d => {
      preview.className = 'rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm text-slate-700 dark:border-indigo-800 dark:bg-indigo-900/30 dark:text-slate-200';
      preview.classList.remove('hidden');
      let html = '';
      d.breakdown.forEach(function (item) {
        html += '<div class="flex justify-between"><span>' + item.label + '</span><span>Rp ' + Number(item.amount).toLocaleString('id-ID') + '</span></div>';
      });
      html += '<div class="mt-1 flex justify-between"><span>Subtotal</span><span class="font-semibold">Rp ' + d.subtotal.toLocaleString('id-ID') + '</span></div>';
      html += '<div class="flex justify-between"><span>Deposit</span><span>Rp ' + d.deposit.toLocaleString('id-ID') + '</span></div>';
      html += '<hr class="my-2 border-indigo-100 dark:border-indigo-800"><div class="flex justify-between font-bold text-indigo-700 dark:text-indigo-400"><span>Total (' + d.days + ' hari)</span><span>Rp ' + d.total.toLocaleString('id-ID') + '</span></div>';
      preview.innerHTML = html;
      updateSubmitBtn();
    })
    .catch(function () {
      preview.className = 'rounded-lg border border-red-100 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400';
      preview.textContent = 'Gagal menghitung harga. Coba lagi.';
      submitBtn.disabled = true;
    });
  }

  function updateSubmitBtn() {
    var priceReady = preview.classList.contains('hidden') === false && preview.querySelector('.font-bold') !== null;
    var termsChecked = document.getElementById('terms_accepted').checked;
    submitBtn.disabled = !(priceReady && termsChecked);
  }

  document.getElementById('terms_accepted').addEventListener('change', updateSubmitBtn);
  calcPrice();
</script>
@endpush
@endsection