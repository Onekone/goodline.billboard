@extends('layouts.app')

@section('content')
    <div class="container">
        {!!Form::open(['method'=>'POST','route'=>['ad.store'],'file'=>true, 'enctype'=>"multipart/form-data"])!!}
        <h1>{{Form::label('title',"Заголовок")}}</h1>
        {{Form::text('title')}}<br>
        <h1>{{Form::label('content',"Текст")}}</h1>
        {{Form::textarea('content')}}<br>
        <h1>{{Form::label('contact',"Контактная информация")}}</h1>
        {{Form::text('contact')}}<br>
        <h1>{{Form::label('image_url',"Картинка")}}</h1>
        {{Form::file('image_url')}}<br><br>
        {{Form::submit('Отправить')}}
        {!! Form::close() !!}
    </div>
@endsection
