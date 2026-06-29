@extends('layouts.app')

@section('title', 'Booking Multi Unit')
@section('page_title', 'Booking Multi Unit')

@section('content')
<div class="mb-4">
  <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Keranjang
  </a>
</div>

<div>
  <div class="card">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Booking {{ count($units) }} Unit</h1>
      <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih tanggal sewa untuk semua unit yang sudah Anda pilih.</p>
    </div>

    <form method="post" action="{{ route('bookings.store') }}" class="space-y-6">
      @csrf

      {{-- Daftar Unit --}}
      <div class="space-y-3">
        @foreach ($units as $unit)
          <div class="flex items-center gap-4 rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-700/50">
            @if ($unit->photos && count($unit->photos) > 0)
              <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->name }}" loading="lazy" class="h-14 w-14 rounded-lg object-cover shrink-0">
            @endif
            <div class="flex-1 min-w-0">
              <div class="font-medium text-slate-800 dark:text-slate-100">{{ $unit->name }}</div>
              <div class="text-xs text-slate-400 dark:text-slate-500">{{ $unit->category->name }}</div>
            </div>
            <div class="text-right shrink-0">
              <div class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</div>
              <div class="text-xs text-slate-400 dark:text-slate-500">/hari</div>
            </div>
            <input type="hidden" name="unit_ids[]" value="{{ $unit->id }}">
          </div>
        @endforeach
      </div>

      {{-- Tanggal --}}
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

      <div id="price-preview" class="hidden rounded-lg border border-indigo-100 bg-indigo-50 p-4 dark:border-indigo-800 dark:bg-indigo-900/30"></div>

      <label class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-300">
        <input type="checkbox" name="terms_accepted" id="terms_accepted" value="1" class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
        Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" class="font-medium text-indigo-600 underline hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">syarat & ketentuan</a> yang berlaku.
      </label>
      <button type="submit" id="submit-btn" class="btn-primary w-full" disabled>Booking {{ count($units) }} Unit</button>
    </form>
  </div>
</div>

@push('scripts')
<script>
  var unitIds = {{ json_encode($units->pluck('id')->toArray()) }};
  var startInput = document.getElementById('start_date');
  var endInput = document.getElementById('end_date');
  var preview = document.getElementById('price-preview');
  var submitBtn = document.querySelector('button[type="submit"]');
  var calcTimeout;

  document.addEventListener('DOMContentLoaded', function() {
    if (typeof flatpickr === 'undefined') {
      var s = document.createElement('script');
      s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
      s.onload = initPickers;
      document.head.appendChild(s);
    } else {
      initPickers();
    }
  });

  function initPickers() {
    startPicker = flatpickr(startInput, {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      onChange: function(selectedDates, dateStr) {
        endPicker.set('minDate', dateStr || 'today');
        if (endInput.value && endInput.value < dateStr) {
          endPicker.clear();
          endPicker.setDate(dateStr);
        }
        calcPrice();
      },
    });

    endPicker = flatpickr(endInput, {
      minDate: 'today',
      dateFormat: 'Y-m-d',
      onChange: function() {
        calcPrice();
        if (!startInput.value) {
          startPicker.open();
        }
      },
    });
  }

  function calcPrice() {
    const s = startInput.value;
    const e = endInput.value;
    if (!s || !e) return;

    const d1 = new Date(s + 'T00:00:00');
    const d2 = new Date(e + 'T00:00:00');
    const days = Math.floor((d2 - d1) / 86400000) + 1;
    if (days < 1) {
      preview.innerHTML = '<div class="text-red-600 text-sm dark:text-red-400">Tanggal selesai tidak boleh sebelum tanggal mulai.</div>';
      preview.classList.remove('hidden');
      return;
    }

    let html = '';
    let promises = unitIds.map(function (id) {
      return fetch('{{ route('calculate.price') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ unit_id: id, start_date: s, end_date: e })
      }).then(function (r) { return r.json(); });
    });

    Promise.all(promises).then(function (results) {
      let grandTotal = 0;
      let grandSubtotal = 0;
      let grandDeposit = 0;
      let maxDays = 0;

      results.forEach(function (d, i) {
        grandSubtotal += d.subtotal;
        grandDeposit += d.deposit;
        grandTotal += d.total;
        if (d.days > maxDays) maxDays = d.days;
        html += '<div class="flex justify-between text-sm"><span>Unit ' + (i + 1) + '</span><span>Rp ' + Number(d.subtotal).toLocaleString('id-ID') + '</span></div>';
      });

      html += '<hr class="my-2 border-indigo-100 dark:border-indigo-800">';
      html += '<div class="flex justify-between"><span>Subtotal</span><span class="font-semibold">Rp ' + grandSubtotal.toLocaleString('id-ID') + '</span></div>';
      html += '<div class="flex justify-between"><span>Deposit</span><span>Rp ' + grandDeposit.toLocaleString('id-ID') + '</span></div>';
      html += '<hr class="my-2 border-indigo-100 dark:border-indigo-800">';
      html += '<div class="flex justify-between font-bold text-indigo-700 dark:text-indigo-400"><span>Total (' + maxDays + ' hari)</span><span>Rp ' + grandTotal.toLocaleString('id-ID') + '</span></div>';
      preview.innerHTML = html;
      preview.className = 'rounded-lg border border-indigo-100 bg-indigo-50 p-4 dark:border-indigo-800 dark:bg-indigo-900/30';
      preview.classList.remove('hidden');
      updateSubmitBtn();
    });
  }

  function updateSubmitBtn() {
    var priceReady = preview.classList.contains('hidden') === false && preview.querySelector('.font-bold') !== null;
    var termsChecked = document.getElementById('terms_accepted').checked;
    submitBtn.disabled = !(priceReady && termsChecked);
  }

  document.getElementById('terms_accepted').addEventListener('change', updateSubmitBtn);
</script>
@endpush
@endsection