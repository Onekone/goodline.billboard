@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">


        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="text-align:center">
                    <img src="http://placehold.it/200x200">
                </div>
                <div class="card-body">
                    [PH] Прислал: {{$username}}</br>
                    [PH] Дата: {{ date('M j, Y', strtotime($post->created_at)) }}
                </div>
                <div class="card-footer">


                {!! Form::open(['method'=>'get','route'=>['ad.edit',$post->id]]) !!}
                    {!! Form::submit('[PH] Редактировать',array('class'=>'form-control btn btn-primary','width'=>'300px'))!!}
                {!! Form::close() !!}

                {!! Form::open(['method'=>'delete','route'=>['ad.destroy',$post->id]]) !!}
                    {{  Form::submit('[PH] Удалить',array('class'=>'form-control btn btn-danger','width'=>'300px'))}}
                {!! Form::close() !!}
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


        </div>

    </div>
</div>
@stop
@if(Auth::check() && Auth:: user()->status)
@endif