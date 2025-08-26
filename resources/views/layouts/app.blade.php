<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        {{-- Optional top navigation (keep if you have this partial, e.g., from Breeze) --}}
        @includeWhen(View::exists('layouts.navigation'), 'layouts.navigation')

        {{-- Page Heading (section-based) --}}
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        {{-- Flash messages --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div role="alert" class="mb-3 rounded bg-green-100 p-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div role="alert" class="mb-3 rounded bg-red-100 p-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page Content (section-based) --}}
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>

</html>
