<div class="mb-4 flex items-center justify-between">
	<div class="text-slate-300">
		<span class="font-medium">
			@if(!empty($courses))
				{{ $courses[0]['term'] }}
			@endif
		</span>
	</div>
</div>


<div id="current-courses" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
	@if(empty($courses))
		<div class="text-slate-400 text-sm col-span-full">Sem disciplinas inscritas.</div>
	@else
		@foreach($courses as $c)
			<article class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
				<div class="flex items-start justify-between">
					<div>
						<h3 class="font-semibold">
							<a href="{{ $c['url'] }}" class="hover:underline">
								{{ $c['name'] }}
							</a>
						</h3>
						<div class="text-slate-400 text-sm">{{ $c['acronym'] }}</div>
					</div>
				</div>

			</article>
		@endforeach
	@endif
</div>
