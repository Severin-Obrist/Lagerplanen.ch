@extends('layouts.app')


<!-- Wird nicht gebraucht -->

<!-- Lists all existing posts with the coverimage-->

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card card-body bg-light mb-3">
                <div class="row">
                    <!-- displays the coverimage and the post next to each other-->
                    <div class="col-md-4 col-sm-4">
                        <img style="width:100%" src="/storage/cover_images/{{$post->cover_image}}">
                    </div>
                    <div class="col-md-8 col-sm-8">
                        <h3 class="card-title"><a href="/posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small class="card-text">Written on {{$post->created_at}} by {{$post->user->name}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        {{$posts->links()}}
    @else
        <p>No Posts found</p>
    @endif
@endsection