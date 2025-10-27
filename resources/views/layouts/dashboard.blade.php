<!doctype html>
<html lang="pt" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <title>@yield('title', 'IST Fenix')</title>

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</head>

<body class="h-full bg-slate-950 text-slate-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 border-r border-slate-800 p-4">
            <div class="flex items-center gap-2 mb-6">
                <img src="{{ asset('favicon.ico') }}" alt="IST Fenix Logo" class="h-8 w-8 rounded-md">
                <div>
                    <div class="font-semibold">Fénix OZK</div>
                    <div class="text-xs text-slate-400">O Fénix que quer o teu sucesso</div>
                </div>
            </div>
            <nav class="space-y-1 text-slate-300">
                <a href="{{ route('dashboard') }}" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Dashboard</a>
                <a href="#" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Informação
                    Pessoal</a>
                <a href="#" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Curricular</a>
                <a href="#" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Avaliações</a>
                <a href="#" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Horário</a>
                <a href="#" class="block rounded-sm px-3 py-2 hover:bg-slate-800">Pagamentos</a>
            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1 min-h-0 p-6 overflow-hidden">
            @yield('content')
        </main>
    </div>
</body>

</html>
