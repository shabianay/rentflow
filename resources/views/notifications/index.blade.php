@extends('layouts.admin')
@section('title', 'Notifikasi')
@section('page_title', 'Notifikasi')
@section('page_subtitle', 'Semua notifikasi sistem')
@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Notifikasi</h1>
        <form method="post" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="btn-ghost text-sm">Tandai Sudah Dibaca</button>
        </form>
    </div>
    <div class="space-y-3">
        @forelse ($notifications as $notif)
            <div class="card flex items-start gap-4 {{ !$notif->is_read ? 'border-l-4 border-l-indigo-500' : '' }}">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $notif->title }}</span>
                        @if (!$notif->is_read)
                            <span class="h-2 w-2 rounded-full bg-indigo-500 shrink-0"></span>
                        @endif
                    </div>
                    @if ($notif->message)
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $notif->message }}</p>
                    @endif
                    <div class="mt-2 flex items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                        <span>{{ $notif->created_at->diffForHumans() }}</span>
                        @if ($notif->url)
                            <a href="{{ $notif->url }}" class="font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">Lihat Detail</a>
                        @endif
                    </div>
                </div>
                @if (!$notif->is_read)
                    <form method="post" action="{{ route('admin.notifications.read', $notif) }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">Tutup</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="card py-8 text-center text-sm text-slate-400 dark:text-slate-500">Belum ada notifikasi.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection
