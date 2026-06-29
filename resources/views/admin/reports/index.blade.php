@extends('layouts.admin')
@section('title', 'Laporan')
@section('page_title', 'Laporan')
@section('page_subtitle', 'Analisis bisnis & data booking')

@section('content')
    {{-- Filter Periode --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="flex gap-1">
                <a href="{{ route('admin.reports.index') }}?period=today"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $period === 'today' ? 'bg-indigo-600 text-white dark:bg-indigo-500' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Hari Ini</a>
                <a href="{{ route('admin.reports.index') }}?period=week"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $period === 'week' ? 'bg-indigo-600 text-white dark:bg-indigo-500' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Minggu Ini</a>
                <a href="{{ route('admin.reports.index') }}?period=month"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $period === 'month' ? 'bg-indigo-600 text-white dark:bg-indigo-500' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Bulan Ini</a>
                <a href="{{ route('admin.reports.index') }}?period=year"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $period === 'year' ? 'bg-indigo-600 text-white dark:bg-indigo-500' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Tahun Ini</a>
                <a href="{{ route('admin.reports.index') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $period === 'all' ? 'bg-indigo-600 text-white dark:bg-indigo-500' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Semua</a>
            </div>
        </div>
        <a href="{{ route('admin.reports.export') }}?period={{ $period }}" class="btn-secondary">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:gap-6 grid-cols-2 md:grid-cols-4 xl:grid-cols-5">
        <div class="stat-card">
            <div class="icon bg-emerald-100 dark:bg-emerald-900/30"><svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Total Pendapatan</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-blue-100 dark:bg-blue-900/30"><svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Total Booking</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $bookingCount }}</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-indigo-100 dark:bg-indigo-900/30"><svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Selesai</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $paidBookings }}</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-amber-100 dark:bg-amber-900/30"><svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Pending</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingBookings }}</div>
        </div>
        <div class="stat-card col-span-2 md:col-span-1">
            <div class="icon bg-red-100 dark:bg-red-900/30"><svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Dibatalkan</div>
            <div class="mt-1 text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">{{ $cancelledBookings }}</div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Monthly Revenue Chart (Bar) --}}
        <div class="card lg:col-span-2">
            <h2 class="section-title">Pendapatan Bulanan</h2>
            <div class="mt-6 space-y-3">
                @php $maxRevenue = max($monthlyRevenue) ?: 1; @endphp
                @foreach ($months as $i => $label)
                    <div class="flex items-center gap-3">
                        <div class="w-14 text-right text-xs text-slate-500 dark:text-slate-400 shrink-0">{{ $label }}</div>
                        <div class="flex-1 h-6 rounded bg-slate-100 dark:bg-slate-700 overflow-hidden">
                            <div class="h-full rounded bg-indigo-500 dark:bg-indigo-400 transition-all duration-500" style="width: {{ ($monthlyRevenue[$i] / $maxRevenue) * 100 }}%"></div>
                        </div>
                        <div class="w-28 text-right text-xs font-medium text-slate-700 dark:text-slate-200">Rp {{ number_format($monthlyRevenue[$i], 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top Units --}}
        <div class="card">
            <h2 class="section-title">Unit Terpopuler</h2>
            @if ($topUnits->isEmpty())
                <p class="mt-4 text-sm text-slate-400 dark:text-slate-500">Belum ada data.</p>
            @else
                <div class="mt-4 space-y-3">
                    @php $maxRev = $topUnits->max('revenue') ?: 1; @endphp
                    @foreach ($topUnits as $unit)
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-800 dark:text-slate-100 truncate">{{ $unit->name }}</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Rp {{ number_format($unit->revenue, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-2 rounded bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                <div class="h-full rounded bg-indigo-600 dark:bg-indigo-500" style="width: {{ ($unit->revenue / $maxRev) * 100 }}%"></div>
                            </div>
                            <div class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">{{ $unit->completed_bookings }}x booking</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Bookings Table --}}
    <div class="mt-6 card overflow-hidden !p-0">
        <div class="flex items-center justify-between px-6 py-4">
            <h2 class="section-title">Booking Terbaru</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead>
                    <tr class="border-y border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-6 py-4 whitespace-nowrap">No. Booking</th>
                        <th class="px-6 py-4 whitespace-nowrap">Customer</th>
                        <th class="px-6 py-4 whitespace-nowrap">Unit</th>
                        <th class="px-6 py-4 whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBookings as $b)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">{{ $b->booking_number }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $b->user->name }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $b->units_list }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $b->start_date->format('d/m/Y') }} — {{ $b->end_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge-{{ in_array($b->status, ['confirmed','active']) ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'info')) }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
