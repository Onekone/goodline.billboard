@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if($post->image_url!=null)
                        <div class="card-header" style="text-align:center">
                            <img src="{{asset('storage/images/'.$post->image_url)}}"
                                 style="width:100%;max-height:100%;height: auto;">
                        </div>
                    @endif
                    <div class="card-body">
                        [PH] Прислал: {{$post->user->name??'deleted'}}<br>
                        [PH] Дата: {{ date('M j, Y', strtotime($post->created_at)) }}
                    </div>
                    <div class="card-footer">
                        @if(Auth::check() && $post->user_id == Auth::user()->id)
                            {!! Form::open(['method'=>'get','route'=>['ad.edit',$post->id]]) !!}
                            {!! Form::submit('[PH] Редактировать',array('class'=>'form-control btn btn-primary','width'=>'300px'))!!}
                            {!! Form::close() !!}
                        @endif
                            @if(Auth::check() && $post->user_id == Auth::user()->id || Auth::check() && Auth::user()->isModerator == 1)
                                {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$post->id]]) !!}
                            {{  Form::submit('[PH] Удалить',array('class'=>'form-control btn btn-danger','width'=>'300px'))}}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{$post->title}}
                    </div>
                    <div class="card-body">
                        {{$post->content}}
                    </div>
                </div>
                <div class="card-footer">
                    Контактная информация: <br>
                    {{$post->contact}}
                </div>
            </div>
        </div>
    </div>
@stop

