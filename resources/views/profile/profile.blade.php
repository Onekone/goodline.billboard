@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            E-mail:<br>{{$user->email}}

                            @if ($auth && $auth==$user->id)
                                @if ($vkLink)
                                    <a data-toggle="tooltip" title="Аккаунт связан с ВК"><img src="https://maxcdn.icons8.com/Share/icon/Logos/vk_com1600.png" width="24px" height="24px"></a>
                                @elseif ($user->verified == 1)
                                    <img src = "https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Emojione_2705.svg/1024px-Emojione_2705.svg.png" width="24px" height="24px">
                                @else
                                    <a href="{{route("password.request")}}" data-toggle="tooltip" title="[PH] Необходимо подтвердить адрес электронной почты. Отправить запрос?"><img src = "https://emojipedia-us.s3.amazonaws.com/thumbs/120/twitter/141/warning-sign_26a0.png" width="24px" height="24px"></a>
                                @endif
                            @endif
                        </p>
                        <p>
                            Зарегистировался:<br>{{$user->created_at}}
                        </p>

                        @if($auth && $auth==$user->id)<hr>
                        {!! Form::model($user, ['route' => ['user',$user->id], $user->id, 'method' => 'PUT', 'name' => 'editForm']) !!}
                        [PH] Отображаемое имя:
                        <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{$user->name}}" required> <br>

                        [PH] Адрес электронной почты:
                        <input id="useremail" type="email" class="form-control{{ $errors->has('useremail') ? ' is-invalid' : '' }}" name="useremail" value="{{$user->email}}" required> <br>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif

                        @if($user->password=="")
                        [PH] У вас нет пароля.<br>
                        <input id="changePassQuestion" name="changePassQuestion" value="changePasswordQuestion" type="checkbox"> Задать пароль?
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="" hidden>
                        <input id="password_new" type="password" class="form-control{{ $errors->has('password_new') ? ' is-invalid' : '' }}" name="password_new"> <hr>
                        @else
                        [PH] Текущий пароль:
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required> <br>

                        <input id="changePassQuestion" name="changePassQuestion" value="changePasswordQuestion" type="checkbox"> [PH] Поменять пароль?
                        <input id="password_new" type="password" class="form-control{{ $errors->has('password_new') ? ' is-invalid' : '' }}" name="password_new"> <hr>
                        @endif

                        @if($vkLink)
                        <a href="http://vk.com/id{{$vkLink->social_id}}" class="btn btn-outline-info form-control" >[PH] Профиль ВК</a>
                        <a href="#" data-toggle="modal" data-target="#modalUnbindVK" class="btn btn-info form-control">[PH] Отвязать от ВК</a>
                        <hr>
                        @else
                            <a href="{{route('vk')}}" class="btn btn-info form-control" data-toggle="tooltip" title="[PH] Это позволит вам использовать аккаунт в контакте для авторизации на сайте вместо логина/пароля. (WIP: после изначальной привязки id ВК к id пользователя не тянет больше никаких данных)">[PH] Связать с ВК</a>
                            <hr>
                        @endif
                        <a href="#" data-toggle="modal" data-target="#modalNukeAds" class="btn btn-outline-danger form-control">[PH] Удалить все объявления</a>
                        <a href="#" data-toggle="modal" data-target="#modalNukeUser" class="btn btn-outline-danger form-control">[PH] Удалить аккаунт</a>
                        <hr>
                        <button type="submit" class = "btn btn-success form-control">[PH] Сохранить изменения</button> <br>
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
                                <a data-toggle="tooltip" title="[PH] Модератор"><img src = "https://image.freepik.com/free-icon/star-ios-7-symbol_318-35526.jpg" width="24px" height="24px"></a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert {{ session('status-class')}}">
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
                                            <a data-toggle="tooltip" title="Истекает {{ date('d M, Y', strtotime($userpost->created_at. ' + 15 days')) }}">{{$userpost->created_at}}</a>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @if($userpost->image_url!=null)
                                            <div class="col-md-4">
                                                <img src="{{asset('images/'.$userpost->image_url)}}" width="200" height="200">
                                            </div>
                                            @endif
                                            <div class="col-md-8">
                                                {{ $userpost->content }}
                                            </div>

                                        </div>

                                    </div>
                                    @if ($auth && $auth==$user->id)
                                    <div class="card-footer">
                                        <div style='display: inline-block; float:left; width:30%; text-align: center'>
                                            <a data-toggle="tooltip" title="[PH] Истекает {{ date('d M, Y', strtotime($userpost->created_at. ' + 15 days')) }}">{{$userpost->created_at}}</a>
                                        </div>
                                        <div style='display: inline-block; float:right; width:30%;'>
                                            {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$userpost->id]]) !!}
                                            {{  Form::submit('[PH] Удалить',array('class'=>'form-control btn btn-danger'))}}
                                            {!! Form::close() !!}
                                        </div>
                                        <div style='display: inline-block; float:right; width:30%;'>
                                            {!! Form::open(['method'=>'get','route'=>['ad.edit',$userpost->id]]) !!}
                                            {!! Form::submit('[PH] Редактировать',array('class'=>'form-control btn btn-primary'))!!}
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
                    Аккаунт будет безвозвратно удален. Это действие <b>невозможно</b> отменить.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success form-control" data-dismiss="modal">Отмена</button>
                    <a href="{{route('user.destroy',$user->id)}}" class="btn btn-danger form-control">Удалить</a>
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
                    Это действие <b>невозможно</b> отменить. Вы уверены, что хотите удалить все свои объявления?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success form-control" data-dismiss="modal">Отмена</button>
                    <a href="{{route('user.clear',$user->id)}}" class="btn btn-danger form-control">Удалить</a>
                </div>
            </div>

        </div>
    </div>
    <div id="modalUnbindVK" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Настройки аккаунта</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @if($user->password != "" && $user->email != "")
                        Привязанный профиль ВК будет отвязан от этого аккаунта. Перед продолжением, убедитесь в том, что вы правильно настроили адрес электронной почты и пароль.
                    @else
                        Привязанный профиль ВК будет отвязан от этого аккаунта. Перед продолжением, вам необходимо настроить пароль и адрес электронной почты.
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success form-control" data-dismiss="modal">Отмена</button>
                    @if($user->password != "" && $user->email != "")
                        <a href="{{route('user.unbindVK',$user->id)}}" class="btn btn-danger form-control">Удалить</a>
                    @else
                        <button type="button" class="btn btn-dark form-control" data-dismiss="modal">Удалить</button>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
