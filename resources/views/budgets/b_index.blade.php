@extends('layouts.app') 

@section('content')
    <h1>Alle deine Budgets</h1>

    @if(count($budgets) > 0)
        @foreach($budgets as $budget)
            <div>
                <h3><a href="/budgets/{{$budget->bid}}">{{$budget->budget_name}}</a></h3>
            </div>
        @endforeach
    @endif
    
    {!! Form::open(['action' => 'BudgetController@createBudget', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            <table class="table mt-2">
                <th>
                    {{ Form::text('budgetName', '', ['class' => 'form-control', 'placeholder' => 'Neues Budget']) }}
                </th>
                <th>
                    {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
                </th>
            </table>
        </div>
    {!! Form::close() !!}
@endsection