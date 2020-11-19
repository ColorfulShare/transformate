@extends('layouts.instructor')

@push('styles')
   <link rel="stylesheet" type="text/css" href="{{ asset('template/css/show.css') }}">
@endpush

@section('content')
   <div class="hero hero--course t-hero--course color-back" style="margin-top: -30px;">
      <div class="uk-container">
         <div uk-grid>
            <div class="uk-width-2-3@m">
               <br>  
               <span class="badge badge--top_sales ">Top ventas</span>
               <h1 class="h2 hero--course__title">
                  <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}">{{ $podcast->title }}</a>
               </h1>
               <h2 class="hero--course__by">
                  <p class="uk-dark uk-text-bold nrg">
                     Categoría: <i class="{{ $podcast->category->icon }}"></i> {{ $podcast->category->title }}
                  </p>
               </h1>
               <div class="d-none d-md-flex">
                  <ul class="list-inline hero--course__stats">
                     <li class="list-inline-item">
                        <i class="fa fa-star fa-fw"></i> {{ number_format($podcast->promedio, 1) }} ({{ $podcast->ratings_count }} Valoraciones)
                     </li>
                     <li class="list-inline-item">
                        <i class="fa fa-users fa-fw"></i> {{ $podcast->students_count }} Estudiantes
                     </li>
                     <li class="list-inline-item">
                        <i class="fa fa-film fa-fw"></i> 1 Archivo de Audio (120Min)
                     </li>
                     <li class="list-inline-item">
                        <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                     </li>
                     <li class="list-inline-item">
                        <i class="far fa-closed-captioning fa-fw"></i> Español / <span class="has-tooltip link-help" data-toggle="tooltip" data-placement="top" title="" data-original-title="Traducción automática">Inglés</span> / <span class="has-tooltip link-help" data-toggle="tooltip" data-placement="top" title="Traducción automática">Portugués</span>
                     </li>

                  </ul>
               </div>
            </div>
            <div class="uk-width-1-3@m">
               <br>
               <div class="hero__purchase-actions" style="margin-bottom: 15px;">
                  <div class="hero__price-and-savings hero__price-and-savings--with-retail">
                     <div class="hero__price">
                       CO$ {{ $podcast->price }}
                     </div>
                  </div>

                  <a class="uk-button uk-button-primary" href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}" uk-tooltip="title: Ir al Foro; delay: 300 ; pos: top ;animation: uk-animation-slide-bottom-small"> Ir al Foro</a>
                  
                  <span class="price-badges js-price-badges t-price-badges">
                     <span class="price-badges--prices">
                        <span class="price-discount t-price-discount">50% Dto.</span>
                        <b class="price-before t-price-before">CO$ {{ $podcast->price*2 }}</b>
                     </span>
                  </span>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div uk-grid class="color-back2">
      <div class="uk-width-2-3@m uk-first-column">
         <ul class="uk-tab uk-flex-center  uk-margin-remove-top" uk-tab>
            @if ($podcast->status == 4)
               <li aria-expanded="true" class="uk-active">
                  <a href="#"> <i class="fa fa-exclamation-circle" style="color: orange;"></i> Evaluación de Revisión </a>
               </li>
            @endif
            <li aria-expanded="true" @if ($podcast->status != 4) class="uk-active" @endif>
               <a href="#"> Presentación  </a>
            </li>                 
            <li>
               <a href="#"> Contenido </a>
            </li>  
         </ul>
         <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto  uk-margin-top">
             @if ($podcast->status == 4)
               <!-- Revisión -->
               <li class="uk-active papd">
                  <div class="text-body-bigger">
                     <div class="course-description">
                        {!! $podcast->evaluation_review !!}     
                     </div>
                  </div>
               </li>
            @endif

            <!-- Presentación-->
            <li @if ($podcast->status != 4) class="uk-active papd" @else class="papd" @endif>
               @if (!is_null($podcast->preview))
                  <div class="video-responsive">
                     <audio src="{{ $podcast->preview }}" type="audio/mp3" controls autoplay muted controlslist="nodownload"></audio>
                  </div>
               @endif
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Inspirado en: {{ $podcast->inspired_in }}</h2>
                  <div class="course-description">
                     {!! $podcast->review !!}
                     <p></p>
                     <div class="image-item"><img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" alt=""></div>
                     <p></p>       
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Prólogo</h2>
                  <div class="course-description">
                     {!! $podcast->prologue !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Objetivos</h2>
                  <div class="course-description">
                     {!! $podcast->objectives !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">¿Qué incluye este T-Book?</h2>
                  <div class="course-description">
                     {!! $podcast->material_content !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">¿A quién está dirigido?</h2>
                  <div class="course-description">
                     {!! $podcast->destination !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Importancia</h2>
                  <div class="course-description">
                     {!! $podcast->importance !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Impacto Potencial</h2>
                  <div class="course-description">
                     {!! $podcast->potential_impact !!}
                     <p></p>     
                  </div>
               </div>
            </li>

            <!-- Temario -->
            <li class="papd">
               <h6> T-Book</h6>  
               <div class="tm-course-section-list">
                  <ul>   
                     <li>
                        @if (!is_null($podcast->audio_file))
                           <audio src="{{ $podcast->audio_file }}" type="audio/mp3" controls draggable controlslist="nodownload" style="height: 50px;"></audio>  
                        @else
                           <a class="uk-link-reset"> 
                              <span class="uk-icon-button icon-play"> <i class="fas fa-exclamation-circle icon-small"></i> </span>Sin Archivo de Audio
                              <span class="uk-visible@m uk-position-center-right time uk-margin-right"> <i class="fas fa-exclamation icon-small"></i>  Sin Audio</span>
                           </a>
                        @endif
                     </li>                                                   
                  </ul>
               </div>       
            </li>
         </ul>
      </div>
        
      <div class="uk-width-1-3@m uk-first-column gr uk-text-center">
         <hr>
         <div class="uk-card uk-card-default">
            <div class="uk-card-body">
               <div class="uk-grid-small uk-padding-small" uk-grid> 
                  <div class="uk-width-4-4@m uk-first-column">   
                     <h5 class="borr">Detalles del T-Book</h5>  
                     <div class="uk-text-left course-details-list">
                        <p><i class="fa fa-film fa-fw"></i>1 Archivo de Audio (120Min)</p>
                        <p><i class="fa fa-users fa-fw"></i> {{ $podcast->students_count }} Alumnos</p>
                        <p><i class="fa fa-star fa-fw"></i> {{ number_format($podcast->promedio, 2) }} ({{ $podcast->ratings_count }} Valoraciones)</p>
                        <p><i class="far fa-smile"></i> Online y a tu ritmo</p>
                        <p><i class="fa fa-volume-up fa-fw"></i>Audio: Español</p>
                        <p><i class="far fa-closed-captioning"></i> Español / <span class="has-tooltip link-help" uk-tooltip="Traducción automática">Inglés</span> / <span class="has-tooltip link-help" uk-tooltip="Traducción automática">Portugués</span></p>
                        <p class="course-details-category">Categoría:<br> {{ $podcast->category->title }}</p> 
                        <p class="course-details-category">ÁREAS</p>
                        <ul class="tags-list">
                           @foreach ($podcast->tags as $etiqueta)
                              <li>
                                 <a href="#">{{ $etiqueta->tag }}</a>
                              </li>
                           @endforeach
                        </ul>
                     </div>                 
                  </div>                       
               </div>   
            </div>              
         </div>
      </div>
   </div>
@endsection