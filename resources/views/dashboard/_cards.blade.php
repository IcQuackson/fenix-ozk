@php
	$cards = [
		[
			'label' => 'Próximo projeto',
			'icon' => 'rocket',
			'value' => data_get($curriculum, 'kpis.display.nextProject.value', '—'),
			'subtitle' => data_get($curriculum, 'kpis.display.nextProject.subtitle', 'Sem projetos'),
			'subtitle_class' => data_get($curriculum, 'kpis.display.nextProject.subtitleClass', 'text-slate-400'),
			'value_class' => 'text-2xl font-semibold leading-tight text-sky-200',
			'link' => data_get($curriculum, 'kpis.display.nextProject.link'),
		],
		[
			'label' => 'Situação financeira',
			'icon' => 'wallet',
			'value' => data_get($curriculum, 'kpis.display.financialStanding.value', '—'),
			'subtitle' => data_get($curriculum, 'kpis.display.financialStanding.subtitle', ''),
			'subtitle_class' => data_get($curriculum, 'kpis.display.financialStanding.subtitleClass', 'text-slate-400'),
			'value_class' => 'text-2xl font-semibold leading-tight text-sky-200',
			'link' => data_get($curriculum, 'kpis.display.financialStanding.link'),
		],
		[
			'label' => 'Média Atual',
			'icon' => 'line-chart',
			'value' => data_get($curriculum, 'kpis.display.avgGrade', '—'),
			'subtitle' => '',
			'subtitle_class' => 'text-slate-400',
			'value_class' => 'text-3xl font-semibold text-sky-200',
			'link' => null,
		],
		[
			'label' => 'ECTS Totais',
			'icon' => 'layers',
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
		activeIndex: 0,
		totalCards: {{ count($cards) }},
		showArrow: false,
		resizeHandler: null,
		touchStartX: null,
		touchEndX: null,
		goNext() {
			if (this.activeIndex >= this.totalCards - 1) return;
			this.activeIndex += 1;
		},
		goPrev() {
			if (this.activeIndex <= 0) return;
			this.activeIndex -= 1;
		},
		handleTouchStart(event) {
			if (!event.changedTouches || !event.changedTouches.length) return;
			this.touchStartX = event.changedTouches[0].screenX;
		},
		handleTouchEnd(event) {
			if (this.touchStartX === null || !event.changedTouches || !event.changedTouches.length) return;
			this.touchEndX = event.changedTouches[0].screenX;
			const delta = this.touchEndX - this.touchStartX;
			if (Math.abs(delta) > 40) {
				if (delta > 0) {
					this.goPrev();
				} else {
					this.goNext();
				}
			}
			this.touchStartX = null;
			this.touchEndX = null;
		},
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
	<div class="sm:hidden">
		<div
			class="overflow-hidden rounded-2xl"
			x-on:touchstart.passive="handleTouchStart($event)"
			x-on:touchend.passive="handleTouchEnd($event)">
			<div
				class="flex transition-transform duration-300 ease-out"
				x-bind:style="`transform: translateX(-${activeIndex * 100}%);`">
				@foreach ($cards as $card)
					<div class="min-w-full flex-shrink-0">
						<div class="bg-slate-800/80 border border-slate-700 rounded-2xl p-4 shadow-inner shadow-black/20 min-h-[120px] flex flex-col justify-between">
							<div class="flex items-center gap-2.5 text-sm font-medium text-slate-200 uppercase tracking-wide leading-tight">
								@if (!empty($card['icon']))
									<x-dynamic-component :component="'lucide-' . $card['icon']" class="w-4 h-4 text-sky-200" />
								@endif
								<span>{{ $card['label'] }}</span>
							</div>
							<div class="{{ $card['value_class'] ?? 'text-3xl font-semibold' }} mt-4">{{ $card['value'] }}</div>
							<div class="text-xs mt-3 {{ $card['subtitle_class'] }}">
								{{ $card['subtitle'] }}
							</div>
							@if (!empty($card['link']))
								<div class="text-xs mt-3">
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
					</div>
				@endforeach
			</div>
		</div>
		<div class="mt-2 flex justify-center items-center gap-3">
			@foreach ($cards as $index => $card)
				<button
					type="button"
					class="rounded-full transition duration-200"
					x-bind:class="activeIndex === {{ $index }} ? 'bg-sky-200 w-5 h-5 shadow-lg shadow-sky-900/40' : 'bg-slate-600/80 w-3 h-3 opacity-70'"
					@click="activeIndex = {{ $index }}"></button>
			@endforeach
		</div>
	</div>
	<div
		class="hidden sm:block">
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
							class="bg-slate-800 border border-slate-700 rounded-2xl p-4 min-w-[180px] sm:min-w-[200px] lg:min-w-[220px] snap-center
								flex-shrink-0 xl:min-w-0 min-h-[120px] flex flex-col justify-between">
						<div class="flex items-center gap-2.5 text-sm font-medium text-slate-200 leading-tight">
							@if (!empty($card['icon']))
								<x-dynamic-component :component="'lucide-' . $card['icon']" class="w-4 h-4 text-sky-200" />
							@endif
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
	</div>
	<div
		class="pointer-events-none absolute inset-y-0 right-2 hidden sm:flex items-center xl:hidden"
		x-cloak
		x-show="showArrow"
		x-transition.opacity.duration.200ms>
		<x-lucide-arrow-right class="w-5 h-5 text-slate-300 animate-pulse" />
	</div>
</div>
