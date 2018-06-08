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
                        <ul>
                            <li><a href="{{route("password.request")}}">Поменять пароль</a></li>
                            <li><a href="{{route("password.request")}}">Поменять электронную почту</a></li>
                            <li><a href="{{route("password.request")}}">Поменять имя</a></li>
                        </ul>
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
@endsection
