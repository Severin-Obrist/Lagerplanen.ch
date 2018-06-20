@extends('layouts.app')

@section('content')
    @if(count($budget) > 0)
        @foreach($budget as $eintrag)
            <div>
                <h3>{{$eintrag->budgetPosten}}</h3>
                <p>Eintrag: {{$eintrag->content}}</p>
            </div>
        @endforeach
    @endif
@endsection