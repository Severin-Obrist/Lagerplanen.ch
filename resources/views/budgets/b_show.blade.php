@extends('layouts.app')

@section('content')
    @if(count($budget) > 0)
        <table class='table table-striped mt-2'>
            <tr>
                <th>Budgetposten</th>
                <th>Ausgaben</th>
                <th>BenutzerID</th>
            </tr>
            @foreach($budget as $key => $eintrag) <!-- iteriert durch die verschiedenen budgetposten in einem budget -->
                <tr>
                    <td>{{$eintrag->budgetPosten}}</td>
                    <td>{{$eintrag->content_sum}}</td>
                    <td>
                        @foreach($budgetData as $eintragData)
                            @if($eintragData->budgetPosten == $eintrag->budgetPosten) <!-- überprüft, ob der BudgetPosten des eintragData derselbe ist wie der des zurzeitigen eintrages -->
                                {{$eintragData->pid}} </br> <!-- wenn ja, dann gibt es die Benutzer ID von EintragData aus -->
                            @endif
                        @endforeach
                </tr>
            @endforeach
                
        </table>
    @else
        <p>Keine Einträge im Buget</p>
    @endif

    <div>
        <table class="table table-striped mt-2">
            {!! Form::open(['action' => 'BudgetController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                <th>
                    <div class="form-group">
                        {{Form::label('budgetPostenLabel', 'Budgetposten')}}
                        {{Form::select('budgetPosten', $budgetPostenList, $selected = null, ['class' => 'form-control', 'multiple' => 'multiple']) }}
                    </div>

                    <div class="form-group">
                        {{Form::text('neuBudgetPosten', '', ['class' => 'form-control', 'placeholder' => 'neuer Budgetposten'])}}
                    </div>
                </th>

                <th>
                    
                    <div class="form-group">
                        {{Form::label('wertLabel', 'Ausgaben')}}
                        {{Form::number('wert', '', ['class' => 'form-control', 'placeholder' => 'Fr.'])}}
                    </div>
                    
                    {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
                    
                </th>
            {!! Form::close() !!}
        </table>
    </div>
@endsection