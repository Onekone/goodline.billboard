@extends('layouts.app')
<link href="{{ asset('css/billboard.css') }}" rel="stylesheet">
@section('content')
    <div class="container">
        <div class="information">
            <div class="ads">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <img src="http://placehold.it/200x200" class="img">
                    </div>
                    <div class="col-lg-9 col-md-9 col-sm-12">
                        <div>{{$post->title}}</div>
                        <div>{{$post->content}}</div>
                    </div>
                </div>
            </div>
        </div><br>
            @if(Auth::check())
                {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$post->id]]) !!}
                {{Form::submit('Удалить')}}
                {!! Form::close() !!}
                {!! Form::open(['method'=>'get','route'=>['ad.edit',$post->id]]) !!}
                {!!Form::submit('Редактироать')!!}
                {!! Form::close() !!}
            @endif
    </div>
@stop
