@extends('layouts.app')
<link href="{{ asset('css/billbord.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
                <div class="card">
                    <div class="card-header">
                        {{ $post->title }}
                        <div style="display: inline-block; position: relative; float: right;">
                            <a href="#" data-toggle="tooltip" title="Истекает {{ date('d M, Y', strtotime($post->created_at. ' + 15 days')) }}">{{$post->created_at}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{asset('images/'.$post->image_url)}}" width="200px" height="200px">
                            </div>
                            <div class="col-md-8">
                                {{ $post->content }}
                            </div>

                        </div>

                    </div>

                    <div class="card-footer">
                        <div style='display: inline-block; float:right; width:30%;'>
                            {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$post->id]]) !!}
                            {{  Form::submit('Удалить',array('class'=>'form-control btn btn-danger'))}}
                            {!! Form::close() !!}
                        </div>
                        <div style='display: inline-block; float:right; width:30%;'>
                            {!! Form::open(['method'=>'get','route'=>['ad.edit',$post->id]]) !!}
                            {!! Form::submit('Редактировать',array('class'=>'form-control btn btn-primary'))!!}
                            {!! Form::close() !!}
                        </div>
                        <div style="display: inline-block; float:left; width:30%">
                            {{ App\User::find($post->user_id)->name}}
                        </div>

                    </div>

                </div>
            </div>
        @endforeach

        <div class="block_links">
            {{ $posts->links() }}
        </div>
        <script>
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </div>
@endsection
