@extends('layouts.app')

@section('content')
    <div class="container">
        {!!Form::open(['method'=>'POST','route'=>['ad.store'],'file'=>true, 'enctype'=>"multipart/form-data"])!!}
        <div class="card">
            <div class="card-body">
                <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Заголовок</span>
                </div>
                <input id="title" type="text" class="form-control" name="title" required> <br>
                </div><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Информация о товаре</span>
                    </div>
                    <textarea class="form-control" aria-label="With textarea" id="content" name="content" rows="7"></textarea>
                </div><br>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Контактная информация</span>
                    </div>
                    <textarea class="form-control" aria-label="With textarea" id="contact" name="contact" rows="5"></textarea>
                </div><br>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile02"  name="image_url">
                        <label class="custom-file-label" for="inputGroupFile02">Выберите файл</label>
                    </div>
                </div><br>

                <div class="input-group-append">
                        <button type="submit" class="input-group-text">Сохранить</button> <br>
                </div>

            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
