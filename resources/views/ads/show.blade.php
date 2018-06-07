@extends('layouts.app')
<link href="{{ asset('css/billboard.css') }}" rel="stylesheet">
@section('content')
<div class="container">
    <div class="information">
        <span class="ads">
            <img src="http://placehold.it/200x200" class="img" >
            {{$post->title}}
            {{$post->content}}
        </span>


        @if(Auth::check() && Auth:: user()->status)
            {!! Form::open(['method'=>'delete','route'=>['articles.destroy',$post->id]]) !!}
            {{Form::submit('Удалить')}}
            {!! Form::close() !!}
            {!! Form::open(['method'=>'get','route'=>['articles.edit',$post->id]]) !!}
            {!!Form::submit('Редактироать')!!}
            {!! Form::close() !!}
        @endif
    </div>
</div>
@stop
