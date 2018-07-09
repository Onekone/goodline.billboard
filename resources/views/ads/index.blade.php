@extends('layouts.app')
@section('title','Типа.Билборд')
@section('content')

    <div class="container">

        <form action="{{route('ad.search')}}" method="GET" class="search-simple">
            <div class="form-group header-search-bar">
                <div class="full-width">
                    <input type="text" class="form-control" name="query"
                           value="{{ old('query')??request('query') }}" required>
                </div>
                <div class="header-search-bar header-right-side p-x-10">
                        <button type="submit" class="btn full-width btn-warning"><img
                                    src="http://www.clker.com/cliparts/n/U/H/1/H/u/search-icon-white-one-hi.png"
                                    width="20px" height="20px"></button>


                        <button type="button" data-toggle="modal" data-target="#modalSearchHelp"
                                class="btn full-width btn-link">
                            <a style="text-decoration: none;color: inherit;"
                               data-toggle="tooltip"
                               title="Помощь по поиску"><img
                                        src="https://im0-tub-ru.yandex.net/i?id=14fdd4ff95e37219ac8d40d95802c1dc&n=13"
                                        width="24px" height="24px">
                            </a>
                        </button>
                </div>
            </div>
        </form>

        @if (session('status') && session('status-class'))
            <div class="alert {{ session('status-class') }}">
                {{ session('status') }}
            </div>
        @endif
        @if (Auth::user())
            <div class="text-center">
                    <a href="{{route('ad.create')}}" class="btn btn-warning form-control text-white col-md-4">Создать новое
                        объявление</a>
            </div>
        @endif
        @if($posts->count()==0)
            <div style="display: block; margin: auto">
                <img src="{{asset('/images/background/background.png')}}" style="display: block; margin: auto">
                <p style="text-align: center; margin: auto">Тут что-то должно быть, но не в этот раз</p>
            </div>

        @endif
        @foreach($posts as $post)
            <div class="ads">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('ad.show',$post->id)}}">
                            {{ $post->title }}
                        </a>
                        <div style="display: inline-block; position: relative; float: right;">
                            <a style="text-decoration: none;color: inherit;" href="{{route('ad.show',$post->id)}}"
                               data-toggle="tooltip"
                               title="Истекает {{ date('d.m.Y', strtotime($post->created_at. ' + 15 days')) }}">{{date('d.m.Y', strtotime($post->created_at))}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($post->image_url!=null)
                                <div class="col-md-4">
                                    <a href="{{route('ad.show',$post->id)}}">
                                        <img src="{{asset('storage/images/'.$post->image_url)}}"
                                             style="width:100%;max-height:100%;height: auto;">
                                    </a>
                                </div>
                            @endif
                            <div class="col-md-8">
                                {!! nl2br($post->content) !!}
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div style="display: inline-block; float:left; width:30%">
                            @if (App\User::find($post->user_id))
                                <a href="{{route("user",$post->user_id)}}">{{App\User::find($post->user_id)->name}}</a>
                            @else
                                deleted
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="block_links flex-center">
            {{ $posts->links() }}
        </div>
    </div>
    <div id="modalSearchHelp" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->

            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title">Помощь по поиску</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('ad.search')}}" method="GET" class="search-simple">
                        <div class="row">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="query" id="query"
                                       value="{{ old('query')??request('query') }}" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="form-control btn btn-warning"><img
                                            src="http://www.clker.com/cliparts/n/U/H/1/H/u/search-icon-white-one-hi.png"
                                            width="20px" height="20px"></button>
                            </div>
                            <br>
                        </div>

                    </form>
                    <hr>
                    Операции с поиском <br>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-4"><a href="#" id="logicAnd" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="A B"
                                                     data-content="По умолчанию, введенные слова считаются за отдельные элементы поиска, и в базе данных ищутся все записи, в которой есть все эти слова в любом порядке">Логическое
                                    И</a></div>
                            <div class="col-md-4"><a href="#" id="logicOr" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="A | B"
                                                     data-content="Поиск будет искать наличие как минимум одного из этих элементов (либо A, либо B)">Логическое
                                    ИЛИ</a></div>
                            <div class="col-md-4"><a href="#" id="logicNot" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="-A"
                                                     data-content="Поиск будет искать объявления, в которых нет этого элемента">Логическое
                                    НЕ</a></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><a href="#" id="logicGroup" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="(A B C)"
                                                     data-content="Элементы будут сгруппированны вместе. Операции можно применять как внутри группы, так и к самой группе">Группировка</a>
                            </div>
                            <div class="col-md-4"><a href="#" id="logicPhrase" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title='"A B C"'
                                                     data-content="Элементы поиска можно заключить в кавычки. Так несколько слов можно искать вместе как фразу">Поиск
                                    по фразе</a></div>
                            <div class="col-md-4"></div>

                        </div>
                        <div class="row">
                        </div>
                    </div>


                    <hr>
                    Поиск по полям <br>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4"><a href="#" id="fieldTitle" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="@title A"
                                                     data-content="Поиск будет проводиться по выбранному полю">Заголовок</a>
                            </div>
                            <div class="col-md-4"><a href="#" id="fieldContent" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="@content A"
                                                     data-content="Поиск будет проводиться по выбранному полю">Содержимое</a>
                            </div>
                            <div class="col-md-4"><a href="#" id="fieldContact" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="@contact A"
                                                     data-content="Поиск будет проводиться по выбранному полю">Контактная
                                    информация</a></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><a href="#" id="fieldSpecific" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="@(поле,поле) текст"
                                                     data-content="Поля, по которым происходит поиск, можно объединить в группы точно также, как и слова поиска">Несколько
                                    полей</a></div>
                            <div class="col-md-4"><a href="#" id="fieldIgnore" class="btn-link" data-toggle="popover"
                                                     data-trigger="hover" title="@!(поле) текст"
                                                     data-content="Совпадения в указанном поле будут проигнорированы">Игнорировать
                                    поле</a></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">


                </div>
            </div>

        </div>
    </div>
    <script>
        $(function () {

            // Stack Overflow - PhearOfRayne
            // https://stackoverflow.com/questions/13941055/add-a-string-of-text-into-an-input-field-when-user-clicks-a-button

            $('#logicAnd').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' A B');
            });
            $('#logicOr').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' A | B');
            });
            $('#logicNot').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' -A');
            });
            $('#logicGroup').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' (A B C)');
            });
            $('#logicPhrase').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' "A B C"');
            });

            $('#fieldTitle').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' @title (A)');
            });
            $('#fieldContent').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' @content (A)');
            });
            $('#fieldContact').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' @contact (A)');
            });

            $('#fieldSpecific').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' @(поле,поле) (текст)');
            });
            $('#fieldIgnore').on('click', function () {
                var text = $('#query');
                text.val(text.val() + ' @!(поле) (текст)');
            });
        });

        $(document).ready(function () {
            $('[data-toggle="popover"]').popover();
        });

    </script>
@endsection
