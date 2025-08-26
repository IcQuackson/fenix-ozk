<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Fénix OZK</title>

	{{-- Skip Vite during tests to avoid "manifest not found" --}}
	@unless(app()->environment('testing'))
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	@endunless
</head>

<body class="antialiased">
	<header>
		@auth <span>{{ auth()->user()->name }}</span> @endauth
	</header>
	<main class="container">
		@yield('content')
	</main>
</body>

</html>
