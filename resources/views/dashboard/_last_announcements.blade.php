{{-- root becomes a flex column that can shrink --}}
<div id="last-announcements" class="flex flex-col flex-1 min-h-0">
  @if (empty($announcements))
    <div class="text-slate-400 text-sm">Sem anúncios recentes.</div>
  @else
    <div class="flex-1 min-h-0 pr-2 overflow-y-auto flex flex-col gap-2
                  scrollbar-thin scrollbar-track-transparent
                  scrollbar-thumb-slate-400/70 hover:scrollbar-thumb-slate-500
                  scrollbar-thumb-rounded-full">
      @foreach ($announcements as $a)
        @php
          $preview = \Illuminate\Support\Str::of(strip_tags($a['description'] ?? ''))
            ->squish()->limit(280, '…');
        @endphp

        <details class="group rounded-xl border border-slate-800 bg-slate-900/40
                            [&>summary::-webkit-details-marker]:hidden">
          <summary class="cursor-pointer select-none px-3 py-2 flex items-start justify-between gap-3">
            <div class="min-w-0">
              <div class="font-medium truncate">{{ $a['courseName'] }}</div>
              <div class="text-sm text-slate-400 truncate">
                {{ $a['publishedAt'] }} — {{ $a['title'] }}
              </div>
              <p class="text-sm text-slate-500 mt-1 line-clamp-2 group-open:hidden">
                {{ $preview }}
              </p>
            </div>

            <!-- centered +/− -->
            <span class="shrink-0 mt-1 text-slate-300" aria-hidden="true">
              <span class="relative inline-flex items-center justify-center w-6 h-6
                               border border-slate-600 rounded-md">
                <span class="absolute h-0.5 w-3 bg-current left-1/2 top-1/2
                                 -translate-x-1/2 -translate-y-1/2"></span>
                <span class="absolute w-0.5 h-3 bg-current left-1/2 top-1/2
                                 -translate-x-1/2 -translate-y-1/2 transition-transform
                                 group-open:scale-y-0 group-open:opacity-0"></span>
              </span>
              <span class="sr-only">Alternar anúncio</span>
            </span>
          </summary>

          <div class="px-3 pb-3">
            <div class="prose prose-sm max-w-none text-sm text-slate-300">
              {!! $a['description'] !!}
            </div>
          </div>
        </details>
      @endforeach
    </div>
  @endif
</div>
