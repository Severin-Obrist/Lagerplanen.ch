@extends('layouts.app')

@section('content')
    <h1>{{$post->title}}</h1>
    <div>
        {!!$post->body!!}
    </div>   

    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>

    <hr>

    <div class="mt-2">
        <a href="/posts" class="border bg-light btn btn-light">Go back</a>
        <a href="/posts/{{$post->id}}/edit" class="border bg-light btn btn-light">Edit</a>
    
    {!!Form::open(['action'=>['PostsController@destroy', $post->id], 'method'=>'POST', 'class'=>'float-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        {{Form::submit('Delete', ['class'=> 'btn btn-danger mt-1'])}}
    {!!Form::close()!!}

    </div>
@endsection