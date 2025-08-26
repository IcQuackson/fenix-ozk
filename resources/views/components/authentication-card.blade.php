@props(['logo' => null])

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-950 text-slate-100">
	<div>
		{{ $logo }}
	</div>

	<div
		class="w-full sm:max-w-md mt-6 px-6 py-8 bg-slate-900 shadow-lg shadow-black/20 overflow-hidden sm:rounded-xl ring-1 ring-slate-800">
		{{ $slot }}
	</div>
</div>
