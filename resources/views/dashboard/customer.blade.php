@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-4">
            <div
                class="flex h-12 w-12 sm:h-16 sm:w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-xl sm:text-2xl font-bold text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30">
                {{ substr($user->name, 0, 1) }}</div>
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-slate-100 truncate">Halo,
                    {{ $user->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Selamat datang di dashboard customer RentFlow</p>
            </div>
        </div>
    </div>

    <div class="grid gap-4 sm:gap-6 grid-cols-2 lg:grid-cols-4">
        <div class="stat-card">
            <div class="icon bg-blue-100 dark:bg-blue-900/30"><svg class="h-6 w-6 text-blue-600 dark:text-blue-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Total Booking</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $bookingCount }}</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-emerald-100 dark:bg-emerald-900/30"><svg
                    class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Selesai</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $completedCount }}
            </div>
        </div>
        <div class="stat-card">
            <div class="icon bg-amber-100 dark:bg-amber-900/30"><svg class="h-6 w-6 text-amber-600 dark:text-amber-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Aktif</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $activeBooking ? 1 : 0 }}
            </div>
        </div>
        <div class="stat-card">
            <div class="icon bg-purple-100 dark:bg-purple-900/30"><svg class="h-6 w-6 text-purple-600 dark:text-purple-400"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Total Pengeluaran</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400">Rp
                {{ number_format($totalSpent, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="mt-6 sm:mt-8 grid gap-6 lg:grid-cols-2">
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="section-title">Booking Terbaru</h2>
                <a href="{{ route('bookings.index') }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat
                    Semua</a>
            </div>
            @if ($bookings->isEmpty())
                <div class="mt-4 py-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada booking.</div>
            @else
                <div class="mt-4 space-y-3">
                    @foreach ($bookings as $b)
                        <div
                            class="flex flex-col sm:flex-row sm:items-center justify-between rounded-lg border border-slate-100 dark:border-slate-700 p-4 transition-colors hover:bg-slate-50 dark:hover:bg-slate-700 gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                                        {{ $b->units_list }}</div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500">
                                        {{ $b->start_date->format('d M') }} — {{ $b->end_date->format('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 sm:text-right shrink-0">
                                <span
                                    class="badge-{{ $b->status === 'confirmed' || $b->status === 'active' ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'default')) }}">{{ $b->status_label }}</span>
                                <div
                                    class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                    Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
