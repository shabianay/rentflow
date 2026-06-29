@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="mb-4">
  <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Booking Saya
  </a>
</div>

<div>
  <div class="card">
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Detail Booking</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">#{{ $booking->booking_number }}</p>
      </div>
      <span class="badge-{{ in_array($booking->status, ['confirmed','active']) ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} text-sm px-3 py-1">
        {{ $booking->status_label }}
      </span>
    </div>

    <div class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3 sm:col-span-2">
        <div class="text-xs text-slate-500 dark:text-slate-400">Unit Dibooking</div>
        @forelse ($booking->items as $item)
          <div class="mt-1 flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-1 last:border-0">
            <span class="font-medium text-slate-800 dark:text-slate-100">{{ $item->unit->name }}</span>
            <span class="text-sm text-slate-600 dark:text-slate-300">{{ $item->days }} hari - Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
          </div>
        @empty
          <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->unit->name }}</div>
        @endforelse
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal Mulai</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->start_date->format('d M Y') }}</div>
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal Selesai</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->end_date->format('d M Y') }}</div>
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Total Hari</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $booking->total_days }} hari</div>
      </div>
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Subtotal</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">Rp {{ number_format($booking->subtotal, 0, ',', '.') }}</div>
      </div>
      @if($booking->deposit_amount > 0)
      <div class="rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3">
        <div class="text-xs text-slate-500 dark:text-slate-400">Deposit</div>
        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">Rp {{ number_format($booking->deposit_amount, 0, ',', '.') }}</div>
      </div>
      @endif
      <div class="rounded-lg bg-indigo-50 dark:bg-indigo-900/30 p-3">
        <div class="text-xs text-indigo-600 dark:text-indigo-400">Total</div>
        <div class="mt-0.5 font-bold text-indigo-700 dark:text-indigo-300">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
      </div>
    </div>

    @if ($booking->notes)
      <div class="mt-4">
        <div class="text-xs font-medium text-slate-500 dark:text-slate-400">Catatan</div>
        <div class="mt-1 rounded-lg bg-slate-50 dark:bg-slate-700/50 p-3 text-sm text-slate-600 dark:text-slate-300">{{ $booking->notes }}</div>
      </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
      @if ($booking->status === 'pending' && $booking->payment)
        <a href="{{ route('payments.show', $booking) }}" class="btn-success">
          <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          Bayar Sekarang
        </a>
        <form method="post" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
          @csrf
          <button type="submit" class="btn-danger">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Batalkan Booking
          </button>
        </form>
      @endif
      @if ($booking->invoice)
        <a href="{{ route('invoices.show', $booking->invoice) }}" class="btn-secondary">
          <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Lihat Invoice
        </a>
      @endif
    </div>
  </div>

  {{-- Review Section --}}
  @if ($booking->status === 'completed')
    <div class="mt-6 space-y-4">
      <h2 class="section-title">Berikan Ulasan</h2>

      @php $units = $booking->items->count() ? $booking->items->load('unit') : collect([(object)['unit_id' => $booking->unit_id, 'unit' => $booking->unit]]); @endphp

      @foreach ($units as $item)
        @php $unit = $item->unit; $existing = $existingReviews->get($unit->id); @endphp
        <div class="card">
          <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-700 pb-3 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
              {{ substr($unit->name, 0, 1) }}
            </div>
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $unit->name }}</h3>
          </div>

          @if ($existing)
            <div class="flex items-center gap-2 mb-3">
              <div class="flex items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                  <svg class="h-4 w-4 {{ $i <= $existing->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                @endfor
              </div>
              <span class="text-xs text-emerald-600 dark:text-emerald-400">Ulasan Anda</span>
              <div class="ml-auto flex items-center gap-1">
                <button type="button" onclick="document.getElementById('edit-review-{{ $unit->id }}').classList.toggle('hidden')" class="btn-ghost p-1.5 text-xs">Edit</button>
                <form method="post" action="{{ route('reviews.destroy', $existing) }}" onsubmit="return confirm('Hapus ulasan ini?')" class="inline">
                  @csrf @method('delete')
                  <button type="submit" class="btn-ghost p-1.5 text-xs text-red-500 hover:text-red-700">Hapus</button>
                </form>
              </div>
            </div>
            @if ($existing->review)
              <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 p-4 text-sm text-emerald-700 dark:text-emerald-300">
                <p>{{ $existing->review }}</p>
              </div>
            @endif

            {{-- Inline Edit Form --}}
            <div id="edit-review-{{ $unit->id }}" class="hidden mt-3 border-t border-slate-100 dark:border-slate-700 pt-4">
              <form method="post" action="{{ route('reviews.update', $existing) }}" class="space-y-4">
                @csrf @method('put')
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Rating</label>
                  <div class="mt-1.5 flex items-center gap-1 star-rating" data-unit="edit-{{ $unit->id }}">
                    @for ($i = 1; $i <= 5; $i++)
                      <button type="button" data-star="{{ $i }}"
                        class="star-btn h-8 w-8 {{ $i <= $existing->rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-400' }} hover:text-amber-400 dark:hover:text-amber-300 focus:outline-none">
                        <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </button>
                    @endfor
                    <input type="hidden" name="rating" class="rating-value" value="{{ $existing->rating }}">
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Ulasan (opsional)</label>
                  <textarea name="review" rows="3" class="mt-1.5 input-field" placeholder="Ceritakan pengalaman Anda...">{{ $existing->review }}</textarea>
                </div>
                <div class="flex gap-2">
                  <button type="submit" class="btn-primary text-sm">Simpan</button>
                  <button type="button" onclick="document.getElementById('edit-review-{{ $unit->id }}').classList.add('hidden')" class="btn-ghost text-sm">Batal</button>
                </div>
              </form>
            </div>
          @else
            <form method="post" action="{{ route('reviews.store') }}" class="space-y-4">
              @csrf
              <input type="hidden" name="booking_id" value="{{ $booking->id }}">
              <input type="hidden" name="unit_id" value="{{ $unit->id }}">

              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Rating</label>
                <div class="mt-1.5 flex items-center gap-1 star-rating" data-unit="{{ $unit->id }}">
                  @for ($i = 1; $i <= 5; $i++)
                    <button type="button" data-star="{{ $i }}"
                      class="star-btn h-8 w-8 text-slate-300 dark:text-slate-400 hover:text-amber-400 dark:hover:text-amber-300 focus:outline-none">
                      <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    </button>
                  @endfor
                  <input type="hidden" name="rating" class="rating-value" value="">
                </div>
                @error('rating') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Ulasan (opsional)</label>
                <textarea name="review" rows="3" class="mt-1.5 input-field" placeholder="Ceritakan pengalaman Anda dengan {{ $unit->name }}...">{{ old('review') }}</textarea>
              </div>

              <button type="submit" class="btn-primary">Kirim Ulasan untuk {{ $unit->name }}</button>
            </form>
          @endif
        </div>
      @endforeach
    </div>
  @endif
</div>

@push('scripts')
<script>
  (function() {
    document.querySelectorAll('.star-rating').forEach(function(container) {
      const ratingInput = container.querySelector('.rating-value');
      const stars = container.querySelectorAll('.star-btn');
      let currentRating = 0;

      function highlight(rating) {
        stars.forEach(function(star, index) {
          if (index < rating) {
            star.classList.remove('text-slate-300', 'dark:text-slate-400');
            star.classList.add('text-amber-400', 'dark:text-amber-300');
          } else {
            star.classList.remove('text-amber-400', 'dark:text-amber-300');
            star.classList.add('text-slate-300', 'dark:text-slate-400');
          }
        });
      }

      stars.forEach(function(star) {
        star.addEventListener('click', function() {
          currentRating = parseInt(this.dataset.star);
          ratingInput.value = currentRating;
          highlight(currentRating);
        });
        star.addEventListener('mouseenter', function() {
          highlight(parseInt(this.dataset.star));
        });
        star.addEventListener('mouseleave', function() {
          highlight(currentRating);
        });
      });
    });
  })();
</script>
@endpush
@endsection
