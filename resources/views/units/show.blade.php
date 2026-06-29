@extends('layouts.app')

@section('title', $unit->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('catalog') }}"
            class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-5">
        <div class="lg:col-span-3 space-y-6">
            @if ($unit->photos && count($unit->photos) > 0)
                <div class="card !p-0 overflow-hidden">
                    <div class="{{ count($unit->photos) > 1 ? 'grid grid-cols-2' : '' }} gap-0.5">
                        @php $photoUrls = array_map(fn($p) => asset('storage/'.$p), $unit->photos ?? []); @endphp
                        @foreach ($unit->photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}" alt="{{ $unit->name }}" loading="lazy"
                                class="w-full h-64 object-cover cursor-pointer" onclick="openLightbox(this.src, {{ json_encode($photoUrls) }})">
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="badge-info">{{ $unit->category->name }}</span>
                            <span
                                class="badge-{{ $unit->status === 'ready' ? 'success' : ($unit->status === 'maintenance' ? 'danger' : 'warning') }}">{{ ucfirst($unit->status) }}</span>
                        </div>
                        <h1 class="mt-3 text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $unit->name }}</h1>
                        <div class="mt-1 flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            <span class="text-sm text-slate-500 dark:text-slate-400 ms-1">{{ number_format($avgRating, 1) }} ({{ $reviews->count() }} ulasan)</span>
                        </div>
                        <p class="mt-1 flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $unit->location ?? 'Lokasi tidak ditentukan' }}
                        </p>
                    </div>
                </div>
                @if ($unit->description)
                    <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $unit->description }}</p>
                @endif
            </div>

            <div class="card">
                <h2 class="section-title">Detail Unit</h2>
                <div class="mt-4 grid gap-4 text-sm sm:grid-cols-2">
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Nomor Asset</div>
                        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $unit->asset_number }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Kategori</div>
                        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $unit->category->name }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Status</div>
                        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100 capitalize">{{ $unit->status }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3 dark:bg-slate-700/50">
                        <div class="text-xs text-slate-500 dark:text-slate-400">Lokasi</div>
                        <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $unit->location ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2 class="section-title">Ulasan</h2>
                <div class="mt-4">
                    @if ($reviews->isEmpty())
                        <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada ulasan untuk unit ini.</p>
                    @else
                        <div class="mb-4 flex items-center gap-2">
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ number_format($avgRating, 1) }} ({{ $reviews->count() }} ulasan)</span>
                        </div>
                        <div class="space-y-4">
                            @foreach ($reviews as $review)
                                <div class="rounded-lg border border-slate-100 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">{{ substr($review->user->name, 0, 1) }}</div>
                                            <div>
                                                <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $review->user->name }}</div>
                                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $review->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if ($review->review)
                                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $review->review }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">Rp
                        {{ number_format($unit->price_per_day, 0, ',', '.') }}</span>
                    <span class="text-sm text-slate-400 dark:text-slate-500">/hari</span>
                </div>
                @if ($unit->deposit_amount > 0)
                    <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">Deposit: <span class="font-medium">Rp
                            {{ number_format($unit->deposit_amount, 0, ',', '.') }}</span></div>
                @endif
                <hr class="my-4 border-slate-100 dark:border-slate-700">
                <div class="space-y-2 text-sm">
                    @if ($unit->price_per_week)
                        <div class="flex justify-between"><span>Mingguan</span><span class="font-medium">Rp
                                {{ number_format($unit->price_per_week, 0, ',', '.') }}</span></div>
                    @endif
                    @if ($unit->price_per_month)
                        <div class="flex justify-between"><span>Bulanan</span><span class="font-medium">Rp
                                {{ number_format($unit->price_per_month, 0, ',', '.') }}</span></div>
                    @endif
                    @if ($unit->weekend_price)
                        <div class="flex justify-between"><span>Weekend</span><span class="font-medium">Rp
                                {{ number_format($unit->weekend_price, 0, ',', '.') }}</span></div>
                    @endif
                </div>
                <hr class="my-4 border-slate-100 dark:border-slate-700">
                <a href="{{ route('bookings.create', $unit) }}" class="btn-primary w-full">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Booking Sekarang
                </a>
            </div>

        </div>
    </div>

    @if (isset($similarUnits) && $similarUnits->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100">Unit Serupa</h2>
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($similarUnits as $su)
                    <a href="{{ route('units.show', $su) }}" class="group card-hover flex flex-col !no-underline">
                        @if ($su->photos && count($su->photos) > 0)
                            <div class="-m-6 mb-4 overflow-hidden rounded-t-xl">
                                <img src="{{ asset('storage/' . $su->photos[0]) }}" alt="{{ $su->name }}" loading="lazy"
                                    class="h-36 w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                        @endif
                        <span class="badge-info mb-2 self-start">{{ $su->category->name }}</span>
                        <h3 class="text-sm font-semibold text-slate-800 group-hover:text-indigo-600 transition-colors dark:text-slate-100 dark:group-hover:text-indigo-400">{{ $su->name }}</h3>
                        <div class="mt-1 flex items-center gap-1 text-xs text-amber-400">
                            @php $savg = round($su->reviews_avg_rating ?? 0); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-3 w-3 {{ $i <= $savg ? 'text-amber-400' : 'text-slate-200 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                            <span class="text-slate-400 dark:text-slate-500">{{ $su->reviews_avg_rating ? number_format($su->reviews_avg_rating, 1) : '0.0' }}</span>
                        </div>
                        <div class="mt-auto pt-3 flex items-baseline gap-1">
                            <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($su->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500">/hari</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endsection
