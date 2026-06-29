@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 px-6 py-20 sm:px-12 sm:py-28">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -right-32 -top-32 h-96 w-96 animate-pulse rounded-full bg-white/5"></div>
        <div class="absolute -bottom-32 -left-32 h-80 w-80 animate-pulse rounded-full bg-white/5" style="animation-delay: 1s"></div>
        <div class="absolute left-1/4 top-1/4 h-48 w-48 rounded-full bg-white/[0.03] blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 h-64 w-64 rounded-full bg-white/[0.02] blur-3xl"></div>
        <svg class="absolute left-0 top-0 h-full w-full opacity-[0.03]" viewBox="0 0 1000 800" preserveAspectRatio="none">
            <defs>
                <pattern id="hero-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hero-grid)"/>
        </svg>
    </div>
    <div class="relative mx-auto max-w-4xl text-center">
        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-sm text-indigo-100 backdrop-blur-sm">
            <span class="flex h-2 w-2 rounded-full bg-emerald-400"></span>
            Platform Rental Terpercaya
        </div>
        <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
            Sewa Perlengkapan
            <span class="text-indigo-200">Mudah & Cepat</span>
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg text-indigo-100/80">
            Temukan berbagai unit rental berkualitas untuk kebutuhan Anda. Booking, bayar, dan nikmati — semua dalam satu platform tanpa ribet.
        </p>
        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a href="{{ route('catalog') }}"
                class="group inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-700 shadow-lg shadow-indigo-900/20 transition-all hover:bg-indigo-50 hover:shadow-xl">
                Jelajahi Katalog
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('register') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-8 py-3.5 text-base font-medium text-white transition-all hover:bg-white/10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Daftar Sekarang
            </a>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="mt-10">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-slate-800">
            <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-indigo-50 opacity-50 transition-all group-hover:scale-150 dark:bg-indigo-900/20"></div>
            <div class="relative text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="mt-3 text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $units->count() }}+</div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Unit Tersedia</div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-slate-800">
            <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-emerald-50 opacity-50 transition-all group-hover:scale-150 dark:bg-emerald-900/20"></div>
            <div class="relative text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="mt-3 text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $categories->count() }}</div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Kategori</div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-slate-800">
            <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-amber-50 opacity-50 transition-all group-hover:scale-150 dark:bg-amber-900/20"></div>
            <div class="relative text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="mt-3 text-3xl font-bold text-amber-600 dark:text-amber-400">100%</div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Aman & Terpercaya</div>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm transition-all hover:shadow-md dark:bg-slate-800">
            <div class="absolute right-0 top-0 h-20 w-20 translate-x-6 -translate-y-6 rounded-full bg-purple-50 opacity-50 transition-all group-hover:scale-150 dark:bg-purple-900/20"></div>
            <div class="relative text-center">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="mt-3 text-3xl font-bold text-purple-600 dark:text-purple-400">24/7</div>
                <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">Layanan</div>
            </div>
        </div>
    </div>
</section>

{{-- Kategori --}}
@if ($categories->isNotEmpty())
    <section class="mt-16">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 sm:text-3xl">Kategori Populer</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Temukan unit sesuai kebutuhan Anda</p>
        </div>
        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @foreach ($categories as $cat)
                <a href="{{ route('catalog') }}?category={{ $cat->slug }}"
                    class="group relative flex flex-col items-center gap-3 overflow-hidden rounded-2xl border border-slate-100 bg-white p-6 text-center shadow-sm transition-all hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-indigo-700">
                    <div class="absolute -inset-x-4 -top-8 h-16 rounded-full bg-indigo-50 opacity-0 transition-all duration-300 group-hover:opacity-100 dark:bg-indigo-900/20"></div>
                    <div class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 text-indigo-600 transition-all group-hover:scale-110 group-hover:from-indigo-100 group-hover:to-indigo-200 dark:from-indigo-900/40 dark:to-indigo-900/60 dark:text-indigo-400 dark:group-hover:from-indigo-800/60 dark:group-hover:to-indigo-800/80">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="relative text-sm font-medium text-slate-700 transition-colors group-hover:text-indigo-700 dark:text-slate-200 dark:group-hover:text-indigo-300">{{ $cat->name }}</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

