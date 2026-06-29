@extends('layouts.admin')
@section('title', 'Booking')
@section('page_title', 'Booking')
@section('page_subtitle', 'Kelola semua booking')

@push('styles')
<style>
  #createModal .flatpickr-day.booked { background: #fecaca !important; color: #dc2626 !important; border-color: #fca5a5 !important; cursor: not-allowed !important; text-decoration: line-through; }
  #createModal .flatpickr-day.booked:hover { background: #fca5a5 !important; }
  #createModal .flatpickr-day.range-start, #createModal .flatpickr-day.range-end { background: #4f46e5 !important; color: #fff !important; border-color: #4f46e5 !important; }
  #createModal .flatpickr-day.in-range { background: #e0e7ff !important; color: #4338ca !important; border-color: #c7d2fe !important; }
  .dark #createModal .flatpickr-calendar { background: #1e293b; border-color: #334155; }
  .dark #createModal .flatpickr-day { color: #cbd5e1; }
  .dark #createModal .flatpickr-day.flatpickr-disabled, .dark #createModal .flatpickr-day.flatpickr-disabled:hover { color: #475569; }
  .dark #createModal .flatpickr-day.today { border-color: #6366f1; }
  .dark #createModal .flatpickr-day.selected, .dark #createModal .flatpickr-day.startRange, .dark #createModal .flatpickr-day.endRange { background: #4f46e5; border-color: #4f46e5; }
  .dark #createModal .flatpickr-months .flatpickr-month { color: #e2e8f0; fill: #e2e8f0; }
  .dark #createModal .flatpickr-current-month .flatpickr-monthDropdown-months { color: #e2e8f0; }
  .dark #createModal .flatpickr-weekday { color: #94a3b8; }
  .dark #createModal .flatpickr-day.inRange { background: #1e1b4b; border-color: #312e81; }
  #calendarModal .cal-cell { min-height: 90px; }
  #calendarModal .cal-cell.today { background: #f0f4ff; }
  .dark #calendarModal .cal-cell.today { background: #1e1b4b; }
  #calendarModal .cal-cell.other-month { opacity: 0.3; }
  #calendarModal .cal-badge { font-size: 10px; padding: 1px 4px; border-radius: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: block; margin-top: 1px; cursor: pointer; }
  #calendarModal .cal-badge:hover { opacity: 0.8; }
</style>
@endpush

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
  <div class="flex items-center gap-3">
    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $bookings->total() }} booking</div>
  </div>
  <div class="flex items-center gap-2">
    <button type="button" onclick="openCalendarModal()" class="btn-secondary">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Kalender
    </button>
    <button type="button" data-create-booking onclick="openCreateModal()" class="btn-primary">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
      Buat Booking
    </button>
    <form method="get" action="{{ route('admin.bookings.index') }}" class="flex gap-2">
      <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari booking..." class="input-field w-48 sm:w-56">
      <button type="submit" class="btn-primary"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
    </form>
  </div>
</div>

