@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview bisnis rental Anda')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <div class="grid gap-4 sm:gap-6 grid-cols-2 md:grid-cols-3 xl:grid-cols-5">
        <div class="stat-card">
            <div class="icon bg-indigo-100 dark:bg-indigo-900/30"><svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 dark:text-slate-500">Total Unit</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $total_units }}</div>
            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500 dark:text-slate-500">Semua unit dalam sistem</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-emerald-100 dark:bg-emerald-900/30"><svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 dark:text-slate-500">Unit ready</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-emerald-600">{{ $units_ready }}</div>
            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500">Siap disewakan</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-amber-100 dark:bg-amber-900/30"><svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 dark:text-slate-500">Unit Disewa</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-amber-600">{{ $units_on_rent }}</div>
            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500">Sedang dalam masa sewa</div>
        </div>
        <div class="stat-card">
            <div class="icon bg-blue-100 dark:bg-blue-900/30"><svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 dark:text-slate-500">Booking Hari Ini</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-blue-600">{{ $bookings_today }}</div>
            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500">Booking masuk hari ini</div>
        </div>
        <div class="stat-card col-span-2 md:col-span-1">
            <div class="icon bg-purple-100 dark:bg-purple-900/30"><svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg></div>
            <div class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 dark:text-slate-500">Pendapatan</div>
            <div class="mt-1 text-xl sm:text-3xl font-bold text-purple-600">Rp {{ number_format($revenue, 0, ',', '.') }}
            </div>
            <div class="mt-1 text-xs text-slate-400 dark:text-slate-500">Total pendapatan</div>
        </div>
    </div>

    <div class="mt-6 card">
      <h2 class="section-title">Pendapatan Bulanan ({{ now()->year }})</h2>
      <div class="mt-4">
        <canvas id="revenueChart" height="100"></canvas>
      </div>
    </div>

    <div class="mt-6 sm:mt-8">
        <div class="card min-w-0">
            <div class="flex items-center justify-between">
                <h2 class="section-title">Booking Terbaru</h2>
                <a href="{{ route('admin.bookings.index') }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Lihat Semua</a>
            </div>
            <div class="mt-4 space-y-3 md:hidden">
                @forelse ($bookings as $b)
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-700/50">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $b->unit?->name }}</div>
                                <div class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400 dark:text-slate-500">{{ $b->booking_number }} ·
                                    {{ $b->user?->name }}</div>
                            </div>
                            <span
                                class="badge-{{ $b->status === 'confirmed' || $b->status === 'active' ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'default')) }} shrink-0">{{ $b->status_label }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between gap-3">
                            <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Rp
                                {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                            <a href="{{ route('admin.bookings.show', $b) }}"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada booking</div>
                @endforelse
            </div>
            <div class="mt-4 hidden overflow-x-auto md:block">
                <table class="min-w-[500px] w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-medium uppercase tracking-wider text-slate-400 dark:text-slate-500 dark:border-slate-700 dark:text-slate-500">
                            <th class="pb-3 pr-4 whitespace-nowrap">No. Booking</th>
                            <th class="pb-3 pr-4 whitespace-nowrap">Unit</th>
                            <th class="pb-3 pr-4 whitespace-nowrap">Customer</th>
                            <th class="pb-3 pr-4 whitespace-nowrap">Status</th>
                            <th class="pb-3 pr-4 whitespace-nowrap">Total</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $b)
                            <tr class="border-b border-slate-50 dark:border-slate-700">
                                <td class="py-3 pr-4 font-medium whitespace-nowrap text-slate-800 dark:text-slate-100">{{ $b->booking_number }}
                                </td>
                                <td class="py-3 pr-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $b->unit?->name }}</td>
                                <td class="py-3 pr-4 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $b->user?->name }}</td>
                                <td class="py-3 pr-4 whitespace-nowrap">
                                    <span
                                        class="badge-{{ $b->status === 'confirmed' || $b->status === 'active' ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'default')) }}">
                                        {{ $b->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4 font-medium whitespace-nowrap">Rp
                                    {{ number_format($b->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.bookings.show', $b) }}"
                                        class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada booking</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('revenueChart');
  if (!ctx) return;
  const monthlyRevenue = @json($monthlyRevenue);
  const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const labels = months;
  const data = months.map((_, i) => (monthlyRevenue[i + 1] || 0));
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Pendapatan',
        data: data,
        backgroundColor: 'rgba(99, 102, 241, 0.5)',
        borderColor: 'rgb(99, 102, 241)',
        borderWidth: 2,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
          }
        }
      }
    }
  });
});
</script>
@endpush
@endsection
