<div id="last-announcements">
  @if(empty($announcements))
    <div class="text-slate-400 text-sm">Sem anúncios recentes.</div>
  @else
    <div class="max-h-56 overflow-y-auto pr-2
                scrollbar-thin scrollbar-track-transparent
                scrollbar-thumb-slate-400/70 hover:scrollbar-thumb-slate-500
                scrollbar-thumb-rounded-full">
      @foreach($announcements as $a)
        <div class="py-3 flex items-start justify-between border-b border-slate-200 last:border-b-0">
          <div class="min-w-0">
            <div class="font-medium truncate">{{ $a['course_name'] }}</div>
            <div class="text-sm text-slate-400 truncate">
              {{ $a['published_at'] }} — {{ $a['title'] }}
            </div>
          </div>
          <a class="text-sky-500 text-sm shrink-0 ml-3" href="{{ $a['link'] }}" target="_blank" rel="noopener">ver</a>
        </div>
      @endforeach
    </div>
  @endif
</div>