<div class="card overflow-hidden !p-0">
  <div class="overflow-x-auto">
    <table class="min-w-[900px] w-full text-left text-sm">
      <thead>
        <tr class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
          <th class="px-6 py-4 whitespace-nowrap">No. Booking</th>
          <th class="px-6 py-4 whitespace-nowrap">Customer</th>
          <th class="px-6 py-4 whitespace-nowrap">Unit</th>
          <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
          <th class="px-6 py-4 whitespace-nowrap">Status</th>
          <th class="px-6 py-4 whitespace-nowrap">Total</th>
          <th class="px-6 py-4 whitespace-nowrap"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $b)
          <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">{{ $b->booking_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="font-medium text-slate-800 dark:text-slate-100">{{ $b->user->name }}</div>
              <div class="text-xs text-slate-400 dark:text-slate-500">{{ $b->user->email }}</div>
            </td>
            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $b->units_list }}</td>
            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $b->start_date->format('d/m/Y') }} - {{ $b->end_date->format('d/m/Y') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="badge-{{ in_array($b->status, ['confirmed','active']) ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'info')) }}">
                {{ $b->status_label }}
              </span>
            </td>
            <td class="px-6 py-4 font-medium">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <a href="{{ route('admin.bookings.show', $b) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40">Detail</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada booking</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
<div class="mt-6">{{ $bookings->links() }}</div>

{{-- Modal Kalender --}}
<div id="calendarModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/70" onclick="closeCalendarModal()"></div>
    <div class="relative w-full max-w-4xl rounded-xl bg-white shadow-2xl dark:bg-slate-800">
      <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Kalender Booking</h2>
        <button type="button" onclick="closeCalendarModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-300">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <div id="calendarModalBody" class="px-6 py-5 min-h-[300px]">
        <div class="flex items-center justify-center py-12 text-sm text-slate-400">
          <svg class="mr-2 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
          Memuat kalender...
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal Buat Booking --}}
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
  <div class="flex min-h-full items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/70" onclick="closeCreateModal()"></div>
    <div class="relative w-full max-w-2xl rounded-xl bg-white shadow-2xl dark:bg-slate-800">
      <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Buat Booking Baru</h2>
        <button type="button" onclick="closeCreateModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-300">
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form method="post" action="{{ route('admin.bookings.store') }}" class="space-y-5 px-6 py-5">
        @csrf

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Customer</label>
          <div id="existing-customer-group">
            <div class="relative mt-1.5" id="customer-picker">
              <input type="text" id="customer-search" class="input-field" placeholder="Ketik nama customer..." value="{{ $selectedCustomerName ?? '' }}" autocomplete="off">
              <input type="hidden" name="user_id" id="customer-id" value="{{ old('user_id') }}">
              <div id="customer-dropdown" class="hidden absolute left-0 right-0 top-full z-10 mt-1 max-h-56 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg dark:border-slate-600 dark:bg-slate-800"></div>
            </div>
          </div>
          <div id="new-customer-group" class="hidden mt-1.5 space-y-3">
            <input type="text" name="new_customer_name" id="new_customer_name" class="input-field" placeholder="Nama lengkap" value="{{ old('new_customer_name') }}">
            <input type="email" name="new_customer_email" class="input-field" placeholder="Email" value="{{ old('new_customer_email') }}">
            <input type="text" name="new_customer_phone" class="input-field" placeholder="No. HP (opsional)" value="{{ old('new_customer_phone') }}">
            <input type="password" name="new_customer_password" class="input-field" placeholder="Password" value="password">
            <p class="text-xs text-slate-400">Password default: <code>password</code></p>
          </div>
          <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
            <input type="checkbox" id="new-customer-toggle" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
            + Customer Baru (offline)
          </label>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Unit</label>
          <div id="unit-checkbox-list" class="mt-1.5 max-h-56 overflow-y-auto space-y-1.5 rounded-lg border border-slate-200 p-3 dark:border-slate-600">
            @forelse ($units as $unit)
              <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors hover:bg-slate-50 dark:hover:bg-slate-700/50 {{ in_array($unit->id, old('unit_ids', [])) ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                <input type="checkbox" name="unit_ids[]" value="{{ $unit->id }}" class="unit-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600" {{ in_array($unit->id, old('unit_ids', [])) ? 'checked' : '' }}>
                <div class="flex-1 min-w-0">
                  <div class="font-medium text-slate-800 dark:text-slate-100">{{ $unit->name }}</div>
                  <div class="text-xs text-slate-400 dark:text-slate-500">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}/hari</div>
                </div>
              </label>
            @empty
              <p class="py-4 text-center text-sm text-slate-400">Tidak ada unit tersedia</p>
            @endforelse
          </div>
          <p id="unit-selected-count" class="mt-1 text-xs text-slate-400 dark:text-slate-500">0 unit dipilih</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Mulai</label>
            <input type="text" name="start_date" id="modal_start_date" class="mt-1.5 input-field" placeholder="Pilih tanggal" value="{{ old('start_date') }}" required readonly>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Selesai</label>
            <input type="text" name="end_date" id="modal_end_date" class="mt-1.5 input-field" placeholder="Pilih tanggal" value="{{ old('end_date') }}" required readonly>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Catatan</label>
          <textarea name="notes" rows="2" class="mt-1.5 input-field" placeholder="Catatan internal (opsional)">{{ old('notes') }}</textarea>
        </div>

        <div id="modal_price_preview" class="hidden rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm dark:border-indigo-800 dark:bg-indigo-900/30"></div>

        <div class="flex items-center justify-between gap-6 border-t border-slate-100 pt-4 dark:border-slate-700">
          <div class="flex flex-col gap-2">
            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
              <input type="checkbox" name="mark_paid" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
              Tandai sudah bayar (offline)
            </label>
            <label class="flex items-start gap-2 text-xs text-slate-500 dark:text-slate-400">
              <input type="checkbox" name="terms_accepted" id="modal_terms_accepted" value="1" class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600">
              Saya setuju dengan <a href="{{ route('terms') }}" target="_blank" class="font-medium text-indigo-600 underline hover:text-indigo-700 dark:text-indigo-400">syarat & ketentuan</a>.
            </label>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" onclick="closeCreateModal()" class="btn-ghost">Batal</button>
            <button type="submit" id="modal_submit_btn" class="btn-primary" disabled>Buat Booking</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
var modalStartPicker, modalEndPicker;

function openCreateModal() {
  document.getElementById('createModal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
  if (modalStartPicker) modalStartPicker.destroy();
  if (modalEndPicker) modalEndPicker.destroy();
  initModalFlatpickr();
}

function closeCreateModal() {
  document.getElementById('createModal').classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

function initModalFlatpickr() {
  if (typeof flatpickr === 'undefined') {
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
    s.onload = initModalFlatpickr;
    document.head.appendChild(s);
    return;
  }
  var startInput = document.getElementById('modal_start_date');
  var endInput = document.getElementById('modal_end_date');
  var preview = document.getElementById('modal_price_preview');
  var submitBtn = document.getElementById('modal_submit_btn');
  var unitCheckboxes = document.querySelectorAll('#createModal .unit-checkbox');
  var unitCountEl = document.getElementById('unit-selected-count');
  var calcTimeout;

  function updateUnitCount() {
    var count = getSelectedUnitIds().length;
    unitCountEl.textContent = count + ' unit dipilih';
  }

  function triggerCalc() {
    clearTimeout(calcTimeout);
    calcTimeout = setTimeout(calcPrice, 400);
  }

  function getSelectedUnitIds() {
    var ids = [];
    unitCheckboxes.forEach(function(cb) {
      if (cb.checked) ids.push(cb.value);
    });
    return ids;
  }

  function calcPrice() {
    var s = startInput.value;
    var e = endInput.value;
    var unitIds = getSelectedUnitIds();
    if (!s || !e || unitIds.length === 0) return;

    var promises = [];
    unitIds.forEach(function(id) {
      promises.push(
        fetch('{{ route('calculate.price') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ unit_id: id, start_date: s, end_date: e })
        }).then(function(r) { return r.json(); })
      );
    });

    Promise.all(promises)
    .then(function(results) {
      var totalSubtotal = 0, totalDeposit = 0, totalAll = 0, maxDays = 0;
      var breakdownMap = {};
      results.forEach(function(d, i) {
        totalSubtotal += d.subtotal;
        totalDeposit += d.deposit;
        totalAll += d.total;
        if (d.days > maxDays) maxDays = d.days;
        if (d.breakdown) {
          d.breakdown.forEach(function(item) {
            var key = item.label;
            breakdownMap[key] = (breakdownMap[key] || 0) + item.amount;
          });
        }
      });
      preview.classList.remove('hidden');
      preview.className = 'rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm dark:border-indigo-800 dark:bg-indigo-900/30';
      var html = '';
      Object.keys(breakdownMap).forEach(function(label) {
        html += '<div class="flex justify-between"><span>' + label + '</span><span>Rp ' + Number(breakdownMap[label]).toLocaleString('id-ID') + '</span></div>';
      });
      html += '<hr class="my-2 border-indigo-100 dark:border-indigo-800"><div class="flex justify-between font-bold text-indigo-700 dark:text-indigo-400"><span>Total (' + maxDays + ' hari, ' + unitIds.length + ' unit)</span><span>Rp ' + totalAll.toLocaleString('id-ID') + '</span></div>';
      preview.innerHTML = html;
      updateModalSubmitBtn();
    })
    .catch(function() {
      preview.classList.remove('hidden');
      preview.className = 'rounded-lg border border-red-100 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400';
      preview.textContent = 'Gagal menghitung harga.';
      submitBtn.disabled = true;
    });
  }

  function updateModalSubmitBtn() {
    var priceReady = preview.classList.contains('hidden') === false && preview.querySelector('.font-bold') !== null;
    var termsChecked = document.getElementById('modal_terms_accepted').checked;
    submitBtn.disabled = !(priceReady && termsChecked);
  }

  document.getElementById('modal_terms_accepted').addEventListener('change', updateModalSubmitBtn);

  modalStartPicker = flatpickr(startInput, {
    minDate: 'today',
    dateFormat: 'Y-m-d',
    onChange: function(sel, dateStr) {
      modalEndPicker.set('minDate', dateStr || 'today');
      if (endInput.value && endInput.value < dateStr) {
        modalEndPicker.clear();
        modalEndPicker.setDate(dateStr);
      }
      triggerCalc();
    }
  });

  modalEndPicker = flatpickr(endInput, {
    minDate: 'today',
    dateFormat: 'Y-m-d',
    onChange: function() { triggerCalc(); }
  });

  unitCheckboxes.forEach(function(cb) {
    cb.addEventListener('change', function() { updateUnitCount(); triggerCalc(); });
  });
  updateUnitCount();

  // Customer searchable dropdown
  var customers = [
    @foreach ($customers as $c)
      { id: {{ $c->id }}, name: '{{ addslashes($c->name) }}', email: '{{ addslashes($c->email) }}' },
    @endforeach
  ];
  var custSearch = document.getElementById('customer-search');
  var custId = document.getElementById('customer-id');
  var custDropdown = document.getElementById('customer-dropdown');
  var selectedIndex = -1;

  function renderCustomers(query) {
    var q = query.toLowerCase().trim();
    var filtered = q ? customers.filter(function(c) { return c.name.toLowerCase().includes(q) || c.email.toLowerCase().includes(q); }) : customers;
    if (filtered.length === 0) {
      custDropdown.classList.add('hidden');
      return;
    }
    var html = '';
    filtered.forEach(function(c, i) {
      html += '<div class="cursor-pointer px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 dark:text-slate-200 dark:hover:bg-indigo-900/30 ' + (i === selectedIndex ? 'bg-indigo-50 dark:bg-indigo-900/30' : '') + '" data-id="' + c.id + '" data-name="' + c.name.replace(/'/g, '&#39;') + '">' + c.name + ' <span class="text-xs text-slate-400">' + c.email + '</span></div>';
    });
    custDropdown.innerHTML = html;
    custDropdown.classList.remove('hidden');

    custDropdown.querySelectorAll('div').forEach(function(el) {
      el.addEventListener('click', function() {
        selectCustomer(this.dataset.id, this.dataset.name);
      });
    });
  }

  function selectCustomer(id, name) {
    custId.value = id;
    custSearch.value = name;
    custDropdown.classList.add('hidden');
    custSearch.classList.remove('border-red-400');
  }

  custSearch.addEventListener('input', function() {
    selectedIndex = -1;
    if (this.value.trim()) {
      renderCustomers(this.value);
    } else {
      custId.value = '';
      custDropdown.classList.add('hidden');
    }
  });

  custSearch.addEventListener('focus', function() {
    if (!this.value.trim() && !custId.value) {
      renderCustomers('');
    }
  });

  custSearch.addEventListener('keydown', function(e) {
    var items = custDropdown.querySelectorAll('div[data-id]');
    if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, items.length - 1); renderCustomers(this.value); }
    if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); renderCustomers(this.value); }
    if (e.key === 'Enter' && selectedIndex >= 0 && items[selectedIndex]) { e.preventDefault(); items[selectedIndex].click(); }
    if (e.key === 'Escape') { custDropdown.classList.add('hidden'); }
  });

  document.addEventListener('click', function(e) {
    if (!document.getElementById('customer-picker').contains(e.target)) custDropdown.classList.add('hidden');
  });

  // Customer toggle
  var newCustToggle = document.getElementById('new-customer-toggle');
  var existingGroup = document.getElementById('existing-customer-group');
  var newGroup = document.getElementById('new-customer-group');

  newCustToggle.addEventListener('change', function() {
    if (this.checked) {
      existingGroup.classList.add('hidden');
      newGroup.classList.remove('hidden');
      custId.value = '';
    } else {
      existingGroup.classList.remove('hidden');
      newGroup.classList.add('hidden');
    }
  });
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { closeCreateModal(); closeCalendarModal(); }
});

// Calendar modal
function openCalendarModal() {
  document.getElementById('calendarModal').classList.remove('hidden');
  document.body.classList.add('overflow-hidden');
  loadCalendar(new Date().getMonth() + 1, new Date().getFullYear());
}

function closeCalendarModal() {
  document.getElementById('calendarModal').classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

function loadCalendar(month, year) {
  var body = document.getElementById('calendarModalBody');
  body.innerHTML = '<div class="flex items-center justify-center py-12 text-sm text-slate-400"><svg class="mr-2 h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Memuat kalender...</div>';
  fetch('{{ route('admin.calendar.data') }}?month=' + month + '&year=' + year)
    .then(function(r) { return r.json(); })
    .then(function(d) { body.innerHTML = d.html; })
    .catch(function() {
      body.innerHTML = '<div class="flex items-center justify-center py-12 text-sm text-red-500">Gagal memuat kalender.</div>';
    });
}
</script>
@endpush
