@props([
    'items' => [],
    'empty' => 'Sem anúncios recentes.',
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @forelse ($items as $a)
        @php
            $title = $a['title'] ?? '—';
            $desc = strip_tags($a['description'] ?? '');
            $publishedAt = $a['publishedAt'] ?? null;
            $courseName = $a['courseName'] ?? null;
        @endphp

        <details class="group border border-slate-700 bg-slate-800 rounded-xl overflow-hidden">
            <summary class="flex justify-between items-start p-3 cursor-pointer list-none hover:bg-slate-700/30 transition-colors">
                <div class="min-w-0">
                    <h3 class="text-sm font-medium text-slate-100 truncate">{{ $title }}</h3>
                    <div class="text-xs text-slate-500 mt-1 flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        @if ($courseName)
                            <span class="truncate">{{ $courseName }}</span>
                        @endif
                        @if ($courseName && $publishedAt)
                            <span class="hidden sm:inline text-slate-600">•</span>
                        @endif
                        @if ($publishedAt)
                            <span class="text-slate-400 sm:text-slate-500">{{ $publishedAt }}</span>
                        @endif
                    </div>
                </div>
                <svg class="h-4 w-4 mt-1 shrink-0 transition-transform group-open:rotate-180 text-slate-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </summary>

            <div class="p-3 pt-3 text-sm text-slate-300 space-y-2 border-t border-slate-700">
                <div class="prose prose-invert prose-sm max-w-none">{!! $a['description'] ?? '' !!}</div>
                @if (!empty($a['link']))
                    <a href="{{ $a['link'] }}" target="_blank" rel="noopener"
                        class="text-xs text-sky-400 hover:underline inline-flex items-center gap-1">
                        <span>Ver no Fénix</span>
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                    </a>
                @endif
            </div>
        </details>
    @empty
        <div class="text-sm text-slate-400">{{ $empty }}</div>
    @endforelse
</div>
