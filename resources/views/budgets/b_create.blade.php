@extends('layouts.app')

<!-- wird zurzeit noch nicht benötigt -->

@section('content')
    <p>create budget</p>
    {!! Form::open(['action' => 'BudgetController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <!-- Form for the title -->
        <div class="form-group">
            {{Form::label('title', 'Title')}}
            {{Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Title'])}}
        </div>
        <!-- Form for the text body, adds the editor-functionality-->
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body', '', ['id' => 'article-ckeditor', 'class' => 'form-control', 'placeholder' => 'Body Text'])}}
        </div>
        <!-- Form for uploading images -->
        <div class="form-group">
            {{Form::file('cover_image')}}
        </div>
        <!-- Submits the form-->
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection