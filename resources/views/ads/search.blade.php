@extends('layouts.app')
@section('content')
    <div class="container">
        @foreach($ads as $ad)
            <div class="ads">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('ad.show',$ad->id)}}">
                            {{ $ad->title }}
                        </a>
                        <div style="display: inline-block; position: relative; float: right;">
                            <a style="text-decoration: none;color: inherit;" href="{{route('ad.show',$ad->id)}}"
                               data-toggle="tooltip"
                               title="[PH] Истекает {{ date('d M, Y', strtotime($ad->created_at. ' + 15 days')) }}">{{date('d M, Y', strtotime($ad->created_at))}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($ad->image_url!=null)
                                <div class="col-md-4">
                                    <a href="{{route('ad.show',$ad->id)}}">
                                        <img src="{{asset('images/'.$ad->image_url)}}" width="200px" height="200px">
                                    </a>
                                </div>
                            @endif
                            <div class="col-md-8">
                                {{ $ad->content }}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div style="display: inline-block; float:left; width:30%">
                            @if (App\User::find($ad->user_id))
                                <a href="{{route("user",$ad->user_id)}}">{{App\User::find($ad->user_id)->name}}</a>
                            @else
                                deleted
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="block_links">
            {{--{{$ads->appends(['q' => \Illuminate\Support\Facades\Input::get('q')])->links()}}--}}
        </div>
@endsection