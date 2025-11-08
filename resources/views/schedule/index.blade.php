@extends('layouts.dashboard')

@section('title', 'Horário · Fénix OZK')

@section('content')
    <div
        x-data="schedulePageComponent(@js([
            'schedule' => $schedule,
            'dataUrl' => route('schedule.data'),
        ]))"
        x-init="init()"
        class="space-y-6"
        x-cloak
    >
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Horário</h1>
                <p class="text-sm text-slate-300">
                    <span x-text="academicTerm ? `Período académico ${academicTerm}` : 'Período académico não disponível'"></span>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-stretch rounded-xl border border-slate-700 overflow-hidden divide-x divide-slate-700">
                    <button
                        type="button"
                        class="px-3 py-2 text-sm text-slate-200 bg-slate-900/70 hover:bg-slate-800 transition disabled:opacity-40 disabled:cursor-not-allowed"
                        @click="previousWeek()"
                        :disabled="!canGoPreviousWeek()"
                    >
                        Semana anterior
                    </button>

                    <div class="px-4 py-2 text-sm font-medium text-slate-200 min-w-[170px] text-center bg-slate-900/40">
                        <span x-text="weekLabel()"></span>
                    </div>

                    <button
                        type="button"
                        class="px-3 py-2 text-sm text-slate-200 bg-slate-900/70 hover:bg-slate-800 transition disabled:opacity-40 disabled:cursor-not-allowed"
                        @click="nextWeek()"
                        :disabled="!canGoNextWeek()"
                    >
                        Semana seguinte
                    </button>
                </div>

                <button
                    type="button"
                    class="px-4 py-2 text-sm rounded-xl border border-slate-700 text-slate-200 bg-slate-900/70 hover:border-sky-500/60 hover:text-sky-300 transition"
                    @click="goToCurrentWeek()"
                >
                    Semana atual
                </button>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl border border-sky-500/40 text-sky-300 bg-sky-500/10 hover:bg-sky-500/20 transition disabled:opacity-60 disabled:cursor-wait"
                    @click="refresh()"
                    :disabled="loading"
                >
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16.023 9.348h4.202V5.146M2.25 14.651v4.202h4.202m13.344-9.505A7.5 7.5 0 104.651 19.846M21.75 9.348l-3 3m0 0 3 3" />
                    </svg>
                    Atualizar
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <label class="text-xs uppercase tracking-wide text-slate-400">Filtrar por disciplina</label>
            <select
                x-model="selectedCourse"
                class="bg-slate-900/70 border border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-100 focus:border-sky-500 focus:ring-0"
            >
                <option value="all">Todas as disciplinas</option>
                <template x-for="course in courses" :key="course.id">
                    <option :value="course.id" x-text="course.label"></option>
                </template>
            </select>

            <button
                type="button"
                class="text-xs text-slate-400 hover:text-slate-200 underline underline-offset-2 transition"
                x-show="hasFiltersActive()"
                @click="resetFilters()"
            >
                Limpar filtro
            </button>

            <span class="text-xs text-slate-400 flex items-center gap-2">
                <span class="inline-flex items-center justify-center h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"
                    x-show="hasOngoingClass"></span>
                <span x-text="nextSessionLabel()"></span>
            </span>
        </div>

        <div class="relative">
            <template x-if="error">
                <div class="bg-red-500/10 border border-red-500/40 text-red-200 rounded-xl p-4">
                    <h2 class="font-semibold mb-1 text-red-100">Não foi possível carregar o horário</h2>
                    <p class="text-sm" x-text="error"></p>
                </div>
            </template>

            <template x-if="!error">
                <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-4 md:p-6">
                    <template x-if="visibleDays().length > 0">
                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
                            <template x-for="day in visibleDays()" :key="day.iso">
                                <section
                                    class="flex flex-col rounded-2xl border border-slate-700/80 bg-slate-900/60 backdrop-blur-sm h-full">
                                    <header
                                        class="px-4 py-3 border-b flex items-baseline justify-between gap-3"
                                        :class="day.isToday ? 'bg-sky-500/10 border-sky-500/40 text-sky-200' : 'border-slate-700/80 bg-slate-900/40'"
                                    >
                                        <div>
                                            <div class="text-xs uppercase tracking-wide" x-text="day.shortLabel"></div>
                                            <div class="text-lg font-semibold">
                                                <span x-text="day.dayNumber"></span>
                                                <span class="text-sm font-normal text-slate-400 ml-1"
                                                    x-text="day.monthLabel"></span>
                                            </div>
                                        </div>
                                        <span class="text-xs text-slate-400" x-text="day.events.length === 1 ? '1 aula' : `${day.events.length} aulas`"></span>
                                    </header>

                                    <div class="flex-1 px-4 py-4 space-y-4 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700/50 scrollbar-track-transparent">
                                        <template x-if="day.events.length === 0">
                                            <p class="text-xs text-slate-500">Sem aulas programadas.</p>
                                        </template>

                                        <template x-for="event in day.events" :key="event.startIso + (event.course?.id ?? '')">
                                            <article
                                                class="relative p-4 rounded-xl border shadow-sm transition-all duration-200 group"
                                                :class="{
                                                    'ring-2 ring-sky-400/60 ring-offset-2 ring-offset-slate-900': event.isOngoing,
                                                    'opacity-60': event.isPast
                                                }"
                                                :style="eventStyle(event)"
                                            >
                                                <div class="flex items-center justify-between text-xs uppercase tracking-wide mb-2">
                                                    <span class="font-semibold" x-text="event.course?.acronym || '—'"></span>
                                                    <span x-text="event.timeRange"></span>
                                                </div>

                                                <h3 class="text-sm font-semibold text-slate-100" x-text="event.title"></h3>
                                                <p class="text-xs text-slate-300 mt-2" x-text="event.roomsLabel || 'Sala por atribuir'"></p>

                                                <div class="mt-3 flex flex-wrap items-center gap-2 text-[11px] text-slate-300/80">
                                                    <template x-if="event.isOngoing">
                                                        <span class="inline-flex items-center gap-1 text-emerald-300 bg-emerald-500/10 border border-emerald-500/40 px-2 py-1 rounded-full">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                                            A decorrer
                                                        </span>
                                                    </template>
                                                    <span x-text="courseTitle(event.course)"></span>
                                                    <span x-show="event.durationMinutes" class="text-slate-400/80">
                                                        · <span x-text="`${event.durationMinutes} min`"></span>
                                                    </span>
                                                </div>
                                            </article>
                                        </template>
                                    </div>
                                </section>
                            </template>
                        </div>
                    </template>

                    <template x-if="visibleDays().length === 0">
                        <div class="text-center text-slate-400 py-12">
                            Sem aulas programadas para as semanas disponíveis.
                        </div>
                    </template>
                </div>
            </template>

            <div
                x-show="loading"
                class="absolute inset-0 rounded-2xl bg-slate-900/80 backdrop-blur-sm flex items-center justify-center"
                x-transition.opacity
            >
                <div class="flex items-center gap-3 text-slate-200 text-sm">
                    <svg class="w-5 h-5 animate-spin text-sky-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3.5-3.5L12 0v4a8 8 0 00-8 8h4z">
                        </path>
                    </svg>
                    <span>A atualizar horário…</span>
                </div>
            </div>
        </div>
    </div>
@endsection
