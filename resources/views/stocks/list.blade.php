@extends('finance-dashboard::main')

@section('content')

@foreach ($stocks as $stock)
    {{$stock->name}}
@endforeach

@endsection
