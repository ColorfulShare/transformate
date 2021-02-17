@extends('layouts.admin')

@push('styles')
   <link rel="stylesheet" type="text/css" href="{{ asset('template/css/show.css') }}">
   <link rel="stylesheet" href="{{ asset('template/css/discussions.css') }}">
@endpush

@section('content')
   <div class="admin-content-inner"> 
      <div class="hero hero--course t-hero--course color-back">
         <div class="uk-container">
            <div uk-grid>
               <div class="uk-width-2-3@m">
                  <br>  
                  <span class="badge badge--top_sales ">Top ventas</span>
                  <h1 class="h2 hero--course__title">
                     <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">{{ $certificacion->title }}</a>
                  </h1>
                  <h2 class="hero--course__by">
                     <p class="uk-dark uk-text-bold nrg">
                        Categoría: <i class="{{ $certificacion->category->icon }}"></i> {{ $certificacion->category->title }}
                     </p>
                  </h1>
                  <div class="d-none d-md-flex">
                     <ul class="list-inline hero--course__stats">
                        <li class="list-inline-item">
                           <i class="fa fa-star fa-fw"></i> {{ number_format($certificacion->promedio, 1) }} ({{ $certificacion->ratings_count }} Valoraciones)
                        </li>
                        <li class="list-inline-item">
                           <i class="fa fa-users fa-fw"></i> {{ $certificacion->students_count }} Estudiantes
                        </li>
                        <li class="list-inline-item">
                           <i class="fa fa-film fa-fw"></i> 30 Lecciones (3 Horas)
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
                  <div class="hero__purchase-actions">
                     <div class="hero__price-and-savings hero__price-and-savings--with-retail">
                        <div class="hero__price">
                          CO$ {{ $certificacion->price }}
                        </div>
                     </div>

                     @if (!is_null($certificacion->preview))
                        <a class="uk-button uk-button-white" href="#preview" uk-toggle uk-tooltip="title: Video Resumen; delay: 200 ; pos: top ;animation:   uk-animation-scale-up">  Vista Previa</a>
                     @endif
                     
                     <span class="price-badges js-price-badges t-price-badges">
                        <span class="price-badges--prices">
                           <span class="price-discount t-price-discount">50% Dto.</span>
                           <b class="price-before t-price-before">CO$ {{ $certificacion->price*2 }}</b>
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
               @if (!is_null($certificacion->evaluation_review))
                  <li aria-expanded="true" class="uk-active">
                     <a href="#"> <i class="fa fa-exclamation-circle" style="color: orange;"></i> Evaluación de Revisión </a>
                  </li>
               @endif
               <li aria-expanded="true" @if (is_null($certificacion->evaluation_review)) class="uk-active" @endif>
                  <a href="#"> Presentación  </a>
               </li>                 
               <li>
                  <a href="#"> Temario </a>
               </li>                                  
               <li>
                  <a href="#"> Evaluaciones </a>
               </li>                       
               <li>
                  <a href="#"> Valoraciones </a>
               </li>   
            </ul>
            <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto  uk-margin-top">
               @if (!is_null($certificacion->evaluation_review))
                  <!-- Presentación-->
                  <li class="uk-active papd">
                     <div class="text-body-bigger">
                        <div class="course-description">
                           {!! $certificacion->evaluation_review !!}     
                        </div>
                     </div>
                  </li>
               @endif

               <!-- Presentación-->
               <li @if (!is_null($certificacion->evaluation_review)) class="papd" @else class="uk-active papd" @endif>
                  @if (!is_null($certificacion->preview))
                     <div class="video-responsive">
                        <video  src="{{ $certificacion->preview }}" type="video/mp4" @if (!is_null($certificacion->preview_cover)) poster="{{ asset('uploads/images/certifications/preview_covers/'.$certificacion->preview_cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
                     </div>
                  @endif
                   
                  <div class="text-body-bigger">
                     <h2 class="course-subtitle">{!! $certificacion->review !!}</h2>
                     <div class="course-description">
                        {!! $certificacion->description !!}
                        <div class="image-item"><img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" alt=""></div>      
                     </div>
                  </div>
                  <div class="text-body-bigger">
                     <h2 class="course-subtitle">Objetivos</h2>
                     <div class="course-description">
                        {!! $certificacion->objectives !!}
                        <p></p>     
                     </div>
                  </div>
                  <div class="text-body-bigger">
                     <h2 class="course-subtitle">¿Qué incluye esta certificación?</h2>
                     <div class="course-description">
                        {!! $certificacion->material_content !!}   
                     </div>
                  </div>
                  <div class="text-body-bigger">
                     <h2 class="course-subtitle">¿A quién está dirigida?</h2>
                     <div class="course-description">
                        {!! $certificacion->destination !!}
                        <p></p>     
                     </div>
                  </div>
                  <div class="text-body-bigger">
                     <h2 class="course-subtitle">Requisitos</h2>
                     <div class="course-description">
                        {!! $certificacion->requirements !!}
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

               <!-- Temario -->
               <li class="papd">
                  <ul uk-accordion class="uk-accordion"> 
                     @foreach ($certificacion->modules as $modulo)
                        <li class="uk-open tm-course-lesson-section uk-background-default"> 
                           <a class="uk-accordion-title uk-padding-small" href="#">
                              <h6 style="color: #585858;"> {{ $modulo->title }}</h6>  
                           </a> 
                           <div class="uk-accordion-content uk-margin-remove-top"> 
                              <div class="tm-course-section-list">
                                 <ul>   
                                    @foreach ($modulo->lessons as $leccion)
                                       <li>
                                          @if (!is_null($leccion->video))
                                             <div uk-lightbox>
                                                <a class="uk-link-reset" href="{{ $leccion->video }}"> 
                                                   <span class="uk-icon-button icon-play"> <i class="fas fa-play icon-small"></i> </span>
                                                   {{ $leccion->title }}
                                                   <span class="uk-visible@m uk-position-center-right time uk-margin-right"> <i class="fas fa-clock icon-small"></i>  5 Minutos</span>
                                                </a>
                                             </div>
                                          @else
                                             <a class="uk-link-reset"> 
                                                <span class="uk-icon-button icon-play"> <i class="fas fa-exclamation-circle icon-small"></i> </span>
                                                {{ $leccion->title }}
                                                <span class="uk-visible@m uk-position-center-right time uk-margin-right"> <i class="fas fa-exclamation icon-small"></i>  Sin Video</span>
                                             </a>
                                          @endif
                                       </li>
                                    @endforeach                                                      
                                 </ul>
                              </div>                                     
                           </div>                                 
                        </li>  
                     @endforeach                                                      
                  </ul>
               </li>

               <!-- Evaluaciones -->
               <li class="papd">
                  <ul uk-accordion class="uk-accordion"> 
                     @foreach($certificacion->modules as $modulo)
                        <li class="uk-open tm-course-lesson-section uk-background-default"> 
                           <a class="uk-accordion-title uk-padding-small" href="#">
                              <h6 style="color: #585858;">Módulo #{{ $modulo->priority_order }} ({{ $modulo->title }})</h6>
                           </a> 
                           <div class="uk-accordion-content uk-margin-remove-top"> 
                              <div class="tm-course-section-list">
                                 <ul> 
                                    @foreach ($modulo->tests as $evaluacion)
                                       @php
                                          $cantPresentaciones = DB::table('tests_students')
                                                                     ->where('test_id', '=', $evaluacion->id)
                                                                     ->count();
                                       @endphp
                                       <li>
                                          <a href="{{ route('admins.tests.show', [$evaluacion->slug, $evaluacion->id]) }}"class="uk-link-reset"> 
                                             <span class="uk-icon-button icon-play"> <i class="fas fa-search icon-small"></i> </span>
                                             {{ $evaluacion->title }}
                                             <span class="uk-visible@m uk-position-center-right time uk-margin-right"> <i class="fas fa-check icon-small"></i>  {{ $cantPresentaciones }} Presentaciones</span>
                                          </a>
                                       </li>
                                    @endforeach                                  
                                 </ul>
                              </div>                                     
                           </div>
                        </li>  
                     @endforeach                                                      
                  </ul>
               </li>

               <!-- Valoraciones -->
               <li class="papd"> 
                  @if ($certificacion->ratings_count > 0)
                     @foreach ($certificacion->ratings as $valoracion)
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
                              <span class="uk-text-small">{{ date('d-m-Y', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                              <hr class="uk-margin-small">
                              <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $valoracion->comment }}</p> 
                           </div>                         
                        </div>
                     @endforeach
                  @else
                     La T-Mentoring no tiene ninguna valoración aún...
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
                        <h5 class="borr">Una certificación de</h5>
                        <img class="uk-width-3-3 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}"> <br>
                        <strong class="nickname"><a href="{{ route('admins.users.show', $instructor->id) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a></strong> <br>
                        <div class="badges"><span class="badge badge--teacher ">Profesor</span><a class="badge badge--pro " href="#">Pro</a></div>
                        <div class="since">
                           En Transfórmate desde {{ date('d-m-Y', strtotime("$instructor->created_at -5 Hours")) }}<br>
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
                        <h5 class="borr">Detalles de la Certificación</h5>  
                        <div class="uk-text-left course-details-list">
                           <p><i class="fa fa-film fa-fw"></i>36 Lecciones (3Horas)</p>
                           <p><i class="fa fa-users fa-fw"></i> {{ $certificacion->students_count }} Alumnos</p>
                           <p><i class="fa fa-star fa-fw"></i> {{ number_format($certificacion->promedio, 2) }} ({{ $certificacion->ratings_count }} Valoraciones)</p>
                           <p><i class="far fa-smile"></i> Online y a tu ritmo</p>
                           <p><i class="fa fa-volume-up fa-fw"></i>Audio: Español</p>
                           <p><i class="far fa-closed-captioning"></i> Español / <span class="has-tooltip link-help" uk-tooltip="Traducción automática">Inglés</span> / <span class="has-tooltip link-help" uk-tooltip="Traducción automática">Portugués</span></p>
                           <p class="course-details-category">Categoría:<br> {{ $certificacion->category->title }}</p> 
                           <p class="course-details-category">ÁREAS</p>
                           <ul class="tags-list">
                              @foreach ($certificacion->tags as $etiqueta)
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

      <!-- Modal de Vista Previa -->
      <div id="preview" uk-modal>
         <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-margin-small-top  uk-margin-small-right uk-light" type="button" uk-close></button>                         
            <div class="uk-modal-header topic4 none-border">
               <b class="uk-text-white uk-text-medium">  Resumen de la T-Mentoring </b>
            </div>
            <div class="video-responsive">
               <video  src="{{ $certificacion->preview }}" type="video/mp4" @if (!is_null($certificacion->preview_cover)) poster="{{ asset('uploads/images/certifications/preview_covers/'.$certificacion->preview_cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
            </div>
            <div class="uk-modal-body"> 
               <h3>{{ $certificacion->title }}</h3>
               <p>{{ $certificacion->subtitle }}</p>
            </div>                         
            <div class="uk-modal-footer uk-text-right"> 
               <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>   
            </div>
         </div>
      </div>
   </div>
@endsection