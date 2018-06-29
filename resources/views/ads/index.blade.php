@extends('layouts.app')
@section('content')

    <div class="container">
        {{--<form action="{{route('searchSimple')}}" method="GET" class="search-simple">--}}
            {{--<div class="row">--}}
                {{--<div class="col-xs-10">--}}
                    {{--<div class="form-group">--}}
                        {{--<input type="text" class="form-control" name="q" value="{{ old('q') }}" required>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-xs-2">--}}
                    {{--<div class="form-group">--}}
                        {{--<input class="btn btn-info" type="submit" value="Искать">--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</form>--}}

        @if (session('status') && session('status-class'))
            <div class="alert {{ session('status-class') }}">
                {{ session('status') }}
            </div>
        @endif
        @if (Auth::user())
            <a href="{{route('ad.create')}}" class="btn btn-primary form-control">Создать новое объявление</a>
        @endif
        @foreach($posts as $post)
            <div class="ads">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('ad.show',$post->id)}}">
                            {{ $post->title }}
                        </a>
                        <div style="display: inline-block; position: relative; float: right;">
                            <a style="text-decoration: none;color: inherit;" href="{{route('ad.show',$post->id)}}"
                               data-toggle="tooltip"
                               title="[PH] Истекает {{ date('d M, Y', strtotime($post->created_at. ' + 15 days')) }}">{{date('d M, Y', strtotime($post->created_at))}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($post->image_url!=null)
                                <div class="col-md-4">
                                    <a href="{{route('ad.show',$post->id)}}">
                                        <img src="{{asset('storage/images/'.$post->image_url)}}" style="width:100%;max-height:100%;height: auto;">
                                    </a>
                                </div>
                            @endif
                            <div class="col-md-8">
                                {{ $post->content }}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div style="display: inline-block; float:left; width:30%">
                            @if (App\User::find($post->user_id))
                            <a href="{{route("user",$post->user_id)}}">{{App\User::find($post->user_id)->name}}</a>
                            @else
                                deleted
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="block_links">
            {{ $posts->links() }}
        </div>

        <script>
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </div>
@endsection
