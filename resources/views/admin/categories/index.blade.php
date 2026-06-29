@extends('layouts.admin')
@section('title', 'Kategori')
@section('page_title', 'Kategori')
@section('page_subtitle', 'Kelola kategori unit')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Total {{ $categories->total() }} kategori</div>
            </div>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
            <form method="get" action="{{ route('admin.categories.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kategori..."
                    class="input-field w-48 sm:w-56">
                <button type="submit" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
            <button type="button" onclick="openCategoryModal()" class="btn-primary">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah Kategori
            </button>
        </div>
    </div>

    <div class="card overflow-hidden !p-0">
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-sm">
                <thead>
                    <tr
                        class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-400">
                        <th class="px-6 py-4 whitespace-nowrap">Nama</th>
                        <th class="px-6 py-4 whitespace-nowrap">Slug</th>
                        <th class="px-6 py-4 whitespace-nowrap">Deskripsi</th>
                        <th class="px-6 py-4 whitespace-nowrap">Unit Count</th>
                        <th class="px-6 py-4 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-b border-slate-50 transition-colors hover:bg-slate-50/50 dark:border-slate-700 dark:hover:bg-slate-700/30">
                            <td class="px-6 py-4 font-medium text-slate-800 dark:text-slate-100">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $category->description ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="badge-default">{{ $category->units_count }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        onclick="openCategoryModal({{ $category->id }}, '{{ e($category->name) }}', '{{ e($category->description ?? '') }}', '{{ e($category->icon ?? '') }}')"
                                        class="rounded-lg px-3 py-1.5 text-sm font-medium text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/40">Edit</button>
                                    <form method="post" action="{{ route('admin.categories.destroy', $category) }}"
                                        onsubmit="return confirm('Hapus kategori ini? Semua unit di kategori ini akan kehilangan kategori.')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/40">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada kategori</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>

    {{-- Category Modal --}}
    <div id="category-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl dark:bg-slate-800">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-700">
                <h3 id="category-modal-title" class="text-lg font-semibold text-slate-800 dark:text-slate-100">Tambah Kategori</h3>
                <button type="button" onclick="closeCategoryModal()" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-700 dark:hover:text-slate-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="category-form" method="post" action="{{ route('admin.categories.store') }}" class="space-y-5 px-6 py-5">
                @csrf
                <input type="hidden" name="_method" id="category-method" value="">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Nama Kategori</label>
                    <input type="text" name="name" id="category-name" class="mt-1.5 input-field" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Deskripsi</label>
                    <textarea name="description" id="category-description" rows="3" class="mt-1.5 input-field">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">Icon (opsional)</label>
                    <input type="text" name="icon" id="category-icon" class="mt-1.5 input-field" value="{{ old('icon') }}" placeholder="Nama icon">
                    <p class="mt-1 text-xs text-slate-400">Nama icon untuk tampilan di halaman publik.</p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary" id="category-submit-btn">Simpan</button>
                    <button type="button" onclick="closeCategoryModal()" class="btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCategoryModal(id, name, description, icon) {
        const modal = document.getElementById('category-modal');
        const title = document.getElementById('category-modal-title');
        const form = document.getElementById('category-form');
        const method = document.getElementById('category-method');
        const submitBtn = document.getElementById('category-submit-btn');

        if (id) {
            title.textContent = 'Edit Kategori';
            form.action = '{{ route('admin.categories.index') }}/' + id;
            method.value = 'PUT';
            submitBtn.textContent = 'Simpan Perubahan';
            document.getElementById('category-name').value = name || '';
            document.getElementById('category-description').value = description || '';
            document.getElementById('category-icon').value = icon || '';
        } else {
            title.textContent = 'Tambah Kategori';
            form.action = '{{ route('admin.categories.store') }}';
            method.value = '';
            submitBtn.textContent = 'Simpan';
            document.getElementById('category-name').value = '';
            document.getElementById('category-description').value = '';
            document.getElementById('category-icon').value = '';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCategoryModal() {
        document.getElementById('category-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
