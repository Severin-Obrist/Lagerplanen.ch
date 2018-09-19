@extends('layouts.app')

@section('content')
    <?php $budgetiert = 0; ?>

    @if(count($budget) > 0)
    {!! Form::open(['action' => 'BudgetController@addBudgetPosten', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                <td> </td>
                <td>
                    <div class="form-group">
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
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

                <td> </td>

                <td> 
                    <div class="form-group">
                        {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
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
                {{ Form::number('content', "", ['class' => 'form-control', 'placeholder' => 'Fr.'])}}
            </div>
                    
            <div class="form-group">
                {{ Form::label('notes', 'Notizen') }}
                {{ Form::text('notes', '', ['class' => 'form-control', 'placeholder' => 'Notizen'])}}
            </div>

            {{ Form::hidden('bid', $budgetID) }} <!-- Gibt die Budget ID mit, indem es die Budget ID von einem der Einträge übernimmt -->

            {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
            
        {!! Form::close() !!}
    </div>
@endsection