@props([
    'items' => [],
    'empty' => 'Sem anúncios recentes.',
    'maxPreview' => 100,
])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    @forelse ($items as $a)
        @php
            $title = $a['title'] ?? '—';
            $desc = strip_tags($a['description'] ?? '');
            $preview = \Illuminate\Support\Str::of($desc)->squish()->limit($maxPreview, '…');
            $publishedAt = $a['publishedAt'] ?? null;
            $courseName = $a['courseName'] ?? null;
        @endphp

        <details class="group border border-slate-800 bg-slate-900/50 rounded-lg">
            <summary class="flex justify-between items-start p-3 cursor-pointer list-none">
                <div class="min-w-0">
                    <h3 class="text-sm font-medium text-slate-100 truncate">{{ $title }}</h3>
                    <p class="text-xs text-slate-500 mt-1 truncate">{{ $courseName }} • {{ $publishedAt }}</p>
                    <p class="text-xs text-slate-400 group-open:hidden mt-1">{{ $preview }}</p>
                </div>
                <svg class="h-4 w-4 mt-1 shrink-0 transition-transform group-open:rotate-180 text-slate-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </summary>

            <div class="p-3 pt-0 text-sm text-slate-300 space-y-2">
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