{{-- Kenapa Memilih --}}
<section class="mt-16">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 sm:text-3xl">Kenapa Memilih {{ config('app.name') }}?</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kemudahan dalam setiap langkah penyewaan</p>
    </div>
    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-indigo-50 transition-all group-hover:scale-150 dark:bg-indigo-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Mudah Dicari</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Cari unit yang Anda butuhkan dengan filter kategori dan bandingkan harga dengan mudah.</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-50 transition-all group-hover:scale-150 dark:bg-emerald-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Pembayaran Aman</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Transaksi diproses melalui Midtrans, payment gateway terpercaya di Indonesia.</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-50 transition-all group-hover:scale-150 dark:bg-amber-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Proses Cepat</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Booking, bayar, dan dapatkan konfirmasi dalam hitungan menit. Tanpa proses yang berbelit.</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-purple-50 transition-all group-hover:scale-150 dark:bg-purple-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Garansi Aman</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Setiap transaksi dilindungi. Data pribadi Anda aman dan tidak akan disalahgunakan.</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-sky-50 transition-all group-hover:scale-150 dark:bg-sky-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600 dark:bg-sky-900/40 dark:text-sky-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Support 24/7</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Tim support kami siap membantu Anda kapan saja jika ada kendala atau pertanyaan.</p>
            </div>
        </div>
        <div class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white p-8 shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-rose-50 transition-all group-hover:scale-150 dark:bg-rose-900/30"></div>
            <div class="relative">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-900/40 dark:text-rose-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Mudah & Nyaman</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Antarmuka yang sederhana dan intuitif, memudahkan siapa saja untuk melakukan penyewaan.</p>
            </div>
        </div>
    </div>
</section>

{{-- How It Works --}}
<section class="mt-16 rounded-2xl bg-slate-50 px-6 py-12 sm:px-12 sm:py-16 dark:bg-slate-700/50">
    <div class="text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 sm:text-3xl">Bagaimana Cara Kerjanya?</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Hanya 3 langkah mudah untuk menyewa unit</p>
    </div>
    <div class="relative mt-10 grid gap-8 sm:grid-cols-3">
        <div class="relative text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100 text-2xl font-bold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">1</div>
            <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Cari Unit</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Jelajahi katalog, filter sesuai kebutuhan, dan pilih unit yang cocok untuk Anda.</p>
        </div>
        <div class="relative text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">2</div>
            <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Booking & Bayar</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih tanggal sewa, lakukan pembayaran dengan berbagai metode yang tersedia.</p>
        </div>
        <div class="relative text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-2xl font-bold text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">3</div>
            <h3 class="mt-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Nikmati</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Unit siap digunakan. Kami akan mengingatkan Anda saat masa sewa akan berakhir.</p>
        </div>
    </div>
</section>

{{-- Featured Units --}}
@if ($units->isNotEmpty())
    <section class="mt-16">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-100 sm:text-3xl">Unit Terbaru</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Unit yang paling baru tersedia untuk Anda</p>
            </div>
            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                Lihat Semua
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($units as $unit)
                <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-lg dark:border-slate-700 dark:bg-slate-800">
                    <a href="{{ route('units.show', $unit) }}" class="overflow-hidden">
                        @if ($unit->photos && count($unit->photos) > 0)
                            <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->name }}"
                                class="h-44 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-44 items-center justify-center bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">
                                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </a>
                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex items-center gap-2">
                            <span class="badge-info">{{ $unit->category->name }}</span>
                            <span class="badge-{{ $unit->status === 'ready' ? 'success' : 'warning' }}">{{ ucfirst($unit->status) }}</span>
                        </div>
                        <a href="{{ route('units.show', $unit) }}">
                            <h3 class="mt-3 text-base font-semibold text-slate-800 transition-colors group-hover:text-indigo-600 dark:text-slate-100 dark:group-hover:text-indigo-400">{{ $unit->name }}</h3>
                            <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $unit->location ?? '-' }}</p>
                        </a>
                        <div class="mt-auto pt-4">
                            <div class="flex items-baseline gap-1">
                                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                                <span class="text-xs text-slate-400 dark:text-slate-500">/hari</span>
                            </div>
                            <a href="{{ route('units.show', $unit) }}" class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-indigo-200 px-4 py-2.5 text-sm font-medium text-indigo-700 transition-all hover:bg-indigo-50 dark:border-indigo-700 dark:text-indigo-300 dark:hover:bg-indigo-900/40">
                                Lihat Detail
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

{{-- CTA Final --}}
<section class="mt-16 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 px-6 py-16 text-center sm:px-12 sm:py-20">
    <div class="relative">
        <div class="absolute left-1/2 top-0 h-32 w-32 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/5 blur-2xl"></div>
        <h2 class="text-3xl font-bold text-white sm:text-4xl">Siap Memulai?</h2>
        <p class="mx-auto mt-3 max-w-lg text-indigo-100">Daftar sekarang dan nikmati kemudahan menyewa unit kapan pun Anda butuhkan.</p>
        <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a href="{{ route('register') }}"
                class="group inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-base font-semibold text-indigo-700 shadow-lg shadow-indigo-900/20 transition-all hover:bg-indigo-50 hover:shadow-xl">
                Daftar Gratis
                <svg class="h-5 w-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('catalog') }}"
                class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-8 py-3.5 text-base font-medium text-white transition-all hover:bg-white/10">
                Lihat Katalog
            </a>
        </div>
    </div>
</section>
@endsection
