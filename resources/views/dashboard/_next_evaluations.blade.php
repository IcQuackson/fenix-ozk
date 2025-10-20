<div id="next-evaluations" class="space-y-3 max-h-80 overflow-y-auto pr-1
                scrollbar-thin scrollbar-track-transparent
                scrollbar-thumb-slate-400/70 hover:scrollbar-thumb-slate-500
                scrollbar-thumb-rounded-full">
	@if(empty($evaluations))
		<div class="text-slate-400 text-sm">Sem exames marcados.</div>
	@else
		@foreach($evaluations as $eval)
			<div class="p-3 rounded-xl border border-slate-800 bg-slate-900 flex items-center justify-between"
				data-exam-at="{{ $eval['exam_at'] ?? '' }}">
				<div>
					<div class="font-medium">{{ $eval['course'] ?? '' }}</div>
					<div class="text-sm text-slate-300">{{ $eval['name'] }}</div>
					<div class="text-sm text-slate-400">
						{{ $eval['exam_at'] ?? 'Sem data marcada.' }}
					</div>
				</div>
				<div class="flex flex-col items-end gap-1 ml-4 shrink-0">
					<span class="text-xs font-medium px-2 py-1 rounded-sm border badge-days hidden"></span>
				</div>
			</div>
		@endforeach
	@endif
</div>
