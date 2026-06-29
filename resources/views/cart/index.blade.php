@extends('layouts.app')

@section('title', 'Keranjang Booking')
@section('page_title', 'Keranjang')

@section('content')
<div class="mx-auto max-w-7xl">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Keranjang Booking</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ count($cart) }} unit dipilih</p>
            </div>
        </div>
        @if (count($cart) > 0)
            <form method="post" action="{{ route('cart.clear') }}">
                @csrf
                <button class="btn-ghost text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Kosongkan</button>
            </form>
        @endif
    </div>

    @if (empty($cart))
        <div class="card py-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-slate-600 dark:text-slate-300">Keranjang Masih Kosong</h3>
            <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">Cari unit yang ingin Anda booking, lalu tambahkan ke keranjang.</p>
            <a href="{{ route('catalog') }}" class="mt-4 btn-primary inline-flex">Cari Unit</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($units as $unit)
                <div class="card flex flex-col sm:flex-row sm:items-center gap-4">
                    @if ($unit->photos && count($unit->photos) > 0)
                        <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->name }}"
                            class="h-20 w-20 rounded-lg object-cover shrink-0">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 shrink-0">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="badge-info">{{ $unit->category->name }}</span>
                                <h3 class="mt-1 text-lg font-semibold text-slate-800 dark:text-slate-100">{{ $unit->name }}</h3>
                            </div>
                            <form method="post" action="{{ route('cart.remove', $unit) }}">
                                @csrf
                                <button class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <div class="mt-2 flex items-baseline gap-1">
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-sm text-slate-400 dark:text-slate-500">/hari</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('bookings.create.multi') }}" class="btn-primary inline-flex items-center gap-2 px-8 py-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Lanjutkan Booking
            </a>
        </div>
    @endif
</div>
@endsection
