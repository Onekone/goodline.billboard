@extends('layouts.app')

@section('content')
    <div class="container">
        {!! Form::open(['method'=>'post','route'=>'ad.store']) !!}
        <h1>{{Form::label('title',"Заголовок")}}</h1>
        {{Form::text('title')}}<br>
        <h1>{{Form::label('content',"Текст")}}</h1>
        {{Form::textarea('content')}}<br>
        {{Form::submit('Отправить')}}
        {!! Form::close() !!}
    </div>
@endsection
