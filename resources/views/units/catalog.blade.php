@extends('layouts.app')

@section('title', 'Katalog Unit')

@section('content')
    <div
        class="relative mb-10 overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-8 sm:p-12">
        <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-10 -left-10 h-48 w-48 rounded-full bg-white/5"></div>
        <div class="relative">
            <h1 class="text-3xl font-bold text-white sm:text-4xl">Temukan Unit Rental</h1>
            <p class="mt-2 max-w-2xl text-lg text-indigo-100">Cari unit, pilih tanggal, booking, bayar. Semua otomatis tanpa
                ribet.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="#units"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-sm font-medium text-indigo-700 shadow-sm transition-all hover:bg-indigo-50 dark:bg-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-900/40">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Lihat Unit
                </a>
            </div>
        </div>
    </div>

    <form method="get" action="{{ route('catalog') }}" class="mb-6 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 id="units" class="text-xl font-bold text-slate-800 dark:text-slate-100">Unit Tersedia <span
                    class="text-sm font-normal text-slate-400 dark:text-slate-500">({{ $units->total() }} unit)</span></h2>
            <div class="flex items-center gap-2">
                <select name="sort" onchange="this.form.submit()" class="select-field text-sm w-36">
                    <option value="terbaru" {{ ($sort ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="termurah" {{ ($sort ?? '') === 'termurah' ? 'selected' : '' }}>Termurah</option>
                    <option value="termahal" {{ ($sort ?? '') === 'termahal' ? 'selected' : '' }}>Termahal</option>
                    <option value="terpopuler" {{ ($sort ?? '') === 'terpopuler' ? 'selected' : '' }}>Terpopuler</option>
                </select>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari unit atau lokasi..." class="input-field w-44 sm:w-56 text-sm">
                <button type="submit" class="btn-primary text-sm"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></button>
                @if ($search || $minPrice || $maxPrice || $categorySlug || ($sort && $sort !== 'terbaru'))
                    <a href="{{ route('catalog') }}" class="btn-ghost text-sm">Reset</a>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('catalog') }}"
                class="rounded-lg bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">Semua</a>
            @foreach ($categories as $cat)
                <a href="{{ route('catalog') }}?category={{ $cat->slug }}{{ $search ? '&search='.$search : '' }}{{ $minPrice ? '&min_price='.$minPrice : '' }}{{ $maxPrice ? '&max_price='.$maxPrice : '' }}{{ $sort && $sort !== 'terbaru' ? '&sort='.$sort : '' }}"
                   class="rounded-lg {{ $categorySlug == $cat->slug ? 'bg-indigo-200 dark:bg-indigo-800' : 'bg-slate-100 dark:bg-slate-700' }} px-4 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-600">{{ $cat->name }}</a>
            @endforeach
            <div class="ml-auto flex items-center gap-2">
                <input type="number" name="min_price" value="{{ $minPrice ?? '' }}" placeholder="Min" class="input-field w-20 text-sm" min="0">
                <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                <input type="number" name="max_price" value="{{ $maxPrice ?? '' }}" placeholder="Max" class="input-field w-20 text-sm" min="0">
                <button type="submit" class="btn-secondary text-sm px-3 py-1.5">Filter Harga</button>
            </div>
        </div>
    </form>

    @if ($units->isEmpty())
        <div class="card py-12 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-slate-600 dark:text-slate-300">Belum Ada Unit</h3>
            <p class="mt-1 text-sm text-slate-400 dark:text-slate-500">Belum ada unit tersedia saat ini. Coba lagi nanti.</p>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($units as $unit)
                <div class="group card-hover flex flex-col">
                    @if ($unit->photos && count($unit->photos) > 0)
                        <div class="-m-6 mb-4 overflow-hidden rounded-t-xl cursor-pointer" onclick="var p={{ json_encode($unit->photos ?? []) }};openLightbox(this.querySelector('img').src, p.map(function(x){return '{{ asset('storage') }}/'+x}))">
                            <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->name }}" loading="lazy"
                                class="h-40 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                    @endif
                    <div class="mb-2 flex items-start justify-between">
                        <span class="badge-info">{{ $unit->category->name }}</span>
                        <span class="badge-success">{{ $unit->status }}</span>
                    </div>
                    <div class="mb-1 flex items-center gap-1 text-xs text-amber-400">
                        @php $avg = round($unit->reviews_avg_rating ?? 0); @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="h-3.5 w-3.5 {{ $i <= $avg ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                        <span class="text-slate-400 dark:text-slate-500 ms-1">{{ $unit->reviews_avg_rating ? number_format($unit->reviews_avg_rating, 1) : '0.0' }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors dark:text-slate-100 dark:group-hover:text-indigo-400">
                        {{ $unit->name }}</h3>
                    <p class="mt-1 flex items-center gap-1 text-xs text-slate-400 dark:text-slate-500">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $unit->location ?? '-' }}
                    </p>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp
                            {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                        <span class="text-sm text-slate-400 dark:text-slate-500">/hari</span>
                    </div>
                    <div class="mt-auto pt-4 flex gap-2">
                        <a href="{{ route('units.show', $unit) }}" class="btn-ghost flex-1 text-center text-sm">
                            <svg class="mr-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Detail
                        </a>
                        @auth
                            <form method="post" action="{{ route('cart.add', $unit) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="btn-primary w-full text-sm">
                                    <svg class="mr-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Keranjang
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary flex-1 text-center text-sm">
                                <svg class="mr-1 h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $units->links() }}
        </div>
    @endif
@endsection
