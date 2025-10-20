@props([
    'items' => [],
    'empty' => 'Sem anúncios recentes.',
    'showCourse' => true,
    'maxPreview' => 220,
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3']) }}>
    @forelse ($items as $a)
        @php
            $title = $a['title'] ?? '—';
            $preview = \Illuminate\Support\Str::of(strip_tags($a['description'] ?? ''))
                ->squish()
                ->limit($maxPreview, '…');
            $course = $a['courseName'] ?? null;
            $author = $a['author'] ?? null;
            $category = $a['category'] ?? null;
            $when = $a['publishedAt'] ?? null;
            $link = $a['link'] ?? null;
        @endphp

        <details
            class="group rounded-xl border border-slate-800 bg-slate-900/40 hover:bg-slate-900/60 transition-shadow focus-within:ring-1 focus-within:ring-slate-700">
            <summary class="flex items-start gap-3 p-4 cursor-pointer list-none">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-slate-100 truncate">{{ $title }}</h3>

                    {{-- preview collapses when open (no JS needed) --}}
                    <p class="mt-1 text-sm text-slate-400 group-open:hidden">
                        {{ $preview }}
                    </p>

                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                        @if ($showCourse && $course)
                            <span class="truncate">{{ $course }}</span>
                        @endif
                        @if ($author)
                            <span class="truncate">• {{ $author }}</span>
                        @endif
                        @if ($when)
                            <time aria-label="Publicado em">{{ $when }}</time>
                        @endif
                        @if ($category)
                            <span
                                class="rounded-full px-2 py-0.5 bg-slate-800 text-slate-300 text-[11px]">{{ $category }}</span>
                        @endif
                    </div>
                </div>

                <span
                    class="shrink-0 mt-1 inline-flex h-6 w-6 items-center justify-center rounded-md ring-1 ring-inset ring-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                    <span class="sr-only">Abrir anúncio</span>
                </span>
            </summary>

            <div class="px-4 pb-4">
                <div class="prose prose-invert prose-sm max-w-none">
                    {!! $a['description'] ?? '' !!}
                </div>

                @if ($link)
                    <div class="mt-3">
                        <a href="{{ $link }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-2 text-xs font-medium hover:underline text-sky-400">
                            <span>Ver no Fénix</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-3.5 w-3.5"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 13v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                                <polyline points="15 3 21 3 21 9" />
                                <line x1="10" y1="14" x2="21" y2="3" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </details>
    @empty
        <div class="text-sm text-slate-400">{{ $empty }}</div>
    @endforelse
</div>
