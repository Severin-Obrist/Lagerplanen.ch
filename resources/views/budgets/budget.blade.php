@extends('layouts.app') 

@section('content')
    <h1>{{$title}}</h1> 
    <p>This is the Program page</p>

    <div>
        <a  class="btn btn-primary" href="create">create new budget</a>
    </div>
@endsection