@extends('layouts.app')
<link href="{{ asset('css/billboard.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            E-mail:<br>{{$user->email}}
                            @if ($auth && $auth==$user->id)
                                @if ($user->verified != NULL)
                                    <img src = "https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Emojione_2705.svg/1024px-Emojione_2705.svg.png" width="24px" height="24px">
                                @else
                                    <a href="{{route("password.request")}}" data-toggle="tooltip" title="Необходимо подтвердить адрес электронной почты. Отправить запрос?"><img src = "https://emojipedia-us.s3.amazonaws.com/thumbs/120/twitter/141/warning-sign_26a0.png" width="24px" height="24px"></a>
                                @endif
                            @endif
                        </p>
                        <p>
                            Зарегистировался:<br>{{$user->created_at}}
                        </p>

                        @if($auth && $auth==$user->id)<hr>
                        {!! Form::model($user, ['route' => ['user',$user->id], $user->id, 'method' => 'PUT', 'name' => 'editForm']) !!}
                        Отображаемое имя:
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value={{$user->name}} required> <br>

                        Адрес электронной почты:
                        <input id="useremail" type="email" class="form-control{{ $errors->has('useremail') ? ' is-invalid' : '' }}" name="useremail" value={{$user->email}} required> <br>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                        Текущий пароль:
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required> <br>

                        <input id="changePassQuestion" name="changePassQuestion" value="changePasswordQuestion" type="checkbox"> Поменять пароль?
                        <input id="password_new" type="password" class="form-control{{ $errors->has('password_new') ? ' is-invalid' : '' }}" name="password_new"> <br>

                        <button type="submit" class = "btn btn-success form-control">[PH] Сохранить изменения</button> <br>
                        <a href="#" class="btn btn-outline-danger form-control">[PH] Удалить все объявления</a>
                        <a href="#" class="btn btn-danger form-control">[PH] Удалить аккаунт</a>

                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> {{ $user->name }}
                        <div style="display: inline-block; position: relative; float: right;">
                            @if ($user->isModerator == true)
                                <a href="#" data-toggle="tooltip" title="Модератор"><img src = "https://image.freepik.com/free-icon/star-ios-7-symbol_318-35526.jpg" width="24px" height="24px"></a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (defined('message'))
                            <div class="alert alert-warning">
                                {{ $message }}
                            </div>
                        @endif

                        @foreach($posts as $userpost)
                                <div class="card">
                                    <div class="card-header">
                                        {{ $userpost->title }}
                                        @if (!$auth || $auth!=$user->id)
                                        <div style="display: inline-block; position: relative; float: right;">
                                            <a href="#" data-toggle="tooltip" title="Истекает {{ date('d M, Y', strtotime($userpost->created_at. ' + 15 days')) }}">{{$userpost->created_at}}</a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{ $userpost->image_url }}" width="200px" height="200px">
                                            </div>
                                            <div class="col-md-8">
                                                {{ $userpost->content }}
                                            </div>

                                        </div>

                                    </div>
                                    @if ($auth && $auth==$user->id)
                                    <div class="card-footer">
                                        <div style='display: inline-block; float:left; width:30%; text-align: center'>
                                            <a href="#" data-toggle="tooltip" title="Истекает {{ date('d M, Y', strtotime($userpost->created_at. ' + 15 days')) }}">{{$userpost->created_at}}</a>
                                        </div>
                                        <div style='display: inline-block; float:right; width:30%;'>
                                            {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$userpost->id]]) !!}
                                            {{  Form::submit('Удалить',array('class'=>'form-control btn btn-danger'))}}
                                            {!! Form::close() !!}
                                        </div>
                                        <div style='display: inline-block; float:right; width:30%;'>
                                            {!! Form::open(['method'=>'get','route'=>['ad.edit',$userpost->id]]) !!}
                                            {!! Form::submit('Редактировать',array('class'=>'form-control btn btn-primary'))!!}
                                            {!! Form::close() !!}
                                        </div>

                                    </div>
                                    @endif
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <div id="modalNukeUser" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Вопрос</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Это действие **невозможно** отменить.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default form-control" data-dismiss="modal">Закрыть</button>

                </div>
            </div>

        </div>
    </div>
    <div id="modalNukeAds" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Настройки аккаунта</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Это действие **невозможно** отменить. Вы уверены, что хотите удалить все свои объявления?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default form-control" data-dismiss="modal">Закрыть</button>


                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
@endsection
