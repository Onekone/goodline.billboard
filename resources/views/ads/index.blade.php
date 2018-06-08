@extends('layouts.app')
<link href="{{ asset('css/billbord.css') }}" rel="stylesheet">
@section('content')
    <div class="container">
        @foreach($posts as $post)

            <div class="ads">
            <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <a href="{{route('ad.show',$post->id)}}">
                            <img src="http://placehold.it/200x200" class="img">
                        </a>
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div><a href="{{route('ad.show',$post->id)}}">{{$post->title}}</a></div>
                        <div>{{$post->content}}</div>
                    </div>
                </div>


            </div>
        @endforeach
            <div class="block_links">
                {{ $posts->links() }}
            </div>
@endsection
