@extends('layouts.app')

@section('content')
    <div class="container">
        @if (isset($posts))
            {!!Form::model($posts,['method'=>'PUT','route'=>['ad.update',$posts->id],'file'=>true,'enctype'=>"multipart/form-data"]) !!}
        @else
            {!!Form::open(['method'=>'POST','route'=>['ad.store'],'file'=>true,'enctype'=>"multipart/form-data"]) !!}
        @endif
        @csrf
        <div class="card">
            <div class="card-header">
                @if (isset($posts))
                    <div class="form-group row">
                        {!! Form::label('title','Заголовок',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                        <div class="col-md-10">
                            {!! Form::text('title',old('title'),['class'=> $errors->has('title')?'form-control is-invalid':'form-control','required']) !!}
                            @if ($errors->has('title'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                            @endif
                        </div>

                    </div>
                @else
                    Создание нового объявления
                @endif
            </div>
            <div class="card-body">
                @if (!isset($posts))
                    <div class="form-group row">
                        {!! Form::label('title','Заголовок',['class'=>'col-sm-2 col-form-label text-md-right']) !!}
                        <div class="col-md-10">
                            {!! Form::text('title',old('title'),['class'=> $errors->has('title')?'form-control is-invalid':'form-control','required']) !!}
                            @if ($errors->has('title'))
                                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                            @endif
                        </div>
                    </div>
                @endif
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
                        {!! Form::file('image_url',['class' => $errors->has('image_url')?'is-invalid':'','accept'=>'image/*']) !!}
                        {!! Form::checkbox('delete_image','delete_image') !!}
                        {!! Form::label('delete_image','Удалить изображение') !!}
                        @if ($errors->has('image_url'))
                            <span class="invalid-feedback"><strong>{{ $errors->first('image_url') }}</strong></span>
                        @endif
                        @if (isset($posts))
                            <div style="text-align: center" class="form-control">
                                @if ($posts->image_url != NULL)
                                    <img src="{{asset('images/'.$posts->image_url)}}" class="">
                                @else
                                    <img src="https://ultimatefires.com.au/wp-content/uploads/2018/02/no-image-available.png"
                                         width="128px" height="128px" class="">
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group row">
                    <a href="{{isset($posts)? route('ad.show',$posts->id) : URL::previous()}}"
                       class="form-control col-md-6 btn btn-danger">Отмена</a>
                    {!! Form::submit('Сохранить',['class' => 'form-control col-md-6 btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
