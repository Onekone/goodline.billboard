@extends('layouts.app')
@section('content')

    <div class="container">

        <form action="{{route('ad.search')}}" method="GET" class="search-simple">
            <div class="form-group row">
                <input type="text" class="form-control col-md-8" name="query" value="{{ old('query')??request('query') }}">
                <input class="btn btn-primary form-control col-md-2" type="submit" value="Искать">
                <button type="button" data-toggle="modal" data-target="#modalSearchHelp" class="btn btn-secondary col-md-2">Помощь по поиску</button>

            </div>
        </form>

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
    <div id="modalSearchHelp" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action="{{route('ad.search')}}" method="GET" class="search-simple">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Настройки аккаунта</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="query" value="{{ old('query')??request('query') }}"> <br>
                    BLAH BLAH BLAH BLAH BLAH
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success form-control" data-dismiss="modal">Отмена</button>
                    <input class="btn btn-primary form-control" type="submit" value="Искать">
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
