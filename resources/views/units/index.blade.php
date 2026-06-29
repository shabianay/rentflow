@extends('layouts.admin')
@section('title', 'Kelola Unit')
@section('page_title', 'Unit')
@section('page_subtitle', 'Kelola semua unit rental')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $units->total() }} unit</div>
            </div>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="get" action="{{ route('admin.units.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari unit..."
                    class="input-field w-48 sm:w-56">
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <button type="button" onclick="openUnitModal()" class="btn-primary">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Unit
            </button>
        </div>
    </div>

    <div class="mb-4 flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.units.index') }}" class="rounded-lg px-3 py-1.5 text-sm font-medium {{ !$status ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">Semua</a>
        @foreach (['ready', 'booked', 'on_rent', 'maintenance', 'reserved'] as $s)
            <a href="{{ route('admin.units.index', array_filter(['status' => $s, 'search' => $search])) }}" class="rounded-lg px-3 py-1.5 text-sm font-medium {{ $status === $s ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600' }}">{{ ucfirst($s) }}</a>
        @endforeach
    </div>

    <div class="card overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-4 py-4 whitespace-nowrap"></th>
                        <th class="px-6 py-4 whitespace-nowrap">Unit</th>
                        <th class="px-6 py-4 whitespace-nowrap">Kategori</th>
                        <th class="px-6 py-4 whitespace-nowrap">Harga/hari</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Asset #</th>
                        <th class="px-6 py-4 whitespace-nowrap">Aktif</th>
                        <th class="px-6 py-4 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if ($unit->photos && count($unit->photos) > 0)
                                    <img src="{{ asset('storage/' . $unit->photos[0]) }}" alt="{{ $unit->name }}" loading="lazy"
                                        class="h-10 w-10 rounded-lg border border-slate-200 object-cover cursor-pointer dark:border-slate-600" onclick="openLightbox(this.src)">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800 dark:text-slate-100">{{ $unit->name }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500">{{ $unit->location ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="badge-info">{{ $unit->category->name }}</span></td>
                            <td class="px-6 py-4 font-medium">Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge-{{ $unit->status === 'ready' ? 'success' : ($unit->status === 'maintenance' ? 'danger' : ($unit->status === 'on_rent' ? 'warning' : 'info')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $unit->asset_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($unit->is_active)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        onclick='openUnitModal({{ $unit->id }}, @json($unit))'
                                        class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40">Edit</button>
                                    <form method="post" action="{{ route('admin.units.destroy', $unit) }}"
                                        onsubmit="return confirm('Hapus unit ini?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/40">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada unit</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $units->links() }}
    </div>

    {{-- Unit Modal --}}
    <div id="unit-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl dark:bg-slate-800">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-slate-100 bg-white px-6 py-4 dark:border-slate-700 dark:bg-slate-800">
                <h3 id="unit-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Tambah Unit</h3>
                <button type="button" onclick="closeUnitModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="unit-form" method="post" enctype="multipart/form-data" class="space-y-6 px-6 py-5">
                @csrf
                <input type="hidden" name="_method" id="unit-method" value="">
                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nama Unit</label>
                        <input type="text" name="name" id="unit-name" class="mt-1.5 input-field" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Kategori</label>
                        <select name="category_id" id="unit-category_id" class="mt-1.5 select-field" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nomor Asset</label>
                        <input type="text" name="asset_number" id="unit-asset_number" class="mt-1.5 input-field" placeholder="CONTOH-001" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Lokasi</label>
                        <input type="text" name="location" id="unit-location" class="mt-1.5 input-field" placeholder="Jakarta Selatan">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Harga per Hari</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="price_per_day" id="unit-price_per_day" class="input-field pl-10" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Harga per Minggu</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="price_per_week" id="unit-price_per_week" class="input-field pl-10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Harga per Bulan</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="price_per_month" id="unit-price_per_month" class="input-field pl-10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Harga Weekend</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="weekend_price" id="unit-weekend_price" class="input-field pl-10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Harga Libur</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="holiday_price" id="unit-holiday_price" class="input-field pl-10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Deposit</label>
                        <div class="relative mt-1.5">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400 dark:text-slate-500">Rp</span>
                            <input type="number" name="deposit_amount" id="unit-deposit_amount" class="input-field pl-10" value="0">
                        </div>
                    </div>
                    <div id="unit-status-field" class="hidden sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Status</label>
                        <select name="status" id="unit-status" class="mt-1.5 select-field">
                            @foreach (['ready', 'booked', 'on_rent', 'maintenance', 'reserved'] as $s)
                                <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="unit-photos-existing" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Foto Saat Ini</label>
                    <div id="unit-photos-list" class="mt-2 flex flex-wrap gap-4" data-reorder="true"></div>
                    <input type="hidden" name="photo_order" id="unit-photo_order" value="">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Foto Unit</label>
                    <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="mt-1.5 input-field file:text-sm file:border-0 file:bg-transparent file:font-medium file:text-indigo-600 hover:file:cursor-pointer dark:file:text-indigo-400">
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Format: JPG, PNG, WebP. Maks 2MB per file. Bisa pilih lebih dari 1.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Deskripsi</label>
                    <textarea name="description" id="unit-description" rows="3" class="mt-1.5 input-field"></textarea>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-700/50">
                    <div>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-200">Aktifkan Unit</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Unit siap untuk disewakan</div>
                    </div>
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="is_active" id="unit-is_active" value="1" class="peer sr-only" checked>
                        <div class="peer h-6 w-11 rounded-full bg-slate-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-indigo-600 peer-checked:after:translate-x-full dark:bg-slate-600"></div>
                    </label>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary" id="unit-submit-btn">Simpan Unit</button>
                    <button type="button" onclick="closeUnitModal()" class="btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@push('scripts')
<script>
    function openUnitModal(id, data) {
        const modal = document.getElementById('unit-modal');
        const title = document.getElementById('unit-modal-title');
        const form = document.getElementById('unit-form');
        const method = document.getElementById('unit-method');
        const submitBtn = document.getElementById('unit-submit-btn');
        const statusField = document.getElementById('unit-status-field');

        if (id) {
            title.textContent = 'Edit Unit';
            form.action = '{{ route('admin.units.index') }}/' + id;
            method.value = 'PUT';
            submitBtn.textContent = 'Simpan Perubahan';
            statusField.classList.remove('hidden');
            statusField.classList.add('sm:col-span-2');

            document.getElementById('unit-name').value = data.name || '';
            document.getElementById('unit-category_id').value = data.category_id || '';
            document.getElementById('unit-asset_number').value = data.asset_number || '';
            document.getElementById('unit-location').value = data.location || '';
            document.getElementById('unit-price_per_day').value = data.price_per_day || '';
            document.getElementById('unit-price_per_week').value = data.price_per_week || '';
            document.getElementById('unit-price_per_month').value = data.price_per_month || '';
            document.getElementById('unit-weekend_price').value = data.weekend_price || '';
            document.getElementById('unit-holiday_price').value = data.holiday_price || '';
            document.getElementById('unit-deposit_amount').value = data.deposit_amount ?? '0';
            document.getElementById('unit-status').value = data.status || 'ready';
            document.getElementById('unit-description').value = data.description || '';
            document.getElementById('unit-is_active').checked = data.is_active;

            // Show existing photos
            const photosContainer = document.getElementById('unit-photos-existing');
            const photosList = document.getElementById('unit-photos-list');
            photosList.innerHTML = '';
            if (data.photos && data.photos.length > 0) {
                photosContainer.classList.remove('hidden');
                data.photos.forEach(function(photo) {
                    const div = document.createElement('div');
                    div.className = 'flex flex-col items-center gap-1.5';
                    div.innerHTML =
                        '<span class="drag-handle cursor-grab text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">' +
                        '<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM7 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 8a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM13 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/></svg>' +
                        '</span>' +
                        '<img src="{{ asset('storage') }}/' + photo + '" alt="Foto unit" class="h-20 w-20 rounded-lg border border-slate-200 object-cover dark:border-slate-600">' +
                        '<label class="flex cursor-pointer items-center gap-1 text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">' +
                        '<input type="checkbox" name="delete_photos[]" value="' + photo + '" class="rounded border-slate-300 text-red-500 focus:ring-red-500 dark:border-slate-600 dark:text-red-400"> Hapus' +
                        '</label>';
                    photosList.appendChild(div);
                });
                // Initialize sortable for photo reordering
                if (window.Sortable && document.getElementById('unit-photos-list')) {
                    Sortable.create(document.getElementById('unit-photos-list'), {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'opacity-50',
                        onEnd: function() {
                            updatePhotoOrder();
                        }
                    });
                }
            } else {
                photosContainer.classList.add('hidden');
            }
        } else {
            title.textContent = 'Tambah Unit';
            form.action = '{{ route('admin.units.store') }}';
            method.value = '';
            submitBtn.textContent = 'Simpan Unit';
            statusField.classList.add('hidden');
            statusField.classList.remove('sm:col-span-2');

            document.getElementById('unit-name').value = '';
            document.getElementById('unit-category_id').value = '';
            document.getElementById('unit-asset_number').value = '';
            document.getElementById('unit-location').value = '';
            document.getElementById('unit-price_per_day').value = '';
            document.getElementById('unit-price_per_week').value = '';
            document.getElementById('unit-price_per_month').value = '';
            document.getElementById('unit-weekend_price').value = '';
            document.getElementById('unit-holiday_price').value = '';
            document.getElementById('unit-deposit_amount').value = '0';
            document.getElementById('unit-description').value = '';
            document.getElementById('unit-is_active').checked = true;

            document.getElementById('unit-photos-existing').classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUnitModal() {
        document.getElementById('unit-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function updatePhotoOrder() {
        const list = document.getElementById('unit-photos-list');
        if (!list) return;
        const order = [];
        list.querySelectorAll('input[name="delete_photos[]"]').forEach(function(input) {
            order.push(input.value);
        });
        document.getElementById('unit-photo_order').value = order.join(',');
    }
</script>
@endpush
