@extends('layouts.app')
<link href="{{ asset('css/billbord.css') }}" rel="stylesheet">
@section('content')
    <div class="container">
        <div>
            <form>
                <button>
                    <a href="{{route('ad.create')}}">Новое объявление</a>
                </button>
            </form>
        </div>
        @foreach($posts as $post)

            <div class="ads">
                <div class="row">
                    @if($post->image_url!=null)
                        <div class="col-lg-3 col-md-3 col-sm-12">
                            <a href="{{route('ad.show',$post->id)}}">
                                <img src="{{asset('images/'.$post->image_url)}}" width="200" height="200">
                            </a>
                        </div>
                    @endif
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
