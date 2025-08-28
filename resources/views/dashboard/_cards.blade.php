@php
	$cards = [
		[
			'label' => 'Disciplinas Inscritas',
			'value' => $kpis['disciplines'] ?? 0,
			'subtitle' => 'Este semestre',
			'subtitle_class' => 'text-emerald-400',
		],
		[
			'label' => 'Média Atual',
			'value' => $kpis['average'] ?? '—',
			'subtitle' => 'Valores até agora',
			'subtitle_class' => 'text-slate-500',
		],
		[
			'label' => 'Disciplinas Aprovadas',
			'value' => $kpis['approved_count'] ?? 0,
			'subtitle' => 'Em ' . ($kpis['degree_total_courses_count'] ?? 0)
				. ' (' . round(($kpis['completion_ratio'] ?? 0) * 100, 1) . '%)',
			'subtitle_class' => 'text-slate-500',
		],
		[
			'label' => 'Trabalhos Pendentes',
			'value' => $kpis['pending_work'] ?? 0,
			'subtitle' => 'Por entregar',
			'subtitle_class' => 'text-slate-500',
		],
	];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
	@foreach ($cards as $card)
		<div class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
			<div class="text-slate-400 text-sm">{{ $card['label'] }}</div>
			<div class="text-3xl font-semibold mt-2">{{ $card['value'] }}</div>
			<div class="text-xs mt-1 {{ $card['subtitle_class'] }}">
				{{ $card['subtitle'] }}
			</div>
		</div>
	@endforeach
</div>
