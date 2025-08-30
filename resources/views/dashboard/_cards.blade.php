@php
	$cards = [
		[
			'label' => 'ECTS Totais',
			'value' => data_get($curriculum, 'kpis.display.totalEcts', '0'),
			'subtitle' => 'Concluídos (62%)',
			'subtitle_class' => 'text-emerald-400',
		],
		[
			'label' => 'Média Atual',
			'value' => data_get($curriculum, 'kpis.display.avgGrade', '—'),
			'subtitle' => '',
			'subtitle_class' => 'text-slate-500',
		],
		[
			'label' => 'ECTS Neste Termo',
			'value' => data_get($curriculum, 'kpis.display.ectsThisTerm', '0'),
			'subtitle' => 'Aprovados no período atual',
			'subtitle_class' => 'text-slate-500',
		],
		[
			'label' => 'Ritmo (ECTS/ano)',
			'value' => data_get($curriculum, 'kpis.display.ectsPerYear', '—'),
			'subtitle' => 'Estimado desde o início do curso',
			'subtitle_class' => 'text-slate-500',
		],
	];
@endphp

{{-- Optional degree header --}}
@if (data_get($curriculum, 'degree.display'))
	<div class="mb-2 text-slate-300">
		{{ data_get($curriculum, 'degree.display') }}
	</div>
@endif

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
