<div class="mb-4 flex items-center justify-between">
	<div class="text-slate-300">
		<span id="academic-year-label" class="font-medium"></span>
	</div>
	<div class="inline-flex rounded-xl border border-slate-800 bg-slate-900 p-1 text-sm">
		<button id="btn-sem-1" data-sem="1" class="px-3 py-1 rounded-lg hover:bg-slate-800" aria-pressed="true">1º
			semestre</button>
		<button id="btn-sem-2" data-sem="2" class="px-3 py-1 rounded-lg hover:bg-slate-800" aria-pressed="false">2º
			semestre</button>
	</div>
</div>

<div id="current-courses" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
	@if(empty($courses))
		<div class="text-slate-400 text-sm col-span-full">Sem disciplinas inscritas.</div>
	@else
		@foreach($courses as $c)
			<article class="course-card bg-slate-900 border border-slate-800 rounded-2xl p-4"
				data-semester="{{ $c['semester'] }}" data-year="{{ $c['year'] }}">
				<div class="flex items-start justify-between">
					<div>
						<h3 class="font-semibold">{{ $c['name'] }}</h3>
						<div class="text-slate-400 text-sm">{{ $c['acronym'] }}</div>
					</div>
				</div>
				<div class="mt-3 space-y-2 text-sm">
					<div class="flex items-center gap-2">🗓 <span>Teste/Exame em {{ $c['next_exam'] ?? '' }}</span></div>
					<div class="flex items-center gap-2">🔔 <span>{{ $c['announcement'] ?? '' }}</span></div>
				</div>
			</article>
		@endforeach
	@endif
</div>
<div id="empty-semester" class="hidden text-slate-400 text-sm mt-2">Sem disciplinas neste semestre.</div>
<script>
	// same filter by semester script from original
</script>
