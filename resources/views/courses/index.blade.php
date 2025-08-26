@extends('layouts.app')

@section('content')
  <h1>Your Courses</h1>
  <ul>
    @foreach($vm->courses as $c)
      <li>{{ $c->name }} — {{ $c->ects }} ECTS @if($c->isHeavy())🔥@endif</li>
    @endforeach
  </ul>
@endsection
