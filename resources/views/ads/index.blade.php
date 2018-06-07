@extends('layouts.app')
<link href="{{ asset('css/billbord.css') }}" rel="stylesheet">
@section('content')
    <div class="container">
        @foreach($posts as $post)
            <span class="ads">
            <a href="{{route('ad.show',$post->id)}}">
                <img src="http://placehold.it/200x200" class="img" >
            {{$post->title}}
            </a> <br>
                {{$post->content}}
            </span> <br>
        @endforeach
           {{ $posts->links() }}
    </div>





@endsection
