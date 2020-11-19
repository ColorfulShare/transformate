@extends('layouts.admin')

@push('styles')
    <style>
        input[type="radio"] {
          display: none;
        }

        label {
          color: grey;
        }

        .clasificacion {
          direction: rtl;
          unicode-bidi: bidi-override;
        }

        label:hover,
        label:hover ~ label {
          color: orange;
        }

        input[type="radio"]:checked ~ label {
          color: orange;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('template/css/show.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@section('content')
   <div class="hero hero--course t-hero--course color-back">
      <div class="uk-container">
         <div uk-grid>
            <div class="uk-width-1-1">
               <br>  
               <span class="badge badge--top_sales ">Top ventas</span>
               <h1 class="h2 hero--course__title">
                  <a href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}">{{ $podcast->title }}</a>
               </h1>
               <h2 class="hero--course__by">
                  Un T-Book de <a href="{{ route('admins.users.show', $instructor->id) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a>, {{ $instructor->profession }}
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
                        <i class="fa fa-film fa-fw"></i> 1 Archivo de Audio (120 Min)
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
         </div>
      </div>
   </div>

   <div uk-grid class="color-back2">
      <!-- navigation-->
      <div class="uk-width-2-3@m uk-first-column">
         <ul class="uk-tab uk-flex-center  uk-margin-remove-top" uk-tab>
            <li aria-expanded="true" class="uk-active">
               <a href="#"> Presentación  </a>
            </li>                 
            <li>
               <a href="#"> Contenido </a>
            </li>                 
            <li>
               <a href="#"> Valoraciones </a>
            </li>                 
         </ul>
         <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto  uk-margin-top">
            <!-- Presentación-->
            <li class="uk-active papd">
               @if (!is_null($podcast->preview))
                  <audio src="{{ $podcast->preview }}" type="audio/mp3" controls draggable controlslist="nodownload" style="height: 50px;"></audio> 
               @endif
                
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">{!! $podcast->review !!}</h2>
                  <div class="course-description">
                     {!! $podcast->description !!}
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
               
               <hr>
               <div class="section">
                  <div class="course-teacher uk-grid" data-id="user-row-1327757">
                     <div class="course-teacher__avatar uk-width-1-3@m uk-first-column gri">
                        <img alt="{{ $instructor->names }} {{ $instructor->last_names }}" class=" lazyloaded" src="{{ asset('uploads/images/users/'.$instructor->avatar)}}">
                     </div>
                     <div class="course-teacher__details uk-width-2-3@m uk-first-column">
                        <h3 class="course-teacher__name">
                           <a href="{{ route('admins.users.show', $instructor->id) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a>
                           <span class="badge badge--teacher ">Profesor</span>
                        </h3>
                        <h4 class="course-teacher__title">{{ $instructor->profession }}</h4>
                        <h3 class="course-teacher__name">
                           <ul class="list-inline">
                              <li class="list-inline-item" style="font-size: 0.7em;">Redes Sociales Disponibles: </li>
                              @if (!is_null($instructor->facebook))
                                 <li class="list-inline-item">
                                    <a title="Facebook de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->twitter))
                                 <li class="list-inline-item">
                                    <a title="Twitter de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->instagram))
                                 <li class="list-inline-item">
                                    <a title="Instagram de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->youtube))
                                 <li class="list-inline-item">
                                    <a title="Canal de YouTube de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->pinterest))
                                 <li class="list-inline-item">
                                     <a title="Pinterest de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                 </li>
                              @endif
                           </ul>
                        </h3>
                        <div class="course-teacher__summary">
                           <p>{!! $instructor->review !!}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </li>

            <!-- Contenido -->
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

            <!-- Valoraciones -->
            <li class="papd"> 
               @if ($podcast->ratings_count > 0)
                  @foreach ($podcast->ratings as $valoracion)
                     <div class="uk-grid-small" uk-grid> 
                        <div class="uk-width-1-5 uk-first-column">
                           <img class="uk-width-1-2 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-align-center  uk-box-shadow-large" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}">
                        </div>
                        <div class="uk-width-4-5 uk-padding-remove-left"> 
                           <div class="uk-float-right"> 
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($valoracion->points >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($valoracion->points >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($valoracion->points >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($valoracion->points >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                           </div>
                           <h4 class="uk-margin-remove">{{ $valoracion->user->names }} {{ $valoracion->user->last_names }}</h4> 
                           <span class="uk-text-small">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                           <hr class="uk-margin-small">
                           <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $valoracion->comment }}</p> 
                        </div>                         
                     </div>
                  @endforeach
               @else
                  El T-Book no tiene ninguna valoración aún...
               @endif
            </li>
         </ul>
        </div>
        
      <div class="uk-width-1-3@m uk-first-column gr uk-text-center">
         <hr>
         <div class="uk-card uk-card-default">
            <div class="uk-card-body">
               <div class="uk-grid-small uk-padding-small" uk-grid> 
                  <div class="uk-width-4-4@m uk-first-column">   
                     <h5 class="borr">Un T-Book de</h5>                    
                     <img class="uk-width-3-3 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}"> <br>
                     <strong class="nickname"><a href="{{ route('admins.users.show', $instructor->id) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a></strong> <br>
                     <div class="badges"><span class="badge badge--teacher ">Profesor</span><a class="badge badge--pro " href="#">Pro</a></div>
                     <div class="since">
                        En Transfórmate desde {{ date('d-m-Y H:i A', strtotime("$instructor->created_at -5 Hours")) }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $instructor->state }}, {{ $instructor->country }}<br><br>
                        <h3 class="course-teacher__name uk-text-center">
                           <ul class="list-inline">
                              @if (!is_null($instructor->facebook))
                                 <li class="list-inline-item">
                                    <a title="Facebook de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->twitter))
                                 <li class="list-inline-item">
                                    <a title="Twitter de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->instagram))
                                 <li class="list-inline-item">
                                    <a title="Instagram de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->youtube))
                                 <li class="list-inline-item">
                                    <a title="Canal de YouTube de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($instructor->pinterest))
                                 <li class="list-inline-item">
                                    <a title="Pinterest de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                 </li>
                              @endif
                           </ul>
                        </h3>
                     </div>
                     <hr>
                     <h5 class="borr">Detalles del Podcast</h5>  
                     <div class="uk-text-left course-details-list">
                        <p><i class="fa fa-film fa-fw"></i>1 Archivo de Audio (120 Min)</p>
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