@extends('layouts.app')

@section('content')
    <h1>{{$budget->budgetPosten}}</h1>
    <div>
        {{$budget->content}}
    </div>
@endsection