<h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Ulasan</h3>
@if ($reviews->isEmpty())
    <p class="mt-2 text-sm text-slate-400">Belum ada ulasan untuk unit ini.</p>
@else
    <div class="mt-4 space-y-4">
        @foreach ($reviews as $review)
            <div class="rounded-lg border border-slate-100 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $review->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $review->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-0.5">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                </div>
                @if ($review->review)
                    <p class="mt-3 text-sm text-slate-600">{{ $review->review }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
