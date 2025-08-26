@extends('layouts.guest')

@section('title', 'Entrar • ' . config('app.name'))

@section('content')
    <div class="stack" aria-labelledby="title">
        <div class="logo" aria-hidden="true">
            {{-- Use the white/negative mark for dark backgrounds --}}
            <img src="{{ asset('img/ist-logo.png') }}" alt="Instituto Superior Técnico">
        </div>

        <h1 id="title">Fénix OZK</h1>
        <p class="tagline">
            O Fénix que quer o teu sucesso
        </p>

        <a class="btn btn-primary" href="{{ route('fenix.connect') }}">
            Entrar com Fenix
        </a>

        <div class="divider" role="presentation"></div>

        <p class="meta">
            Ao continuar, concorda com o processamento dos dados autorizados no Fenix.
        </p>
    </div>
@endsection
