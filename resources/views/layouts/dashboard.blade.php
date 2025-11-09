<!doctype html>
<html lang="pt" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <title>@yield('title', 'IST Fenix')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="h-full bg-slate-900 text-slate-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-slate-900 border-r border-slate-700 p-4 transform transition-transform duration-200 ease-in-out md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon.ico') }}" alt="IST Fenix Logo" class="h-8 w-8 rounded-md">
                    <div>
                        <div class="font-semibold">Fénix OZK</div>
                        <div class="text-xs text-slate-400">O Fénix que quer o teu sucesso</div>
                    </div>
                </div>
                <button class="md:hidden p-2 text-slate-300" @click="sidebarOpen=false">✕</button>
            </div>
            @php
                $comingSoonClasses = 'flex items-center justify-between rounded-lg px-3 py-2 border border-slate-800 bg-slate-900 text-slate-500 pointer-events-none opacity-70';
            @endphp
            <nav class="flex-1 space-y-1 text-slate-200">
                <a href="{{ route('dashboard') }}"
                    class="block rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('dashboard') ? 'bg-sky-500/10 text-sky-300 border border-sky-500/20' : 'border border-transparent hover:bg-slate-800 hover:border-slate-700' }}">
                    Dashboard
                </a>
                <div class="{{ $comingSoonClasses }}" aria-disabled="true">
                    <span>Informação Pessoal</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-800 rounded-full px-2 py-0.5 whitespace-nowrap">em breve</span>
                </div>
                <div class="{{ $comingSoonClasses }}" aria-disabled="true">
                    <span>Curricular</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-800 rounded-full px-2 py-0.5 whitespace-nowrap">em breve</span>
                </div>
                <div class="{{ $comingSoonClasses }}" aria-disabled="true">
                    <span>Avaliações</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-800 rounded-full px-2 py-0.5 whitespace-nowrap">em breve</span>
                </div>
                <div class="{{ $comingSoonClasses }}" aria-disabled="true">
                    <span>Horário</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-800 rounded-full px-2 py-0.5 whitespace-nowrap">em breve</span>
                </div>
                <div class="{{ $comingSoonClasses }}" aria-disabled="true">
                    <span>Pagamentos</span>
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-400 bg-slate-800 rounded-full px-2 py-0.5 whitespace-nowrap">em breve</span>
                </div>
            </nav>
            <form method="POST" action="{{ route('logout') }}"
                class="mt-6 border-t border-slate-800 pt-4">
                @csrf
                <button type="submit"
                    class="w-full rounded-lg px-3 py-2 text-left transition-colors border border-transparent bg-slate-800 hover:bg-red-500/10 hover:border-red-500/50 hover:text-red-300 hover:cursor-pointer">
                    Terminar sessão
                </button>
            </form>
        </aside>

        <!-- Overlay -->
        <div x-cloak x-show="sidebarOpen" @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/40 md:hidden"
            x-transition.opacity></div>

        <!-- Main -->
        <main class="flex-1 min-h-0 p-6 overflow-hidden w-full md:ml-64 transition-all">
            <button class="md:hidden mb-4 p-2 bg-slate-800 rounded" @click="sidebarOpen=true">☰</button>
            @yield('content')
        </main>
    </div>
</body>

</html>
