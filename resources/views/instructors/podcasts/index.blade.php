@extends('layouts.instructor')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
    <style>
        .revision{
            font-size: 0.9em;
        }
    </style>
@endpush

@section('content')
    <div class="uk-container uk-margin-medium-top" uk-scrollspy="target: > div; cls:uk-animation-slide-bottom-medium; delay: 200">  
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-clearfix boundary-align"> 
            <div class="uk-clearfix boundary-align"> 
                <div class="uk-float-left section-heading none-border coco"> 
                    <h2 class="sca">Mis T-Books</h2> 
                </div> 
            </div> 
            <div class="uk-float-right section-heading none-border"> 
                <a href="{{ route('instructors.podcasts.create') }}" class="uk-button uk-button-danger"><i class="fa fa-plus-circle"></i> Crear T-Book</a>
            </div>                       
        </div>
        
        <div class="uk-child-width-1-2@s uk-child-width-1-4@m uk-grid-match uk-margin uk-grid uk-grid-stack" > 
            @foreach($podcasts as $podcast)                            
                <div style="" class="uk-scrollspy-inview uk-margin-top uk-margin-bottom"> 
                    <div class="uk-card-default uk-card-hover uk-card-small uk-inline-clip">
                        <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}" class="uk-link-reset"> 
                            <img src="{{asset('uploads/images/podcasts/'.$podcast->cover)}}" class="course-img"> 
                        </a>
                        <div class="uk-card-body">
                            <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}" class="uk-link-reset"> 
                                <h3 class="uk-card-title">{{ $podcast->title}}</h4> 
                            </a>
                            <div uk-grid>
                                <div class="wt uk-margin-top uk-margin-bottom">
                                    <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: search;" uk-tooltip="Ver"></a>
                                </div>
                                <div class="wt uk-margin-top uk-margin-bottom">
                                    <a href="{{ route('instructors.podcasts.edit', [$podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>
                                </div>
                                <div class="wt uk-margin-top uk-margin-bottom">
                                    <a href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: comments;" uk-tooltip="Foro"></a>
                                </div>
                                 <div class="wt uk-margin-top uk-margin-bottom">
                                    <a href="{{ route('instructors.ratings.index', ['podcast', $podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: star;" uk-tooltip="Valoraciones"></a>
                                </div>
                                <div class="wt uk-margin-top uk-margin-bottom">
                                    <a href="{{ route('instructors.podcasts.purchases-record', [$podcast->slug, $podcast->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: cart;" uk-tooltip="Compras"></a>
                                </div> 
                                @if ( ($podcast->status == 0) || ($podcast->status == 4) )
                                    <div class="wt uk-margin-top uk-margin-bottom">
                                        <a href="{{ route('instructors.podcasts.publish', $podcast->id) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: tag;" uk-tooltip="Publicar"></a>
                                    </div> 
                                @endif
                                @if ($podcast->status == 0)
                                    <div class="uk-width-1-1 nb">
                                        <button class="uk-button uk-margin-top uk-button-default " disabled>Sin Publicar</button>
                                    </div>
                                @elseif ($podcast->status == 1)
                                    <div class="uk-width-1-1 nb">
                                        <button class="uk-button uk-margin-top uk-button-default " disabled>Sin Revisar</button>
                                    </div>
                                @elseif ($podcast->status == 2)
                                    <div class="uk-width-1-1 nb">
                                        <button class="uk-button uk-margin-top uk-button-default " disabled>Publicado</button>
                                    </div>
                                @elseif ($podcast->status == 4)
                                    <div class="uk-width-1-1 nb">
                                        <button class="uk-button uk-margin-top uk-button-default " disabled>Por Corregir</button>
                                    </div>
                                @endif
                            </div> 
                        </div>                                                                      
                    </div>                                 
                </div> 
            @endforeach
        </div>
        
        {{ $podcasts->links() }}

        <div class="uk-grid" style="background-color: #eeeeee; padding-top: 10px;">
            <div class="uk-width-1-2">
                <h3><i>Resumen de Revisiones</i></h3><br>

                @if ($cantPodcastsConCorrecciones == 0)
                    <div class="paper">
                        No tiene ninguna evaluación por revisiones pendiente..
                    </div>
                @else
                    <ul class="forums-list">
                        @foreach ($podcastsConCorrecciones as $podcastCorreccion)
                            <li class="paper" style="padding-top: 0px; padding-left: 0px;">
                                <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom" >
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <div class="topic-badge-wrapper">
                                                <div class="topic-badge topic-badge--m">
                                                    <a href="#">
                                                        <span class="avatar avatar--s">
                                                            <img width="32" height="32" title="{{ $podcastCorreccion->title }}" src="{{ asset('uploads/images/podcasts/'.$podcastCorreccion->cover) }}">
                                                        </span>
                                                    </a>
                                                    <h3 class="title">
                                                        <a href="{{ route('instructors.podcasts.show', [$podcastCorreccion->slug, $podcastCorreccion->id]) }}">{{ $podcastCorreccion->title }}</a>
                                                    </h3>
                                                    <div class="metadata">
                                                        Revisado el {{ date('d-m-Y H:i A', strtotime("$podcastCorreccion->reviewed_at -5 Hours")) }}
                                                    </div><br>

                                                    <div class="revision">
                                                        {!!  $podcastCorreccion->evaluation_review !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="uk-width-1-2">
                <h3><i>Resumen de Actividad en el Foro</i></h3><br>
                
                @if ( ($cantNuevasDiscusiones == 0) && ($cantNuevosComentarios == 0))
                    <div class="paper">
                        No hay actividad reciente en sus foros..
                    </div>
                @else
                    @if ($cantNuevasDiscusiones > 0)
                        <h4>Nuevas Preguntas</h4>
                        <ul class="forums-list">
                            @foreach ($nuevasDiscusiones as $discusion)
                                <li class="paper" style="padding-top: 0px; padding-left: 0px;">
                                    <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom" >
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
                                                            <a href="{{ route('instructors.discussions.show', [$discusion->slug, $discusion->id]) }}">{{ $discusion->title }}</a>
                                                        </h3>
                                                        <div class="metadata">
                                                            Tiene una nueva pregunta en el foro...
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-width-auto">
                                                <p class="topic-count">
                                                    <a href="{{ route('instructors.discussions.show', [$discusion->slug, $discusion->id]) }}"></i>
                                                        <i class="fa fa-comment i-space-right"></i>Responder
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if ($cantNuevosComentarios > 0)
                        <h4>Nuevos Comentarios</h4>
                        <ul class="forums-list">
                            @foreach ($nuevosComentarios as $comentario)
                                <li class="paper" style="padding-top: 0px; padding-left: 0px;">
                                    <div class="paper__body paper__body--no-padded-top paper__body--no-padded-bottom" >
                                        <div class="uk-grid">
                                            <div class="uk-width-expand">
                                                <div class="topic-badge-wrapper">
                                                    <div class="topic-badge topic-badge--m">
                                                        <a href="#">
                                                            <span class="avatar avatar--s">
                                                                <img width="32" height="32" title="{{ $comentario->names }} {{ $comentario->last_names }}" src="{{ asset('uploads/images/users/'.$comentario->avatar) }}">
                                                            </span>
                                                        </a>
                                                        <h3 class="title">
                                                            <a href="{{ route('instructors.discussions.show', [$comentario->slug, $comentario->discussion_id]) }}">{{ $comentario->title }}</a>
                                                        </h3>
                                                        <div class="metadata">
                                                            Tiene {{ $comentario->total_comentarios }} nuevos comentarios en su pregunta...
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-width-auto">
                                                <p class="topic-count">
                                                    <a href="{{ route('instructors.discussions.show', [$comentario->slug, $comentario->discussion_id]) }}"></i>
                                                        <i class="fa fa-comment i-space-right"></i>Revisar
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection