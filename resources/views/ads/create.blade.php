@extends('layouts.app')

@section('content')
    <div class="container">
        @if (isset($posts))
            {!!Form::model($posts,['method'=>'POST','route'=>['ad.update',$posts->id],'file'=>true,'enctype'=>"multipart/form-data"]) !!}
        @else
            {!!Form::open(['method'=>'POST','route'=>['ad.store'],'file'=>true,'enctype'=>"multipart/form-data"]) !!}
        @endif
        @csrf
        <div class="card">

            <div class="card-body">

                <div class="form-group row">
                    {!! Form::label('title','Заголовок',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                    <div class="col-md-10">
                        {!! Form::text('title',old('title'),['class'=> $errors->has('title')?'form-control is-invalid':'form-control','required']) !!}
                        @if ($errors->has('title'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                        @endif
                    </div>

                </div>

                <div class="form-group row">
                    {!! Form::label('content','Текст объяления',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                    <div class="col-md-10">
                    {!! Form::textarea('content',old('content'),['class'=> $errors->has('content')?'form-control is-invalid':'form-control','required','rows'=>'7']) !!}
                        @if ($errors->has('content'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('content') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    {!! Form::label('contact','Контакт',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                    <div class="col-md-10">
                    {!! Form::textarea('contact',old('contact'),['class'=> $errors->has('contact')?'form-control is-invalid':'form-control','required','rows'=>'5']) !!}
                    @if ($errors->has('contact'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('contact') }}</strong></span>
                    @endif
                </div>

                </div>

                <div class="form-group row">
                    {!! Form::label('image_url','Изображение',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                    <div class="col-md-10">
                    {!! Form::file('image_url',['class' => $errors->has('image_url')?'form-control is-invalid':'form-control','accept'=>'image/*']) !!}
                    @if ($errors->has('image_url'))
                        <span class="invalid-feedback"><strong>{{ $errors->first('image_url') }}</strong></span>
                    @endif
                        @if (isset($model))
                            <img src="{{$posts->image_url}}">
                            @endif
                </div>

                </div>

                <div class="form-group row">
                    {!! Form::submit('Сохранить',['class' => 'form-control col-md-12 btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@endsection
