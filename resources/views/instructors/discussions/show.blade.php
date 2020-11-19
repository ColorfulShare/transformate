@extends('layouts.instructor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    @if (Session::has('msj-exitoso'))
        <div id="note">
            {{ Session::get('msj-exitoso') }}
            <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
        </div>
    @endif
    
    <div class="discussions">
       <div class="uk-container">
            <div class="uk-grid">
                {{-- Sección Izquierda --}}
                <div class="uk-width-2-3">
                    <article class="paper">
                        <header class="paper__header paper__header--topic">
                            <h1><a href="{{ route('instructors.discussions.show', [$discusion->slug, $discusion->id]) }}">{{ $discusion->title }}</a></h1>
                            <p class="meta">
                                <i class="fa fa-post"></i>
                                <span>publicado el {{ date('d-m-Y H:i A', strtotime("$discusion->created_at -5 Hours")) }}</span>
                                <a class="link-secondary" href="#last_comment">
                                    &nbsp;<span class="uk-text-right"><i class="fa fa-comment i-space-right"></i>{{ $discusion->comments_count }} comentarios</span>
                                </a>
                            </p>
                        </header>
                        <div class="paper__body">
                            <div class="text-body-bigger">
                                <div class="text-item">{!! $discusion->comment !!}</div>
                            </div>
                            <div class="user-tools d-flex align-items-end flex-wrap">
                                <div class="btn-toolbar flex-grow-1" id="t-topic-tools">
                                    <a class="btn btn-secondary btn-inverse" href="#new_comment">
                                        <i class="fa fa-comment"></i> Comentar
                                        <i class="to-right">→</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <div class="anchor" id="comments">
                        <div>
                            <h2>Comentarios</h2>
                        </div>
                        <ul class="comments-list" id="last_comment">
                            @if ($discusion->comments_count > 0)
                                @foreach ($discusion->comments as $comentario)
                                    <li class="comment-unit">
                                        <div>
                                            <div class="comment-unit__head">
                                                <h4>{{ $comentario->user->names }} {{ $comentario->user->last_names }}</h4>
                                                <div class="comment-unit__time">
                                                    <a class="link-secondary" href="#">
                                                        <i class="fa fa-comment"></i>
                                                            &nbsp;<span class="dateposted">{{ date('d-m-Y H:i A', strtotime("$comentario->created_at -5 Hours")) }}</span>
                                                    </a>
                                                </div>
                                                <a href="#">
                                                    <span class="avatar avatar--s">
                                                        <img width="32" height="32" src="{{ asset('uploads/images/users/'.$comentario->user->avatar) }}">
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="comment-unit__body text-body-bigger">
                                                <div class="text-item">{!! $comentario->comment !!}</div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                No hay comentarios aún...
                            @endif
                        </ul>
                    </div>
                    <div id="new_comment">
                        <div class="comment-unit comment-unit--you">
                            <div class="comment-unit__head">
                                <h4>Tu comentario</h4>
                                <a href="/es/luisanaelenamarin">
                                    <span class="avatar avatar--s">
                                        <img width="32" height="32" src="{{ asset('uploads/images/users/'.Auth::user()->avatar) }}">
                                    </span>
                                </a>
                            </div>
                             <form action="{{ route('instructors.discussions.store-comment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="discussion_id" value="{{ $discusion->id }}">
                                <div class="content-items-wrapper">
                                    <div class="content-item content-item--text">
                                        <textarea class="ckeditor" rows="5" name="comment" placeholder="Comentario..."></textarea>
                                        <script>
                                            ClassicEditor
                                                .create( document.querySelector( '.ckeditor' ) )
                                                .catch( error => {
                                                    console.error( error );
                                                } );
                                        </script>                    
                                    </div>
                                    <div class="btn-toolbar flex-column">
                                        <button type="submit" class="btn btn-primary">
                                            Publicar comentario
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Sección Derecha --}}
                <div class="uk-width-1-3">
                    <aside>
                        <div class="block">
                            <h5>Iniciado por</h5>
                            <div class="user-item user-badge user-badge--xl user-badge--vertical">
                                <span class="avatar avatar--xl">
                                    <img width="96" height="96" src="{{ asset('uploads/images/users/'.$discusion->user->avatar) }}">
                                </span>
                                <strong class="nickname">{{ $discusion->user->names }} {{ $discusion->user->last_names }}</strong>
                                <div class="since">
                                    En Transfórmate desde {{ date('d-m-Y H:i A', strtotime("$discusion->user->created_at -5 Hours")) }}
                                </div>
                                @if ( (!is_null($discusion->user->state)) && (!is_null($discusion->user->country)) )
                                    <div class="location">
                                        <i class="fa fa-map-marker"></i> {{ $discusion->user->state }}, {{ $discusion->user->country }}
                                    </div>
                                @endif
                                @if ( (!is_null($discusion->user->facebook)) || (!is_null($discusion->user->twitter)) || (!is_null($discusion->user->instagram)) || (!is_null($discusion->user->youtube)) || (!is_null($discusion->user->pinterest)) )
                                    <div class="btn-toolbar follow-actions">
                                        <ul class="list-inline">
                                            @if (!is_null($discusion->user->facebook))
                                                <li class="list-inline-item">
                                                    <a title="Facebook de {{ $discusion->user->names }} {{ $discusion->user->last_names }}" rel="external nofollow" href="{{ $discusion->user->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                                </li>
                                            @endif
                                            @if (!is_null($discusion->user->twitter))
                                                <li class="list-inline-item">
                                                    <a title="Twitter de {{ $discusion->user->names }} {{ $discusion->user->last_names }}" rel="external nofollow" href="{{ $discusion->user->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                                </li>
                                            @endif
                                            @if (!is_null($discusion->user->instagram))
                                                <li class="list-inline-item">
                                                    <a title="Instagram de {{ $discusion->user->names }} {{ $discusion->user->last_names }} Instagram" rel="external nofollow" href="{{ $discusion->user->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                                </li>
                                            @endif
                                            @if (!is_null($discusion->user->youtube))
                                                <li class="list-inline-item">
                                                    <a title="Canal de YouTube de {{ $discusion->user->names }} {{ $discusion->user->last_names }} Instagram" rel="external nofollow" href="{{ $discusion->user->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                                </li>
                                            @endif
                                            @if (!is_null($discusion->user->pinterest))
                                                <li class="list-inline-item">
                                                    <a title="Pinterest de {{ $discusion->user->names }} {{ $discusion->user->last_names }}" rel="external nofollow" href="{{ $discusion->user->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="block">
                            <h5>OTRAS DISCUSIONES EN ESTE FORO</h5>
                            <ul class="sidebar-topics-list">
                                @foreach ($otrasDiscusiones as $otraDiscusion)
                                    <li class="item">
                                        <div class="topic-badge topic-badge--xs">
                                            <span class="avatar avatar--xs">
                                                <img width="24" height="24" src="{{asset('uploads/images/users/'.$otraDiscusion->user->avatar) }}">
                                            </span>
                                            <h3 class="title">
                                                <a href="{{ route('students.discussions.show', [$otraDiscusion->slug, $otraDiscusion->id]) }}">{{ $otraDiscusion->title }}</a>
                                            </h3>
                                            <div class="description">{!! $otraDiscusion->comment !!}</div>
                                            <div class="metadata">
                                                por {{ $otraDiscusion->user->names }} {{ $otraDiscusion->user->last_names }}
                                            </div>
                                            <div class="likes">
                                                <ul class="list-inline">
                                                    <li class="list-inline-item">
                                                        <i class="fa fa-comment i-space-right-small"></i> <b>{{ $otraDiscusion->comments_count }}</b>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection