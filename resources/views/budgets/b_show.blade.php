@extends('layouts.app')

@section('content')
    <?php $budgetiert = 0;?>

    <!-- überprüft, ob der Benutzer angemeldet ist, geht weiter wenn ja-->
    @if(!Auth::guest()) 
        <!-- lasst nur angemeldete user zu, welche zugriff auf das budget haben sollen --> 
        @if(in_array(Auth::user()->id, $allowed))  

            <h1>{{ $budgetName->budget_name }}</h1>
            <hr>
            <div class="form-group">

            <!-- Öffnet ein Formular mit der Funktion leiterSearch() las Ziel -->
            {!! Form::open(['action' => ['BudgetController@leiterSearch'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <div>
                    {{ Form::label('pfadiname', 'Leiter hinzufügen') }}
                    {{ Form::hidden('bid', $budgetID) }} <!-- Gibt die Budget ID mit, indem es die Budget ID von einem der Einträge übernimmt -->
                </div>
                <div class="input-group">
                    {{ Form::text('pfadiname', '', ['class' => 'form-control', 'placeholder' => 'Pfadiname']) }} 
                    <div class="input-group-append">
                            {{ Form::submit('Suchen', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            {!! Form::close() !!}
            <!-- Schliesst das Formular -->
            </div>
            
            <!-- Öffnet ein Formular mit der Funktion addBudgetPoster() als Ziel-->
            {!! Form::open(['action' => ['BudgetController@addBudgetPosten'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            
            <!-- Überprüft, ob Einträge im $budget Array vorhanden sind-->
            @if(count($budget) > 0)
                <table class='table table-striped mt-2'>
                    <tr>
                        <th>Budgetposten</th>
                        <th>Ausgaben</th>
                        <th>Budgetiert</th>
                        <th>Noch verfügbar</th> 
                        <th>Infos</th>
                    </tr>
                    <!-- iteriert durch die verschiedenen budgetposten in $budget und erzeugt neue Tabellenreihen it den dazugehörigen Daten -->
                    @foreach($budget as $key => $eintrag) 
                        <tr>
                            <td>{{ $eintrag->budgetPosten }}</td>
                            <td>{{ $eintrag->content_sum }} Fr.</td>
                            <td>
                                <!-- Iteriert durch $budgetData-->
                                @foreach($budgetData as $eintragData)
                                    <!-- Falls ein Eintrag von BudgetData == Eintrag von $budget, zeigt es die 'budgeted' Spalte an-->
                                    @if($eintragData->budgetPosten == $eintrag->budgetPosten)  
                                        {{ $eintragData->budgeted }} Fr.
                                        <?php $budgetiert = $eintragData->budgeted; ?>
                                        @break
                                    @endif
                                @endforeach
                            </td>

                            <td> <?php echo $budgetiert-$eintrag->content_sum; ?> Fr.</td>

                            <td>
                            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#budgetModal{{ $eintrag->budgetPosten }}">Mehr</button>
            
                            <!-- Das 'Modal' mit den zusätzlichen Informationen, wird erst angezeigt, wenn auf den obigen Knopf gedrückt wird -->
                            <div class="modal fade show" id="budgetModal{{ $eintrag->budgetPosten }}" role="dialog">
                                <div class="modal-dialog">
                                
                                    
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            Infos
                                        </div>

                                        <div class="modal-body">
                                            <table class='table table-striped mt-2'>
                                                <tr>
                                                    <th>Benutzer</th>
                                                    <th>Ausgaben</th>
                                                    <th>Notizen</th>
                                                </tr>

                                                <!-- Iteriert durch $budgetData, erstellt Tabellenreihen mit den zusätzlichen Infos für jeden Eintrag -->
                                                @foreach($budgetData as $eintragData)
                                                    <tr>
                                                        @if($eintragData->budgetPosten == $eintrag->budgetPosten)  <!--überprüft, ob der BudgetPosten des eintragData derselbe ist wie der des zurzeitigen eintrages -->
                                                            <td>                                                   <!--wenn ja, dann gibt es den Namen des Benutzers von EintragData aus -->
                                                                @if($eintragData->user->pfadiname != "kein pfadiname")
                                                                    {{ $eintragData->user->pfadiname }}
                                                                @else
                                                                    {{ $eintragData->user->name }}
                                                                @endif
                                                            </td>   
                                                            <td>{{ $eintragData->content }}</td> 
                                                            <td>{{ $eintragData->notes }}</td> 
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-outline-danger" type="button" data-toggle="modal" data-target="#deleteModal{{ $eintrag->budgetPosten }}">Löschen</button>
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Schliessen</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal geschlossen -->

                            <!-- Modal zum Bestätigen, dass man den Budgetposten Schliessen Will-->
                            <div class="modal fade show" id="deleteModal{{ $eintrag->budgetPosten }}" role="dialog">
                                <div class="modal-dialog">
                                    
                                        
                                    <div class="modal-content">
                                        <div class="modal-header modal-title">
                                            <h3> Budgetposten {{ $eintrag->budgetPosten }} wirklich löschen? </h3>
                                        </div>

                                        <div class="modal-body">
                                            <div class="text-center">
                                                <a href="/deleteBudgetPosten/{{ $budgetID }}/{{ $eintrag->budgetPosten }}"><span class="btn btn-danger">Löschen</span></a>
                                            </div>
                                        </div>
    
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Abbrechen</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal geschlossen -->

                            </td>

                        </tr>
                    @endforeach

                    <!-- Formular zum erzeugen von Budgetposten, wenn Einträge in $budget vorhanden sind -->
                    <tr>
                        <td colspan="3"> 
                            <div class="form-group">
                                {{ Form::label('budgetPosten', 'Budegetposten') }}
                                {{ Form::text('budgetPosten', '', ['class' => 'form-control', 'placeholder' => 'neuer Budgetposten']) }} 
                            </div>
                        </td>
                        <td> 
                            <div class="form-group">
                                {{ Form::label('budgetiert', 'Budgetiert') }}
                                {{ Form::number('budgetiert', "", ['class' => 'form-control', 'placeholder' => 'budgetiert'])}} </td>
                            </div>
                        <td >
                            <div class="form-group">
                                {{ Form::hidden('bid', $budgetID) }}
                                {{ Form::label('submit', 'Hinzufügen') }} </br>
                                {{ Form::submit('Bestätigen', ['class' => 'btn btn-primary']) }}
                            </div>
                        </td>
                    </tr>
                        
                </table>
            @else
                <table class='table table-striped mt-2'>
                    <tr>
                        <th colspan="5" >Keine Einträge im Budget </th>
                    </tr>

                        <!-- Formular zum erzeugen von Budgetposten, wenn keine Einträge in $budget vorhanden sind -->
                        <tr>
                            <td colspan="3"> 
                                <div class="form-group">
                                    {{ Form::label('budgetPosten', 'Budegtposten') }}
                                    {{ Form::text('budgetPosten', '', ['class' => 'form-control', 'placeholder' => 'neuer Budgetposten']) }} 
                                </div>
                            </td>
                            <td> 
                                <div class="form-group">
                                    {{ Form::label('budgetiert', 'Budgetiert') }}
                                    {{ Form::number('budgetiert', "", ['class' => 'form-control', 'placeholder' => 'budgetiert'])}} </td>
                                </div>
                            <td >
                                <div class="form-group">
                                    {{ Form::hidden('bid', $budgetID) }}
                                    {{ Form::label('submit', 'Hinzufügen') }} </br>
                                    {{ Form::submit('Bestätigen', ['class' => 'btn btn-primary']) }}
                                </div>
                            </td>
                        </tr>             
                </table>
            @endif
            {!! Form::close() !!}
            <!-- Schliesst das Formular -->            
            
            <div>        
                <!-- Öffnet ein Formular mit der Funktion store() als Ziel-->
                {!! Form::open(['action' => 'BudgetController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{ Form::label('budgetPosten', 'Budgetposten') }}

                        <select class="form-control" name="budgetPosten" multiple> <!-- normale HTML-syntax verwendet, damit ich die werte der einzelnen Optionen festelegen kann -->
                            <!-- Iteriert durch $budgetPostenList um die Auswahlmöglichkeiten im Formular festzulegen-->
                            @foreach($budgetPostenList as $key => $bPLEintrag)
                                <option value="{{ $bPLEintrag }}">{{ $bPLEintrag }} </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="form-group">
                        {{ Form::label('content', 'Ausgaben') }}
                        {{ Form::number('content', "", ['class' => 'form-control', 'placeholder' => 'Fr.']) }}
                    </div>
                            
                    <div class="form-group">
                        {{ Form::label('notes', 'Notizen') }}
                        {{ Form::text('notes', '', ['class' => 'form-control', 'placeholder' => 'Notizen']) }}
                    </div>

                    {{ Form::hidden('bid', $budgetID) }} <!-- Gibt die Budget ID mit, indem es die Budget ID von einem der Einträge übernimmt -->

                    {{ Form::submit('Ausgabe hinzufügen', ['class' => 'btn btn-primary']) }}
                    
                {!! Form::close() !!}
                <!-- Schliesst das Formular -->
            </div>

        @else
            Du hast keinen Zugriff auf dieses Budget!
        @endif
    @else
        Melde dich an um dieses Budget sehen zu können!
    @endif 
@endsection