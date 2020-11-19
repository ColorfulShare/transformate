@extends('layouts.student2')

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
            @if ($errors->any())
                <div class="uk-alert-danger" uk-alert>
                    <ul class="uk-list uk-list-bullet">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    
                </div>
            @endif

            <div class="uk-margin-medium-bottom">
                <a class="uk-button btn-secondary" href="#create-modal" uk-toggle><i class="fa fa-pencil"></i> Crear Nueva Discusión</a>
            </div>

            <ul class="forums-list">
                @if ($cantDiscusiones > 0)
                    @foreach ($discusiones as $discusion)
                        <li class="paper">
                            <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom">
                                <div class="uk-grid">
                                    <div class="uk-width-expand">
                                        <div class="topic-badge-wrapper">
                                            <div class="topic-badge topic-badge--m">
                                                <a href="#">
                                                    <span class="avatar avatar--s">
                                                        <img width="32" height="32" title="{{ $discusion->user->names }} {{ $discusion->user->last_names }}" src="{{ asset('uploads/images/users/'.$discusion->user->avatar) }}">
                                                    </span>
                                                </a>
                                                <h3 class="title">
                                                    <a href="{{ route('students.discussions.show', [$discusion->slug, $discusion->id]) }}">{{ $discusion->title }}</a>
                                                </h3>
                                                <div class="metadata">
                                                    publicada el {{ date('d-m-Y H:i A', strtotime("$discusion->created_at -5 Hours")) }} por {{ $discusion->user->names }} {{ $discusion->user->last_names }}<br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-width-auto">
                                        <p class="topic-count">
                                            <a href="{{ route('students.discussions.show', [$discusion->slug, $discusion->id]) }}"></i>
                                                <i class="fa fa-comment i-space-right"></i>{{ $discusion->comments_count }} comentarios
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>   
            {{ $discusiones->links() }}<br>       
        </div>
    </div>

    <div id="create-modal" class="uk-modal-container" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Crear Nueva Discusión</h4>
            </div>
            <form action="{{ route('students.discussions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="{{ $tipo }}">
                <input type="hidden" name="id" value="{{ $id }}"> 
                @if ($tipo == 'course')
                    <input type="hidden" name="course_id" value="{{ $id }}">
                @elseif ($tipo == 'certification')
                    <input type="hidden" name="certification_id" value="{{ $id }}">
                @else 
                    <input type="hidden" name="podcast_id" value="{{ $id }}">
                @endif

                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <div class="uk-width-1-1">
                            <input class="uk-input" type="text" name="title" placeholder="Título de la discusión..." required>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-width-1-1">
                            <textarea class="ckeditor" rows="5" name="comment" placeholder="Contenido de la discusión..."></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '.ckeditor' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-success" type="submit">Crear Discusión</button>
                </div>
            </form>
        </div>
    </div>
@endsection