@extends('layouts.admin')
@section('title', 'Data Customer')
@section('page_title', 'Customer')
@section('page_subtitle', 'Semua data customer terdaftar')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $customers->total() }} customer</div>
            </div>
        </div>
        <form method="get" action="{{ route('admin.customers') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari customer..."
                class="input-field w-48 sm:w-56">
            <button type="submit" class="btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="card overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-[700px] w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-6 py-4 whitespace-nowrap">Nama</th>
                        <th class="px-6 py-4 whitespace-nowrap">Email</th>
                        <th class="px-6 py-4 whitespace-nowrap">No. WhatsApp</th>
                        <th class="px-6 py-4 whitespace-nowrap">Total Booking</th>
                        <th class="px-6 py-4 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $c)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                        {{ substr($c->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-800 dark:text-slate-100">{{ $c->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400">{{ $c->email }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $c->phone ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="badge-info">{{ $c->total_booking }}x</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.customers.show', $c) }}"
                                    class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">
                                {{ $search ? 'Customer tidak ditemukan.' : 'Belum ada customer.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $customers->links() }}
    </div>
@endsection
