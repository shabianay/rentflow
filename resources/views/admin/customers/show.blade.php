@extends('layouts.admin')
@section('title', 'Detail Customer')
@section('page_title', 'Detail Customer')
@section('page_subtitle', $user->name)

@section('content')
<div class="space-y-6">
    <div class="mb-6">
        <a href="{{ route('admin.customers') }}" class="btn-secondary">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- Full-width Profile Header --}}
    <div class="card">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-5">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-xl font-bold text-white shadow-md">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    @if ($user->phone)
                        <p class="text-sm text-slate-400 dark:text-slate-500">{{ $user->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-6 divide-x divide-slate-200 dark:divide-slate-600">
                <div class="text-center px-4">
                    <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $bookings->total() }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Total Booking</div>
                </div>
                <div class="text-center px-4">
                    <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400">Rp
                        {{ $totalSpent > 999999 ? number_format($totalSpent / 1000000, 1, ',', '.') . 'jt' : number_format($totalSpent, 0, ',', '.') }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Total Pengeluaran</div>
                </div>
                <div class="text-center px-4">
                    <div class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $user->created_at->format('d M Y') }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Bergabung</div>
                </div>
            </div>
            <button type="button" onclick="openCustomerEditModal()" class="btn-secondary">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <form method="post" action="{{ route('admin.customers.toggle', $user) }}" class="inline" onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                @csrf @method('PATCH')
                <button type="submit" class="{{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        @if ($user->is_active)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @endif
                    </svg>
                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Informasi Pribadi --}}
    <div class="card">
        <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="section-title">Informasi Pribadi</h3>
        </div>
        <div class="grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-slate-100 p-3 dark:border-slate-700">
                <div class="text-xs text-slate-500 dark:text-slate-400">Tempat Lahir</div>
                <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $user->place_of_birth ?? '-' }}</div>
            </div>
            <div class="rounded-lg border border-slate-100 p-3 dark:border-slate-700">
                <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal Lahir</div>
                <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $user->date_of_birth?->format('d M Y') ?? '-' }}</div>
            </div>
            <div class="rounded-lg border border-slate-100 p-3 dark:border-slate-700">
                <div class="text-xs text-slate-500 dark:text-slate-400">No. WhatsApp</div>
                <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $user->phone ?? '-' }}</div>
            </div>
            <div class="rounded-lg border border-slate-100 p-3 dark:border-slate-700">
                <div class="text-xs text-slate-500 dark:text-slate-400">No. KTP</div>
                <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $user->id_card_number ?? '-' }}</div>
            </div>
            <div class="rounded-lg border border-slate-100 p-3 sm:col-span-2 lg:col-span-4 dark:border-slate-700">
                <div class="text-xs text-slate-500 dark:text-slate-400">Alamat</div>
                <div class="mt-0.5 font-medium text-slate-800 dark:text-slate-100">{{ $user->address ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Riwayat Booking --}}
    <div class="card">
        <div class="flex items-center gap-2 mb-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="section-title">Riwayat Booking</h3>
        </div>
        @if ($bookings->isEmpty())
            <p class="text-sm text-slate-400 dark:text-slate-500">Belum ada booking.</p>
        @else
            <div class="space-y-3">
                @foreach ($bookings as $b)
                    <div class="flex items-center justify-between rounded-lg border border-slate-100 p-3 text-sm transition-colors hover:border-indigo-100 hover:bg-indigo-50/30 dark:border-slate-700 dark:hover:border-indigo-700 dark:hover:bg-indigo-900/20">
                        <div class="flex items-center gap-4">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <div class="font-medium text-slate-800 dark:text-slate-100">{{ $b->units_list }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $b->start_date->format('d M Y') }} — {{ $b->end_date->format('d M Y') }}
                                    ({{ $b->total_days }} hari)
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-medium text-slate-800 dark:text-slate-100">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</div>
                            <span class="badge-{{ $b->status === 'completed' ? 'success' : ($b->status === 'pending' ? 'warning' : ($b->status === 'cancelled' ? 'danger' : 'info')) }} text-xs">
                                {{ $b->status_label }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
{{-- Customer Edit Modal --}}
<div id="customer-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl dark:bg-slate-800">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Edit Customer</h3>
            <button type="button" onclick="closeCustomerModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="post" action="{{ route('admin.customers.update', $user) }}" class="space-y-5 px-6 py-5">
            @csrf @method('PUT')
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nama Lengkap</label>
                    <input type="text" name="name" class="mt-1.5 input-field" value="{{ old('name', $user->name) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Email</label>
                    <input type="email" name="email" class="mt-1.5 input-field" value="{{ old('email', $user->email) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">No. WhatsApp</label>
                    <input type="text" name="phone" class="mt-1.5 input-field" value="{{ old('phone', $user->phone) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">No. KTP</label>
                    <input type="text" name="id_card_number" class="mt-1.5 input-field" value="{{ old('id_card_number', $user->id_card_number) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" class="mt-1.5 input-field" value="{{ old('place_of_birth', $user->place_of_birth) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal Lahir</label>
                    <input type="text" name="date_of_birth" id="edit-date_of_birth" class="mt-1.5 input-field" placeholder="Pilih tanggal lahir" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" readonly>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Alamat</label>
                    <textarea name="address" rows="3" class="mt-1.5 input-field">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <button type="button" onclick="closeCustomerModal()" class="btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function ensureFlatpickr(callback) {
        if (typeof flatpickr !== 'undefined') { callback(); return; }
        var s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js';
        s.onload = callback;
        document.head.appendChild(s);
    }

    function openCustomerEditModal() {
        document.getElementById('customer-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(function () {
            ensureFlatpickr(function() {
                flatpickr('#edit-date_of_birth', {
                    maxDate: 'today',
                    dateFormat: 'Y-m-d',
                });
            });
        }, 100);
    }

    function closeCustomerModal() {
        document.getElementById('customer-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
