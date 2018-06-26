@extends('layouts.app')
@section('head')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    function refreshCaptchaJSFunction() {
        $.ajax({
            type:'GET',
            url:'{{route('refresh_captcha')}}',
            success:function(data){
                $(".captcha span").html(data.captcha+" <button type=\"button\" class=\"btn btn-success\" onclick=\"refreshCaptchaJSFunction()\" ><img src=\"http://www.clipartbest.com/cliparts/7Ta/Mnb/7TaMnbL7c.png\" width=\"24px\" height=\"24px\"></button> ");
            }
        });
    };

</script>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            @if (session('status') && session('status-class'))
                                <div class="alert {{ session('status-class') }}">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">

                                    <input name="social_id" type="text" value="{{isset($socialOptions) ? $socialOptions['social_id'] : ''}}" hidden>

                                    <input id="name" type="text"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name" value="{{ isset($socialOptions) ? $socialOptions['name'] : old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ isset($socialOptions) ? $socialOptions['email'] : old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>


                            <div class="form-group row">

                                <div class="col-md-4 col-form-label text-md-right captcha" style="padding-top: 0">
                                    <span class="captcha">{!! captcha_img() !!}<button type="button" class="btn btn-success" onclick="refreshCaptchaJSFunction()" ><img src="http://www.clipartbest.com/cliparts/7Ta/Mnb/7TaMnbL7c.png" width="24px" height="24px"></button></span>


                                </div>
                                <div class="col-md-6">

                                    <input id="captcha" type="text" class="form-control {{ $errors->has('captcha') ? ' is-invalid' : '' }}" placeholder="Enter Captcha" name="captcha" required>
                                    @if ($errors->has('captcha'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('captcha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">

                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="align-content-md-center">

                            <a class="btn btn-outline-info" href="{{route("vk")}}"><img src="https://maxcdn.icons8.com/Share/icon/Logos/vk_com1600.png" width="24px" height="24px"> VK </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('afterbody')

@endsection
