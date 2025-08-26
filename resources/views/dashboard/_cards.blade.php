<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
	<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
		<div class="text-slate-400 text-sm">Disciplinas Inscritas</div>
		<div class="text-3xl font-semibold mt-2">{{ $kpis['disciplines'] ?? 0 }}</div>
		<div class="text-xs text-emerald-400 mt-1">Este semestre</div>
	</div>

	<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
		<div class="text-slate-400 text-sm">Média Atual</div>
		<div class="text-3xl font-semibold mt-2">{{ $kpis['average'] ?? '—' }}</div>
		<div class="text-xs text-slate-500 mt-1">Valores até agora</div>
	</div>

	<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
		<div class="text-slate-400 text-sm">Disciplinas Aprovadas</div>
		<div class="text-3xl font-semibold mt-2">{{ $kpis['approved_count'] ?? 0 }}</div>
		<div class="text-xs text-slate-500 mt-1">
			Em {{ $kpis['degree_total_courses_count'] ?? 0 }}
			({{ round(($kpis['completion_ratio'] ?? 0) * 100, 1) }}%)
		</div>
	</div>

	<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
		<div class="text-slate-400 text-sm">Trabalhos Pendentes</div>
		<div class="text-3xl font-semibold mt-2">{{ $kpis['pending_work'] ?? 0 }}</div>
		<div class="text-xs text-slate-500 mt-1">Por entregar</div>
	</div>
</div>
