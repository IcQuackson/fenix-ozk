@extends('layouts.dashboard')

@section('title', 'Dashboard · Fenix 2.0')

@section('content')
    <div class="flex flex-col lg:flex-row items-start justify-between mb-6 gap-6">
        {{-- Left: photo + welcome --}}
        <div class="flex items-center gap-6">
            @if (!empty($personalInfo['photoUri']))
                <img src="{{ $personalInfo['photoUri'] }}" alt="Profile photo"
                    class="rounded-full w-30 h-30 object-cover border border-slate-700 shadow">
            @endif

            <div>
                <h1 class="text-2xl font-semibold mb-1">
                    Bem-vindo de volta, {{ $personalInfo['name'] ?? '' }}!
                </h1>
                <div class="text-slate-400 text-sm">{{ $curriculum['degree']['name'] ?? '' }}</div>
            </div>
        </div>

        {{-- Right: personal info --}}
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-4 text-sm text-slate-300 space-y-2 w-full lg:w-64">
            @if (!empty($personalInfo['username']))
                <div class="flex items-center gap-2">
                    <x-lucide-user class="w-4 h-4 text-slate-400" />
                    <span>{{ $personalInfo['username'] }}</span>
                </div>
            @endif
            @if (!empty($personalInfo['institutionalEmail']))
                <div class="flex items-center gap-2">
                    <x-lucide-mail class="w-4 h-4 text-slate-400" />
                    <span>{{ $personalInfo['institutionalEmail'] }}</span>
                </div>
            @endif
            @if (!empty($personalInfo['campus']))
                <div class="flex items-center gap-2">
                    <x-lucide-map-pin class="w-4 h-4 text-slate-400" />
                    <span>{{ $personalInfo['campus'] }}</span>
                </div>
            @endif
            @if (!empty($curriculum['degree']['acronym']))
                <div class="flex items-center gap-2">
                    <x-lucide-graduation-cap class="w-4 h-4 text-slate-400" />
                    <span>{{ $curriculum['degree']['acronym'] }}</span>
                </div>
            @endif
        </div>
    </div>

    @include('dashboard._cards')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6 min-h-0 xl:grid-rows-[1fr_auto]">
        <section
            class="xl:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl
                p-4 flex flex-col min-h-0 h-96 xl:h-[420px] overflow-hidden">
            <div class="flex items-center justify-between mb-2 shrink-0">
                <h2 class="font-semibold">Últimos Anúncios</h2>
            </div>

            <div
                class="flex-1 min-h-0 overflow-y-auto scrollbar-thin scrollbar-track-transparent
                scrollbar-thumb-slate-400/70 hover:scrollbar-thumb-slate-500
                scrollbar-thumb-rounded-full">
                <x-announcement-list :items="$announcements" />
            </div>
        </section>



        <!-- Próximas Avaliações card -->
        <section
            class="bg-slate-900 border border-slate-800 rounded-2xl p-4
                flex flex-col min-h-0 h-full overflow-hidden">
            <div class="flex items-center justify-between mb-2 shrink-0">
                <h2 class="font-semibold">Próximas Avaliações</h2>
            </div>

            <div class="flex-1 min-h-0">
                @include('dashboard._next_evaluations')
            </div>
        </section>

        <section class="xl:col-span-3 mt-0">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-semibold">Disciplinas Inscritas</h2>
            </div>
            @include('dashboard._current_courses')
        </section>
    </div>
@endsection
