@extends('layouts.admin')
@section('title', 'Kelola Review')
@section('page_title', 'Review')
@section('page_subtitle', 'Kelola ulasan customer')

@section('content')
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-400 dark:border-slate-700 dark:bg-slate-700/50 dark:text-slate-500">
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3">Ulasan</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                    <tr class="border-b border-slate-50 dark:border-slate-700">
                        <td class="px-4 py-3 whitespace-nowrap text-slate-800 dark:text-slate-100">{{ $review->user?->name ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-slate-600 dark:text-slate-300">{{ $review->unit?->name ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3 max-w-xs text-slate-600 dark:text-slate-300">
                            <div class="truncate">{{ $review->review ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-slate-500 dark:text-slate-400">{{ $review->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <form method="post" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Hapus review ini?')">
                                @csrf @method('delete')
                                <button type="submit" class="btn-ghost p-1.5 text-xs text-red-500 hover:text-red-700">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada review.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $reviews->links() }}
</div>
@endsection
