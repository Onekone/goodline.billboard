@extends('layouts.app')

@section('content')
    <div class="container">
        {!! Form::open(['method'=>'put','route'=>['ad.update',$post->id]]) !!}
        <h1>{{Form::label('title',"Заголовок")}}</h1>
        {{Form::text('title',$post->title)}}<br>
        <h1>{{Form::label('content',"Текст")}}</h1>
        {{Form::textarea('content',$post->content)}}<br>
        {{Form::submit('Отправить')}}
        {!! Form::close() !!}
    </div>
@endsection
