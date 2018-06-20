@extends('layouts.app')

@section('content')
    @if(count($budget) > 0)
        <table class='table table-striped mt-2'>
            <tr>
                <th>Budgetposten</th>
                <th>Eintrag</th>
            </tr>
            @foreach($budget as $eintrag)
                <tr>
                    <td>{{$eintrag->budgetPosten}}</td>
                    <td>{{$eintrag->content}}</td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection