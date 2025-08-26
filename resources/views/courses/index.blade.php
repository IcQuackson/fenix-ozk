@extends('layouts.app')

@section('header')
  <h1 class="text-xl font-semibold">Your Courses</h1>
@endsection

@section('content')
  @if(empty($vm->courses))
    <p>No courses found.</p>
  @else
    <ul class="list-disc pl-6 space-y-1">
      @foreach($vm->courses as $c)
        <li>
          {{ $c->name }} — {{ $c->ects }} ECTS
          @if($c->isHeavy()) <span title="Heavy" aria-label="Heavy">🔥</span> @endif
        </li>
      @endforeach
    </ul>
  @endif
@endsection
