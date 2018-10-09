@extends('layouts.app')

@section('content')
    <h1>Leiter Hinzufügen</h1>

    <!-- überprüft, ob im Array Einträge vorhanden sind -->
    @if(count($leiterArray)>0) 

        <!-- Öffent das Formular mit der Funktion addLeiter() als Ziel-->
        {!! Form::open(['action' => ['BudgetController@addLeiter'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

            <!-- Gibt die Budget ID mit, indem es die Budget ID von einem der Einträge übernimmt -->
            {{ Form::hidden('budgetID', $budgetID) }} 

            <div class="form-group">
                <select class="form-control" name="leiterSelect" multiple> <!-- normale HTML-syntax verwendet, damit ich die werte der einzelnen Optionen festelegen kann -->
                    <!-- Iteriert durch $leiterArray und fügt für jeden Eintrag eine Option im Formular -->
                    @foreach($leiterArray as $key => $leiter)
                        <option value="{{ $leiter->id }}">{{ $leiter->name }} v/o {{ $leiter->pfadiname}} </option>
                    @endforeach
                </select>        
            </div> 

            <!-- Submit-Knopf -->
            {{ Form::submit('Hinzufügen', ['class' => 'm-2 inline-block btn btn-primary']) }}       

        {!! Form::close() !!} 
        <!-- Schliesst das Formular -->
        
    <!-- Wenn das Array leer ist, wird der Folgende Text dargestellt-->
    @else
        <p> Keine Leiter mit diesem Pfadinamen gefunden, stelle sicher, dass du den Namen richtig geschrieben hast. </br>
            Falls die Person, nach der du suchst noch keinen Pfadinamen hat, suche nach "kein Pfadiname" </p>
    @endif
    <a class="m-2 inline-block btn btn-secondary" href="/budgets/{{ $budgetID }}">Zurück</a>
@endsection