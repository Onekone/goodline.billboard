@extends('layouts.app')
@section('title',$post->title)
@section('content')
    <div class="container">
        @if (session('status') && session('status-class'))
            <div class="alert {{ session('status-class') }}">
                {{ session('status') }}
            </div>
        @endif
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
                        Прислал: {{$post->user->name??'deleted'}}<br>
                        Дата: {{ date('d.m.Y', strtotime($post->created_at)) }}
                    </div>
                    <div class="card-footer">
                        @if(Auth::check() && $post->user_id == Auth::user()->id)
                            <a href="{{route('ad.edit', $post->id)}}" class="btn btn-primary">Редактировать</a>
                        @endif
                        @if(Auth::check() && $post->user_id == Auth::user()->id || Auth::check() && Auth::user()->isModerator == 1)
                                <a data-toggle="modal" data-target="#modalDeleteAds"
                                   class="btn btn-danger text-white" >Удалить</a>
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
                        {!! nl2br($post->content) !!}
                    </div>
                </div>
                <div class="card-footer">
                    Контактная информация: <br>
                    {!! nl2br($post->contact) !!}
                </div>
            </div>
        </div>
    </div>

    <div id="modalDeleteAds" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Удалить обьявление</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите удалить обьявление?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Отмена</button>
                    <form action="{{route('ad.destroy', $post->id)}}" method="post">
                        {{method_field('delete')}}
                        <button type="submit" class="btn btn-primary">Удалить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

