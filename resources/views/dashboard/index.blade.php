@extends('layouts.app')

@section('content')
	<h1>Dashboard</h1>
	<p>Total ECTS: {{ $summary['ectsSum'] ?? 0 }}</p>
@endsection
