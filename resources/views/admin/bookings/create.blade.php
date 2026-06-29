@extends('layouts.admin')
@section('title', 'Buat Booking')
@section('page_title', 'Buat Booking')
@section('page_subtitle', 'Buatkan booking untuk customer')

@push('styles')
<style>
  .flatpickr-day.booked { background: #fecaca !important; color: #dc2626 !important; border-color: #fca5a5 !important; cursor: not-allowed !important; text-decoration: line-through; }
  .flatpickr-day.booked:hover { background: #fca5a5 !important; }
  .flatpickr-day.range-start, .flatpickr-day.range-end { background: #4f46e5 !important; color: #fff !important; border-color: #4f46e5 !important; }
  .flatpickr-day.in-range { background: #e0e7ff !important; color: #4338ca !important; border-color: #c7d2fe !important; }
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
  <a href="{{ route('admin.calendar') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Kalender
  </a>
</div>

<div class="max-w-2xl">
  <div class="card">
    <form method="post" action="{{ route('admin.bookings.store') }}" class="space-y-5">
      @csrf

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Customer</label>
        <select name="user_id" class="mt-1.5 select-field" required>
          <option value="">Pilih customer</option>
          @foreach ($customers as $customer)
            <option value="{{ $customer->id }}" {{ old('user_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->email }})</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Unit</label>
        <select name="unit_ids[]" class="mt-1.5 select-field" multiple required size="6">
          @foreach ($units as $unit)
            <option value="{{ $unit->id }}" {{ in_array($unit->id, old('unit_ids', [])) ? 'selected' : '' }}>
              {{ $unit->name }} — Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}/hari
            </option>
          @endforeach
        </select>
        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Tahan Ctrl untuk pilih lebih dari satu unit</p>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Mulai</label>
          <input type="text" name="start_date" id="start_date" class="mt-1.5 input-field" placeholder="Pilih tanggal" value="{{ old('start_date') }}" required readonly>
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Selesai</label>
          <input type="text" name="end_date" id="end_date" class="mt-1.5 input-field" placeholder="Pilih tanggal" value="{{ old('end_date') }}" required readonly>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Catatan</label>
        <textarea name="notes" rows="3" class="mt-1.5 input-field" placeholder="Catatan internal (opsional)">{{ old('notes') }}</textarea>
      </div>

      <div id="price-preview" class="hidden rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm dark:border-indigo-800 dark:bg-indigo-900/30"></div>

      <div class="flex items-center gap-6 pt-2">
        <button type="submit" id="submit-btn" class="btn-primary" disabled>Buat Booking</button>
        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
          <input type="checkbox" name="mark_paid" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
          Tandai sudah bayar (offline)
        </label>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  var startInput = document.getElementById('start_date');
  var endInput = document.getElementById('end_date');
  var preview = document.getElementById('price-preview');
  var submitBtn = document.getElementById('submit-btn');
  var unitSelect = document.querySelector('select[name="unit_ids[]"]');
  var calcTimeout;

  function ensureFlatpickr(cb) {
    if (typeof flatpickr !== 'undefined') { cb(); return; }
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
    s.onload = cb;
    document.head.appendChild(s);
  }

  ensureFlatpickr(function() {
    var startPicker = flatpickr(startInput, {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      onChange: function(sel, dateStr) {
        endPicker.set('minDate', dateStr || 'today');
        if (endInput.value && endInput.value < dateStr) {
          endPicker.clear();
          endPicker.setDate(dateStr);
        }
        triggerCalc();
      }
    });

    var endPicker = flatpickr(endInput, {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      onChange: function() { triggerCalc(); }
    });
  });

  function triggerCalc() {
    clearTimeout(calcTimeout);
    calcTimeout = setTimeout(calcPrice, 400);
  }

  function getSelectedUnitIds() {
    var ids = [];
    for (var i = 0; i < unitSelect.options.length; i++) {
      if (unitSelect.options[i].selected) ids.push(unitSelect.options[i].value);
    }
    return ids;
  }

  function calcPrice() {
    var s = startInput.value;
    var e = endInput.value;
    var unitIds = getSelectedUnitIds();

    if (!s || !e || unitIds.length === 0) return;

    // For simplicity, show price for first selected unit as estimate
    fetch('{{ route('calculate.price') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: JSON.stringify({ unit_id: unitIds[0], start_date: s, end_date: e })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
      preview.classList.remove('hidden');
      preview.className = 'rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm dark:border-indigo-800 dark:bg-indigo-900/30';
      var html = '';
      d.breakdown.forEach(function(item) {
        html += '<div class="flex justify-between"><span>' + item.label + '</span><span>Rp ' + Number(item.amount).toLocaleString('id-ID') + '</span></div>';
      });
      html += '<hr class="my-2 border-indigo-100 dark:border-indigo-800"><div class="flex justify-between font-bold text-indigo-700 dark:text-indigo-400"><span>Estimasi (' + d.days + ' hari, ' + unitIds.length + ' unit)</span><span>Rp ' + (d.total * unitIds.length).toLocaleString('id-ID') + '</span></div>';
      if (unitIds.length > 1) {
        html += '<p class="mt-2 text-xs text-slate-400">*Estimasi per unit, total final dihitung saat simpan.</p>';
      }
      preview.innerHTML = html;
      submitBtn.disabled = false;
    })
    .catch(function() {
      preview.classList.remove('hidden');
      preview.className = 'rounded-lg border border-red-100 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400';
      preview.textContent = 'Gagal menghitung harga.';
      submitBtn.disabled = true;
    });
  }

  unitSelect.addEventListener('change', triggerCalc);
});
</script>
@endpush
