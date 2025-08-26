<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ config('app.name', 'Laravel') }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
	<div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
		<div class="mb-6">
			{{-- Breeze logo slot (optional) --}}
			{{ $logo ?? '' }}
		</div>

		<div
			class="w-full sm:max-w-md px-6 py-8 bg-slate-900 shadow-lg shadow-black/20 overflow-hidden sm:rounded-xl ring-1 ring-slate-800">
			{{ $slot }}
		</div>
	</div>
</body>

</html>
