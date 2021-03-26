@extends('layouts.student2')

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

@push('scripts')
    <script>
       $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });
      });
        function cargarModalEditar(){
            document.getElementById("rating_id").value = document.getElementById("id_hidden").value;
            document.getElementById("title").value = document.getElementById("title_hidden").value;
            document.getElementById("comment").value = document.getElementById("comment_hidden").value;
            if (document.getElementById("points_hidden").value >= 1){
                document.getElementById("radio5").checked = true;
            }
            if (document.getElementById("points_hidden").value >= 2){
                document.getElementById("radio4").checked = true;
            }
            if (document.getElementById("points_hidden").value >= 3){
                document.getElementById("radio3").checked = true;
            }
            if (document.getElementById("points_hidden").value >= 4){
                document.getElementById("radio2").checked = true;
            }
            if (document.getElementById("points_hidden").value >= 5){
                document.getElementById("radio1").checked = true;
            }
            UIkit.modal("#modalEditar").show();
        }
    </script>
@endpush

@section('content')
   @if (Session::has('msj'))
      <div id="note">
         ¡¡Felicidades!! Has comprado el curso de forma exitosa. ¡Disfrútalo! 
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
      </div>
   @endif

   @if (Session::has('msj-exitoso'))
      <div id="note">
         El código de regalo ha sido aplicado con exito. ¡¡Disfrute su nuevo T-Course!!
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
      </div>
   @endif
    
   @if (is_null($miValoracion))
         <script>
            $(function(){
               document.getElementById("course_id").value = "{{ $curso->id }}";
               UIkit.modal("#modalValorar").show();
            });
         </script>
   @endif

   @if (Session::has('msj-exitoso-rating'))
      <div id="note">
         {{ Session::get('msj-exitoso-rating') }}
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>

         <script>
            $(function(){
               openTabs(event, 'Valoraciones');
            });
         </script>
      </div>
   @endif

   @include('students.ratings.createModal')
   @include('students.ratings.updateModal')
   
   <div class="hero hero--course t-hero--course color-back">
      <div class="uk-container">
         <div uk-grid>
            <div class="uk-width-2-3@m">
               <br>  
               <span class="badge badge--top_sales ">Top ventas</span>
               <h1 class="h2 hero--course__title">
                  <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}">{{ $curso->title }}</a>
               </h1>
               <h2 class="hero--course__by">
                  Un T-Course de <a href="{{ route('students.instructors.show-profile', [$curso->user->slug, $curso->user->id]) }}">{{ $curso->user->names }} {{ $curso->user->last_names }}</a>, {{ $curso->user->profession }}
                  <p class="uk-dark uk-text-bold nrg">
                     Categoría: <i class="{{ $curso->category->icon }}"></i> {{ $curso->category->title }}
                  </p>
               </h1>
               <div class="d-none d-md-flex">
                  <ul class="list-inline hero--course__stats">
                     {{--<li class="list-inline-item">
                        <i class="fa fa-star fa-fw"></i> {{ number_format($curso->promedio, 1) }} ({{ $curso->ratings_count }} Valoraciones)
                     </li>--}}
                     {{--<li class="list-inline-item">
                        <i class="fa fa-users fa-fw"></i> {{ $curso->students_count }} Estudiantes
                     </li>--}}
                     <li class="list-inline-item">
                        <i class="fa fa-film fa-fw"></i> {{ $curso->lessons_count }} Lecciones
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
               <hr>
               <div class="hero__purchase-actions">
                  <div class="hero__price-and-savings hero__price-and-savings--with-retail uk-visible@m">
                     <div><h4>Mi Progreso ({{ $progreso->progress }}%)</h4></div>
                     <span class="price-badges js-price-badges t-price-badges">
                        <span class="price-badges--prices">
                           <progress id="js-progressbar" class="uk-progress progress-green uk-margin-small-bottom uk-margin-small-top" value="{{ $progreso->progress }}" max="100" style="height: 8px;"></progress>
                        </span> 
                     </span>
                  </div>
                  <div class="uk-hidden@m"><h4>Mi Progreso ({{ $progreso->progress }}%)</h4></div>
                  <p>
                     <a class="uk-button uk-button-primary" href="{{ route('students.discussions.group', ['course', $curso->slug, $curso->id]) }}" uk-tooltip="title: Ir al Foro; delay: 300 ; pos: top ;animation: uk-animation-slide-bottom-small"> Ir al Foro</a>
                     @if ($progreso->progress != 100)
                        <a class="uk-button uk-button-success" href="{{ route('students.courses.lessons', [$curso->slug, $curso->id, $primeraLeccion->id ]) }}" uk-tooltip="title: Continuar con el curso  ; delay: 300 ; pos: top ;animation:  uk-animation-slide-bottom-small"> Ir a Lecciones</a>
                     @else
                        <a class="uk-button uk-button-success" href="{{ asset('certificates/courses/'.Auth::user()->id.'-'.$curso->id.'.pdf') }}" target="_blank"><i class="fas fa-medal"></i> Ver Certificado</a>
                     @endif
                  </p>
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
               <a href="#"> Temario </a>
            </li>                 
            <li>
               <a href="#"> Valoraciones </a>
            </li>                
         </ul>
         <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto  uk-margin-top">
            <!-- Presentación-->
            <li class="uk-active papd">
               <h2 class="course-subtitle">{{ $curso->subtitle }}</h2>
               @if (!is_null($curso->preview))
                  <div class="video-responsive">
                     <video autoplay="autoplay" muted src="{{ $curso->preview }}" type="video/mp4" @if (!is_null($curso->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" @endif controls controlslist="nodownload"></video>
                  </div>
               @endif
                
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">{!! $curso->review !!}</h2>
                  <div class="course-description">
                     {!! $curso->description !!}
                     <p></p>
                     <div class="image-item"><img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" alt=""></div>
                     <p></p>       
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Objetivos</h2>
                  <div class="course-description">
                     {!! $curso->objectives !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">¿Qué incluye este curso?</h2>
                  <div class="course-description">
                     {!! $curso->material_content !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">¿A quién está dirigido?</h2>
                  <div class="course-description">
                     {!! $curso->destination !!}
                     <p></p>     
                  </div>
               </div>
               <div class="text-body-bigger">
                  <h2 class="course-subtitle">Requisitos</h2>
                  <div class="course-description">
                     {!! $curso->requirements !!}
                     <p></p>     
                  </div>
               </div>
               <hr>

               <div class="section">
                  <div class="course-teacher uk-grid" data-id="user-row-1327757">
                     <div class="course-teacher__avatar uk-width-1-3@m uk-first-column gri">
                        <img alt="{{ $curso->user->names }} {{ $curso->user->last_names }}" class=" lazyloaded" src="{{ asset('uploads/images/users/'.$curso->user->avatar)}}">
                     </div>
                     <div class="course-teacher__details uk-width-2-3@m uk-first-column">
                        <h3 class="course-teacher__name">
                           <a href="{{ route('students.instructors.show-profile', [$curso->user->slug, $curso->user->id]) }}">{{ $curso->user->names }} {{ $curso->user->last_names }}</a>
                           <span class="badge badge--teacher ">Profesor</span>
                        </h3>
                        <h4 class="course-teacher__title">{{ $curso->user->profession }}</h4>
                        <h3 class="course-teacher__name">
                           <ul class="list-inline">
                              <li class="list-inline-item" style="font-size: 0.7em;">Redes Sociales Disponibles: </li>
                              @if (!is_null($curso->user->facebook))
                                 <li class="list-inline-item">
                                    <a title="Facebook de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->twitter))
                                 <li class="list-inline-item">
                                    <a title="Twitter de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->instagram))
                                 <li class="list-inline-item">
                                    <a title="Instagram de {{ $curso->user->names }} {{ $curso->user->last_names }} Instagram" rel="external nofollow" href="{{ $curso->user->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->youtube))
                                 <li class="list-inline-item">
                                    <a title="Canal de YouTube de {{ $curso->user->names }} {{ $curso->user->last_names }} Instagram" rel="external nofollow" href="{{ $curso->user->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->pinterest))
                                 <li class="list-inline-item">
                                     <a title="Pinterest de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                 </li>
                              @endif
                           </ul>
                        </h3>
                        <div class="course-teacher__summary">
                           <p>{!! $curso->user->review !!}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </li>

            <!-- Temario -->
            <li class="papd">
               <ul uk-accordion class="uk-accordion"> 
                  @foreach ($curso->modules as $modulo)
                     <li class="uk-open tm-course-lesson-section uk-background-default"> 
                        <a class="uk-accordion-title uk-padding-small" href="#">
                           <h6> {{ $modulo->title }}</h6>  
                        </a> 
                        <div class="uk-accordion-content uk-margin-remove-top"> 
                           <div class="tm-course-section-list">
                              <ul>   
                                 @foreach ($modulo->lessons as $leccion)
                                    <li>
                                       @if (!is_null($leccion->video))
                                          <a class="uk-link-reset" href="{{ route('students.courses.lessons', [$curso->slug, $curso->id, $leccion->id]) }}"> 
                                             <span class="uk-icon-button icon-play"> <i class="fas fa-play icon-small"></i> </span>
                                             {{ $leccion->title }}
                                             <span class="uk-position-center-right time uk-margin-right"> 
                                                <i class="fas fa-clock icon-small"></i>  
                                                @if ($leccion->duration > 0) 
                                                   {{ $leccion->minutes }}m 
                                                @else
                                                   Sin Capturar
                                                @endif
                                             </span>
                                          </a>
                                       @else
                                          <a class="uk-link-reset"> 
                                             <span class="uk-icon-button icon-play"> <i class="fas fa-exclamation icon-small"></i> </span>
                                             {{ $leccion->title }}
                                             <span class="uk-position-center-right time uk-margin-right"> <i class="fas fa-times icon-small"></i> Sin Video</span>
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

            <!-- Valoraciones -->
            <li class="papd"> 
               @if ($curso->ratings_count > 0)
                  @if (!is_null($miValoracion))
                     <input type="hidden" id="id_hidden" value="{{ $miValoracion->id }}">
                     <input type="hidden" id="title_hidden" value="{{ $miValoracion->title }}">
                     <input type="hidden" id="comment_hidden" value="{{ $miValoracion->comment }}">
                     <input type="hidden" id="points_hidden" value="{{ $miValoracion->points }}">
                           
                     <div class="uk-grid-small" uk-grid style="background-color: #edeff0;"> 
                        <div class="uk-width-1-5 uk-first-column">
                           <img alt="Image" class="uk-width-1-2 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-align-center  uk-box-shadow-large" src="{{ asset('uploads/images/users/'.Auth::user()->avatar) }}">
                        </div>
                        <div class="uk-width-4-5 uk-padding-remove-left"> 
                           <div class="uk-float-right"> 
                              @if ($miValoracion->points >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($miValoracion->points >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($miValoracion->points >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($miValoracion->points >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                              @if ($miValoracion->points >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif<br>

                              <a class="uk-button" onclick="cargarModalEditar();"><i class="fa fa-edit"></i> Editar</a>
                           </div>
                           <h4 class="uk-margin-remove">{{ Auth::user()->names }} {{ Auth::user()->last_names }}</h4> 
                           <span class="uk-text-small">{{ date('d-m-Y H:i A', strtotime("$miValoracion->created_at -5 Hours")) }}</span> 
                           <hr class="uk-margin-small">
                           <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $miValoracion->comment }}</p> 
                        </div>                         
                     </div>
                  @endif
                  @foreach ($curso->ratings as $valoracion)
                     @if ($valoracion->user_id != Auth::user()->id)
                        <div class="uk-grid-small" uk-grid> 
                           <div class="uk-width-1-5 uk-first-column">
                              @if ($valoracion->user_id != 0)
                                 <img class="uk-width-1-2 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-align-center  uk-box-shadow-large" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}">
                              @else
                                 <img class="uk-width-1-2 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-align-center  uk-box-shadow-large" src="{{ asset('uploads/images/users/avatar.png') }}">
                              @endif
                           </div>
                           <div class="uk-width-4-5 uk-padding-remove-left"> 
                              <div class="uk-float-right"> 
                                 @if ($valoracion->points >= 1) <i class="fas fa-star icon-small"></i> @else <i class="far fa-star icon-small"></i> @endif
                                 @if ($valoracion->points >= 2) <i class="fas fa-star icon-small"></i> @else <i class="far fa-star icon-small"></i> @endif
                                 @if ($valoracion->points >= 3) <i class="fas fa-star icon-small"></i> @else <i class="far fa-star icon-small"></i> @endif
                                 @if ($valoracion->points >= 4) <i class="fas fa-star icon-small"></i> @else <i class="far fa-star icon-small"></i> @endif
                                 @if ($valoracion->points >= 5) <i class="fas fa-star icon-small"></i> @else <i class="far fa-star icon-small"></i> @endif
                              </div>
                              <h4 class="uk-margin-remove">
                                 @if ($valoracion->user_id != 0)
                                    {{ $valoracion->user->names }} {{ $valoracion->user->last_names }}
                                 @else
                                    {{ $valoracion->name }}
                                 @endif
                              </h4> 
                              <span class="uk-text-small">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                              <hr class="uk-margin-small">
                              <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $valoracion->comment }}</p> 
                           </div>                         
                        </div>
                     @endif
                  @endforeach
               @else
                  El T-Course no tiene ninguna valoración aún...
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
                     <h5 class="borr">Un T-Course de</h5>                    
                     <img class="uk-width-3-3 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up" src="{{ asset('uploads/images/users/'.$curso->user->avatar) }}"> <br>
                     <strong class="nickname"><a href="{{ route('students.instructors.show-profile', [$curso->user->slug, $curso->user->id]) }}">{{ $curso->user->names }} {{ $curso->user->last_names }}</a></strong> <br>
                     <div class="badges"><span class="badge badge--teacher ">Profesor</span><a class="badge badge--pro " href="#">Pro</a></div>
                     <div class="since">
                        En Transfórmate desde {{ date('d-m-Y H:i A', strtotime("$curso->user->created_at -5 Hours")) }}<br>
                        <i class="fas fa-map-marker-alt"></i> {{ $curso->user->state }}, {{ $curso->user->country }}<br><br>
                        <h3 class="course-teacher__name uk-text-center">
                           <ul class="list-inline">
                              @if (!is_null($curso->user->facebook))
                                 <li class="list-inline-item">
                                    <a title="Facebook de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->twitter))
                                 <li class="list-inline-item">
                                    <a title="Twitter de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->instagram))
                                 <li class="list-inline-item">
                                    <a title="Instagram de {{ $curso->user->names }} {{ $curso->user->last_names }} Instagram" rel="external nofollow" href="{{ $curso->user->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->youtube))
                                 <li class="list-inline-item">
                                    <a title="Canal de YouTube de {{ $curso->user->names }} {{ $curso->user->last_names }} Instagram" rel="external nofollow" href="{{ $curso->user->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                 </li>
                              @endif
                              @if (!is_null($curso->user->pinterest))
                                 <li class="list-inline-item">
                                    <a title="Pinterest de {{ $curso->user->names }} {{ $curso->user->last_names }}" rel="external nofollow" href="{{ $curso->user->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                 </li>
                              @endif
                           </ul>
                        </h3>
                     </div>
                     <hr>
                     <h5 class="borr">Detalles del T-Course</h5>  
                     <div class="uk-text-left course-details-list">
                        <p><i class="fa fa-film fa-fw"></i>{{ $curso->lessons_count }} Lecciones</p>
                        {{--<p><i class="fa fa-users fa-fw"></i> {{ $curso->students_count }} Alumnos</p>
                        <p><i class="fa fa-star fa-fw"></i> {{ number_format($curso->promedio, 2) }} ({{ $curso->ratings_count }} Valoraciones)</p>--}}
                        <p><i class="far fa-smile"></i> Online y a tu ritmo</p>
                        <p><i class="fa fa-volume-up fa-fw"></i>Audio: Español</p>
                        
                        <p class="course-details-category">Categoría:<br> {{ $curso->category->title }}</p>
                        <p class="course-details-category">ÁREAS</p>
                        <ul class="tags-list">
                           @foreach ($curso->tags as $etiqueta)
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