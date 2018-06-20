@extends('layouts.app') 

@section('content')
    <h1>This is the budget page</h1> 
    @if(count($budgets) > 0)
        @foreach($budgets as $budget)
            <div>
                <h3><a href="/budgets/{{$budget->bid}}">Budget {{$budget->bid}}</a></h3>
            </div>
        @endforeach
    @endif
@endsection