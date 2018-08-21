@extends('layouts.app')

@section('content')
    @if(count($budget) > 0)
        <table class='table table-striped mt-2'>
            <tr>
                <th>Budgetposten</th>
                <th>Eintrag</th>
            </tr>
            @foreach($budget as $eintrag) <!-- iteriert durch die verschiedenen budgetposten in einem budget -->
                <tr>
                    <td>{{$eintrag->budgetPosten}}</td>
                    <td>{{$eintrag->content_sum}}</td>
                </tr>
            @endforeach
                
        </table>
    @else
        <p>Keine Einträge im Buget</p>
    @endif
@endsection