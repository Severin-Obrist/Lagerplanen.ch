@extends('layouts.app')

@section('content')
    <h1>Leiter Hinzufügen</h1>
    
    @if(count($leiterArray)>0)
        {!! Form::open(['action' => ['BudgetController@addLeiter'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

            {{ Form::hidden('budgetID', $budgetID) }} <!-- Gibt die Budget ID mit, indem es die Budget ID von einem der Einträge übernimmt -->

            <div class="form-group">
                <select class="form-control" name="leiterSelect" multiple> <!-- normale HTML-syntax verwendet, damit ich die werte der einzelnen Optionen festelegen kann -->
                    @foreach($leiterArray as $key => $leiter)
                        <option value="{{ $leiter->id }}">{{ $leiter->name }} v/o {{ $leiter->pfadiname}} </option>
                    @endforeach
                </select>        
            </div> 

            {{ Form::submit('Hinzufügen', ['class' => 'btn btn-primary']) }}       

        {!! Form::close() !!} 
    @else
        <p> Keine Leiter mit diesem Pfadinamen gefunden, stelle sicher, dass du den Namen richtig geschrieben hast. </br>
            Falls die Person, nach der du suchst noch keinen Pfadinamen hat, suche nach "kein Pfadiname" </p>
        <a class="btn btn-primary" href="/budgets/{{ $budgetID }}">Zurück</a>
    @endif
@endsection