@php
	$cards = [
		[
			'label' => 'Próximo projeto',
			'value' => data_get($curriculum, 'kpis.display.nextProject.value', '—'),
			'subtitle' => data_get($curriculum, 'kpis.display.nextProject.subtitle', 'Sem projetos'),
			'subtitle_class' => data_get($curriculum, 'kpis.display.nextProject.subtitleClass', 'text-slate-400'),
			'value_class' => 'text-2xl font-semibold leading-tight text-sky-300',
			'link' => data_get($curriculum, 'kpis.display.nextProject.link'),
		],
		[
			'label' => 'Situação financeira',
			'value' => data_get($curriculum, 'kpis.display.financialStanding.value', '—'),
			'subtitle' => data_get($curriculum, 'kpis.display.financialStanding.subtitle', ''),
			'subtitle_class' => data_get($curriculum, 'kpis.display.financialStanding.subtitleClass', 'text-slate-400'),
			'value_class' => 'text-2xl font-semibold leading-tight text-sky-300',
			'link' => data_get($curriculum, 'kpis.display.financialStanding.link'),
		],
		[
			'label' => 'Média Atual',
			'value' => data_get($curriculum, 'kpis.display.avgGrade', '—'),
			'subtitle' => '',
			'subtitle_class' => 'text-slate-400',
			'value_class' => 'text-3xl font-semibold text-sky-200',
			'link' => null,
		],
		[
			'label' => 'ECTS Totais',
			'value' => data_get($curriculum, 'kpis.display.totalEcts', '0'),
			'subtitle' => '',
			'subtitle_class' => 'text-slate-400',
			'value_class' => 'text-3xl font-semibold text-sky-200',
			'link' => null,
		],
	];
@endphp


<div
	class="relative pb-2"
	x-data="{
		showArrow: false,
		resizeHandler: null,
		updateArrow() {
			const el = this.$refs.scroller;
			if (!el) return;
			const maxScroll = el.scrollWidth - el.clientWidth;
			this.showArrow = maxScroll > 4 && el.scrollLeft < maxScroll - 4;
		},
		init() {
			this.$nextTick(() => this.updateArrow());
			this.resizeHandler = () => this.updateArrow();
			window.addEventListener('resize', this.resizeHandler);
		},
		destroy() {
			if (this.resizeHandler) {
				window.removeEventListener('resize', this.resizeHandler);
			}
		}
	}"
	x-init="() => {
		init();
		return () => destroy();
	}"
	x-on:mouseenter="updateArrow()"
	x-on:touchstart="updateArrow()">
	<div
		class="overflow-x-auto"
		x-ref="scroller"
		@scroll="updateArrow()"
		@touchstart="updateArrow()">
		<div
			class="flex gap-4 sm:gap-6 snap-x snap-mandatory min-w-max pr-2 xl:pr-0
				xl:grid xl:grid-cols-4 xl:gap-6 xl:min-w-full">
			@foreach ($cards as $card)
				<div
						class="bg-slate-800 border border-slate-700 rounded-2xl p-5 min-w-[180px] sm:min-w-[200px] lg:min-w-[220px] snap-center
							flex-shrink-0 xl:min-w-0">
					<div class="flex items-center gap-2 text-sm font-medium text-slate-200">
						<span class="inline-block w-2 h-2 rounded-full bg-sky-400"></span>
						<span>{{ $card['label'] }}</span>
					</div>
					<div class="{{ $card['value_class'] ?? 'text-3xl font-semibold' }} mt-3">{{ $card['value'] }}</div>
					<div class="text-xs mt-2 {{ $card['subtitle_class'] }}">
						{{ $card['subtitle'] }}
					</div>
					@if (!empty($card['link']))
						<div class="text-xs mt-2">
							<a
								href="{{ $card['link'] }}"
								class="text-sky-400 hover:text-sky-300 underline"
								target="_blank"
								rel="noreferrer">
								Ver submissão
							</a>
						</div>
					@endif
				</div>
			@endforeach
		</div>
	</div>
	<div
		class="pointer-events-none absolute inset-y-0 right-2 flex items-center xl:hidden"
		x-cloak
		x-show="showArrow"
		x-transition.opacity.duration.200ms>
		<x-lucide-arrow-right class="w-5 h-5 text-slate-300 animate-pulse" />
	</div>
</div>
