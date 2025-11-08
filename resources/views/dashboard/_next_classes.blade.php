<div id="next-classes" class="space-y-3 max-h-[60vh] md:max-h-64 xl:max-h-none xl:h-full overflow-y-auto pr-0 md:pr-1
                scrollbar-thin scrollbar-track-transparent
                scrollbar-thumb-slate-400/70 hover:scrollbar-thumb-slate-500
                scrollbar-thumb-rounded-full">
    @if (empty($classes))
        <div class="text-slate-400 text-sm">Sem aulas marcadas.</div>
    @else
        @foreach ($classes as $class)
            <article class="p-3 rounded-xl border border-slate-700 bg-slate-800 flex items-start justify-between gap-3"
                data-class-start="{{ $class['startIso'] ?? '' }}">
                <div class="flex-1">
                    @if (!empty($class['dateLabel']))
                        <div class="text-xs uppercase tracking-wide text-slate-300">
                            {{ $class['dateLabel'] }}
                        </div>
                    @endif

                    <div class="font-medium">
                        {{ $class['title'] ?? ($class['course']['name'] ?? '') }}
                    </div>
                    <div class="text-sm text-slate-300">
                        {{ $class['course']['acronym'] ?? '' }}
                    </div>

                    @if (!empty($class['timeLabel']))
                        <div class="text-sm text-slate-300">
                            {{ $class['timeLabel'] }}
                        </div>
                    @endif

                    @if (!empty($class['roomsLabel']))
                        <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                            <x-lucide-map-pin class="w-3 h-3 text-slate-400" />
                            <span>{{ $class['roomsLabel'] }}</span>
                        </div>
                    @endif
                </div>

                @if (!empty($class['isOngoing']))
                    <span class="text-xs font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 px-2 py-1 rounded-md">
                        A decorrer
                    </span>
                @endif
            </article>
        @endforeach
    @endif
</div>
