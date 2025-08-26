@extends('layouts.dashboard')

@section('title', 'Dashboard · Fenix 2.0')

@section('content')
	<h1 class="text-2xl font-semibold mb-2">Bem-vindo de volta, {{ $kpis['name'] ?? '' }}!</h1>
	<p class="text-slate-400 mb-6">Resumo da sua atividade académica</p>

	@include('dashboard._cards')

	<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-6">
		<section class="xl:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-4">
			<div class="flex items-center justify-between mb-2">
				<h2 class="font-semibold">Últimos Anúncios</h2>
				<button class="text-sm text-sky-400">Atualizar</button>
			</div>
			@include('dashboard._last_announcements')
		</section>

		<section class="bg-slate-900 border border-slate-800 rounded-2xl p-4">
			<div class="flex items-center justify-between mb-2">
				<h2 class="font-semibold">Próximas Avaliações</h2>
			</div>
			@include('dashboard._next_evaluations')
		</section>

		<section class="xl:col-span-3 mt-0">
			<div class="flex items-center justify-between mb-2">
				<h2 class="font-semibold">Disciplinas Este Semestre</h2>
			</div>
			@include('dashboard._current_courses')
		</section>
	</div>
@endsection
