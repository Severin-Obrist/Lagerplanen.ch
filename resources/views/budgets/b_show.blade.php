@extends('layouts.app')

@section('content')
    <?php $budgetiert = 0;?>

    @if(!Auth::guest())
        @if(in_array(Auth::user()->id, $allowed)) <!-- lasst nur angemeldete user zu, welche zugriff auf das budget haben sollen -->  

            <h1>{{ $budgetName->budget_name }}</h1>
            <hr>

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

            
            
            <!--
            <button type="button" data-toggle="modal" data-target="#myModal">Open Modal</button>
            
            <div class="modal fade show" id="myModal" role="dialog">
                <div class="modal-dialog">
                
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            Leiter hinzufügen
                        </div>

                        <div class="modal-body">
                            test
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        -->
            {!! Form::open(['action' => ['BudgetController@addBudgetPosten'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            
            @if(count($budget) > 0)
                <table class='table table-striped mt-2'>
                    <tr>
                        <th>Budgetposten</th>
                        <th>Ausgaben</th>
                        <th>Budgetiert</th>
                        <th>Noch verfügbar</th> 
                        <!-- <th>BenutzerID</th> -->
                    </tr>
                    @foreach($budget as $key => $eintrag) <!-- iteriert durch die verschiedenen budgetposten in einem budget -->
                        <tr>
                            <td>{{ $eintrag->budgetPosten }}</td>
                            <td>{{ $eintrag->content_sum }} Fr.</td>
                            <td>
                                @foreach($budgetData as $eintragData)
                                    @if($eintragData->budgetPosten == $eintrag->budgetPosten)  
                                        {{ $eintragData->budgeted }} Fr.
                                        <?php $budgetiert = $eintragData->budgeted; ?>
                                        @break
                                    @endif
                                @endforeach
                            </td>
                            <td> <?php echo $budgetiert-$eintrag->content_sum; ?> Fr.</td>
                            <!--
                            <td>
                                @foreach($budgetData as $eintragData)
                                    @if($eintragData->budgetPosten == $eintrag->budgetPosten)  überprüft, ob der BudgetPosten des eintragData derselbe ist wie der des zurzeitigen eintrages 
                                        {{ $eintragData->user->name }} </br>  wenn ja, dann gibt es die Benutzer ID von EintragData aus 
                                    @endif
                                @endforeach
                            </td>
                            -->
                        </tr>
                    @endforeach

                    <tr>
                        <td> 
                            <div class="form-group">
                                {{ Form::text('budgetPosten', '', ['class' => 'form-control', 'placeholder' => 'neuer Budgetposten']) }} 
                            </div>
                        </td>
                        <td> 
                            <div class="form-group">
                                {{ Form::number('budgetiert', "", ['class' => 'form-control', 'placeholder' => 'budgetiert'])}} </td>
                            </div>
                        <td colspan="2">
                            <div class="form-group">
                                {{ Form::hidden('bid', $budgetID) }}
                                {{ Form::submit('Budgetposten hinzufügen', ['class' => 'btn btn-primary']) }}
                            </div>
                        </td>
                    </tr>
                        
                </table>
            @else
                <p>Keine Einträge im Budget</p>

                <table class='table table-striped mt-2'>
                    <tr>
                        <td> 
                            <div class="form-group">
                                {{ Form::text('budgetPosten', '', ['class' => 'form-control', 'placeholder' => 'neuer Budgetposten']) }} 
                            </div>
                        </td>

                        <td> 
                            <div class="form-group">
                                {{ Form::number('budgetiert', "", ['class' => 'form-control', 'placeholder' => 'budgetiert'])}}
                            </div>
                        </td>

                        <td colspan="2"> 
                            <div class="form-group">
                                {{ Form::hidden('bid', $budgetID) }}
                                {{ Form::submit('Budgetposten hinzufügen', ['class' => 'btn btn-primary']) }}
                            </div>
                        </td>
                    </tr>                
                </table>
            @endif
            {!! Form::close() !!}
            
            <div>        
                {!! Form::open(['action' => 'BudgetController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                    <div class="form-group">
                        {{ Form::label('budgetPosten', 'Budgetposten') }}

                        <select class="form-control" name="budgetPosten" multiple> <!-- normale HTML-syntax verwendet, damit ich die werte der einzelnen Optionen festelegen kann -->
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
            </div>

        @else
            Du hast keinen Zugriff auf dieses Budget!
        @endif
    @else
        Melde dich an um dieses Budget sehen zu können!
    @endif 
@endsection