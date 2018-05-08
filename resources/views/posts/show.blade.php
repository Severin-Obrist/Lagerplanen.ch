@extends('layouts.app')

<!-- Shows the post on its own-->

@section('content')
    <h1>{{$post->title}}</h1>
    <img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
    <br/><br/>
    <div>
        {!!$post->body!!}
    </div>   

    <small>Written on {{$post->created_at}} by {{$post->user->name}}</small>

    <hr>

    <div class="mt-2">        
        <a href="/posts" class="border bg-light btn btn-light">Go back</a>

        <!-- displays the edit and delete button only if it is the correct user-->
        @if(!Auth::guest())
            @if(Auth::user()->id == $post->user_id)
                <a href="/posts/{{$post->id}}/edit" class="border bg-light btn btn-light">Edit</a>
            
                {!!Form::open(['action'=>['PostsController@destroy', $post->id], 'method'=>'POST', 'class'=>'float-right'])!!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete', ['class'=> 'btn btn-danger mt-1'])}}
                {!!Form::close()!!} 
            @endif           
        @endif
    </div>
@endsection