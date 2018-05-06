@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in! 

                    <div class="mt-2">
                        <a href="/posts/create" class="btn btn-primary">Create Post</a>
                    </div>
                    
                    @if(count($posts) > 0)
                        <table class="table table-striped mt-2">
                            <tr>
                                <th>Title</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach($posts as $post)
                                <tr>
                                    <td>
                                        {{$post->title}}
                                    </td>
                                    <td>
                                        <a href="/posts/{{$post->id}}/edit" class="btn btn-outline-primary">Edit</a>
                                    </td>
                                    <td>
                                        {!!Form::open(['action'=>['PostsController@destroy', $post->id], 'method'=>'POST', 'class'=>'float-right'])!!}
                                            {{Form::hidden('_method', 'DELETE')}}
                                            {{Form::submit('Delete', ['class'=> 'btn btn-danger mt-1'])}}
                                        {!!Form::close()!!}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="mt-2">You have no posts!</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
