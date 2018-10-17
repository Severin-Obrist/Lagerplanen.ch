@extends('layouts.app') 

@section('content')
    <h1>Alle deine Budgets</h1>

    <!-- Überprüft, ob Einträge im Array sind -->
    @if(count($budgets) > 0)
        <!-- Iteriert durch das $budgets Array und erstellt einen Link zum passenden Budget für jeden Eintrag-->
        @foreach($budgets as $budget)
            <div>
                <a href="/budgets/{{$budget->bid}}">
                    <h3 class="d-inline">
                            {{$budget->budget_name}}
                    </h3>
                    @foreach ($budgetsCreator as $key => $creator)
                        @if ($creator->bid == $budget->bid)
                            @if($creator->user->pfadiname  != 'kein pfadiname')
                                <small> - von {{ $creator->user->pfadiname }}</small>
                            @else
                                <small> - von {{ $creator->user->name }}</small>
                            @endif
                        @endif
                    @endforeach
                </a>
            </div>
        @endforeach
    @endif
    
    <!-- Öffnet das Formular mit der Funktion createBudget() als Ziel-->
    {!! Form::open(['action' => 'BudgetController@createBudget', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            <table class="table mt-2">
                <th>
                    {{ Form::text('budgetName', '', ['class' => 'form-control', 'placeholder' => 'Neues Budget']) }}
                </th>
                <th>
                    {{ Form::submit('Budget erstellen', ['class' => 'btn btn-primary']) }}
                </th>
            </table>
        </div>
    {!! Form::close() !!}
    <!-- Schliesst das Formular -->
@endsection