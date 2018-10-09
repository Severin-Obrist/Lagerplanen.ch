@extends('layouts.app')

<!-- Wird nicht gebraucht -->

<!-- Form to edit a post -->

@section('content')
    <h1>Edit Posts</h1>
    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <!-- Title field -->
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', $post->title, ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <!-- Text body field-->
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body', $post->body, ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
        </div>
        <!-- image upload field -->
        <div class="form-group">
                {{Form::file('cover_image')}}
        </div>
        <!-- submits the Form and updates the Post -->
        {{Form::hidden('_method','PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection