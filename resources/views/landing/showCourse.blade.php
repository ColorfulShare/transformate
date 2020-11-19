@extends('layouts.landing')

@push('styles')
   <link rel="stylesheet" type="text/css" href="{{ asset('css/courseDescription.css') }}">
@endpush

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });

         $('.close-trailer').on('click', function(){
            var vid = document.getElementById("video-trailer");
            vid.pause();
         });

         $('.close-trailer2').on('click', function(){
            var vid = document.getElementById("video-trailer2");
            vid.pause();
         });
      });

      function loadPreview(){
         modal = UIkit.modal("#previewModal");
         modal.show(); 
         if (document.getElementById("tema").value == 'dark'){
            $(".header-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("header-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("header-dark-trailer");
            });
            $(".color-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("color-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("color-dark-trailer");
            });
            $(".background-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("background-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("background-dark-trailer");
            });    
         }else{
            $(".header-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("header-dark-trailer");
               $("#"+$(this).attr('id')).addClass("header-ligth-trailer");
            });
            $(".color-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("color-dark-trailer");
               $("#"+$(this).attr('id')).addClass("color-ligth-trailer");
            });
            $(".background-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("background-dark-trailer");
               $("#"+$(this).attr('id')).addClass("background-ligth-trailer");
            });    
         }     
      }

      function showLesson($lesson){
         $("#video-trailer2").attr('src', $lesson.video);
         $("#title-trailer2").empty();
         $("#title-trailer2").append($lesson.title);
         modal = UIkit.modal("#lessonModal");
         modal.show(); 
         if (document.getElementById("tema").value == 'dark'){
            $(".header-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("header-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("header-dark-trailer");
            });
            $(".color-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("color-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("color-dark-trailer");
            });
            $(".background-ligth-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("background-ligth-trailer");
               $("#"+$(this).attr('id')).addClass("background-dark-trailer");
            });    
         }else{
            $(".header-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("header-dark-trailer");
               $("#"+$(this).attr('id')).addClass("header-ligth-trailer");
            });
            $(".color-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("color-dark-trailer");
               $("#"+$(this).attr('id')).addClass("color-ligth-trailer");
            });
            $(".background-dark-trailer").each(function(index) {
               $("#"+$(this).attr('id')).removeClass("background-dark-trailer");
               $("#"+$(this).attr('id')).addClass("background-ligth-trailer");
            });    
         }     
      }
   </script>
@endpush

@section('content')
   <div class="background-ligth2 uk-visible@s" id="main-div">
      <div class="header-course" uk-grid>
         <div class="uk-width-2-3">
            <div class="padding-div">
               <span class="badge badge-top-sales">TOP VENTAS</span>
            </div>

            <div class="color-ligth2 course-header-title" id="course-title">
               {{ $curso->title }}
            </div>

            <div class="color-ligth2 course-header-category" id="course-category">
               Categoría: <i class="{{ $curso->category->icon }}"></i> {{ $curso->category->title }}
            </div>

            <div class="padding-div">
               <ul class="list-inline">
                  {{--<li class="list-inline-item color-ligth2 course-header-item" id="course-item-1">
                     <i class="fa fa-star fa-fw"></i> {{ number_format($curso->promedio, 1) }} ({{ $curso->ratings_count }} Valoraciones)
                  </li>--}}
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-2">
                     <i class="fa fa-film fa-fw"></i> {{ $curso->lessons_count }} Lecciones
                  </li>
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-3">
                     <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                  </li>
               </ul>
            </div>
         </div>

         <div class="uk-width-1-3 uk-text-right">
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
            <a class="course-header-instructor-name" href="{{ route('landing.instructor.show-profile', [$instructor->slug, $instructor->id]) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a>
         </div>
      </div>

      <div class="background-ligth" id="course-content-tabs">
         <div uk-grid>
            {{-- Sección Izquierda --}}
            <div class="uk-width-2-3">
               <ul class="uk-tab uk-margin-remove-top" uk-tab>
                  @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($curso->status == 4) )
                     <li>
                        <a href="#" style="color: black;" id="review"> Revisión </a>
                     </li>
                  @endif 
                  <li aria-expanded="true" class="uk-active">
                     <a href="#" style="color: black;" id="presentation"> Descripción  </a>
                  </li>                 
                  <li>
                     <a href="#" style="color: black;" id="temary"> Temario </a>
                  </li>                 
                  <li>
                     <a href="#" style="color: black;" id="ratings"> Valoraciones </a>
                  </li>           
               </ul>
               <ul class="uk-switcher uk-margin uk-container-small">
                  @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($curso->status == 4) )
                     <li>
                        <div class="course-content-accordion">
                           {!! $curso->evaluation_review !!} 
                        </div>
                     </li>
                  @endif
                  <!-- Presentación-->
                  <li class="uk-active">
                     @if (!is_null($curso->preview))
                        <div class="video-responsive">
                           <video src="{{ $curso->preview }}" type="video/mp4" @if (!is_null($curso->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
                        </div>
                     @else
                        Este curso no posee un video resumen...
                     @endif
                     
                     <div class="course-content-accordion">
                        <ul uk-accordion>
                           <li>
                              <a class="uk-accordion-title" href="#" id="objectives-title" style="color: black;"><b>Objetivos</b></a>
                              <div class="uk-accordion-content color-ligth2" id="objectives">
                                 <p>{!! $curso->objectives !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="material-content-title" style="color: black;"><b>¿Qué incluye éste curso?</b></a>
                              <div class="uk-accordion-content color-ligth2" id="material-content">
                                 <p>{!! $curso->material_content !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="destination-title" style="color: black;"><b>¿A quién está dirigido?</b></a>
                              <div class="uk-accordion-content color-ligth2" id="destination">
                                 <p>{!! $curso->destination !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="requirements-title" style="color: black;"><b>Requisitos</b></a>
                              <div class="uk-accordion-content color-ligth2" id="requirements">
                                 <p>{!! $curso->requirements !!}</p>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </li>

                  <!-- Temario -->
                  <li>
                     <div class="uk-child-width-1-2" uk-grid>
                        <div class="uk-text-center color-ligth2" id="modules-item"><i class="fas fa-book-open fa-fw fa-temary"></i><br> {{ $curso->modules_count }} Módulos</div>
                        <div class="uk-text-center color-ligth2" id="resources-item"><i class="fas fa-file-download fa-fw fa-temary"></i><br> {{ $curso->resource_files_count }} Recursos Descargables</div>
                        <div class="uk-text-center color-ligth2" id="lessons-item"><i class="fas fa-book-reader fa-fw fa-temary"></i><br> {{ $curso->lessons_count }} Lecciones</div>
                        <div class="uk-text-center color-ligth2" id="audio-item"><i class="fa fa-volume-up fa-fw fa-temary"></i><br> Audio: Español</div>
                     </div>
                     <div class="course-content-accordion">
                        <ul uk-accordion>
                           @foreach ($curso->modules as $modulo)
                              <li>
                                 <a class="uk-accordion-title" href="#" id="module-title-{{$modulo->priority_order}}" style="color: black;"><b>Unidad {{ $modulo->priority_order }}: {{ $modulo->title }}</b></a>
                                 <div class="uk-accordion-content color-ligth2 accordion-content" id="objectives">
                                    <ul class="uk-list uk-list-divider">
                                       @foreach ($modulo->lessons as $leccion)
                                          <li class="color-ligth2" id="lesson-{{$leccion->id}}">
                                             <a>Lección {{$leccion->priority_order}}: {{ $leccion->title }}</a>
                                             @if (!Auth::guest())
                                                @if ($modulo->priority_order <= 2)
                                                   @if ($leccion->priority_order <= 2)
                                                      <a href="javascript:;" onclick="showLesson({{$leccion}});"><i class="fa fa-play-circle"></i> Ver Gratis</a>
                                                   @endif
                                                @endif
                                             @endif
                                          </li>
                                       @endforeach
                                    </ul>
                                 </div>
                              </li>
                           @endforeach
                        </ul>
                     </div>
                  </li>

                  <!-- Valoraciones -->
                  <li class="papd"> 
                     @if ($curso->ratings_count > 0)
                        @foreach ($curso->ratings as $valoracion)
                           <div uk-grid> 
                              <div class="uk-width-auto">
                                 @if ($valoracion->user_id != 0)
                                    <img class="uk-border-circle uk-align-center uk-box-shadow-large rating-img" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}">
                                 @else
                                    <img class="uk-border-circle uk-align-center uk-box-shadow-large rating-img" src="{{ asset('uploads/images/users/avatar.png') }}">
                                 @endif
                              </div>
                              <div class="uk-width-expand uk-padding-remove-left color-ligth2" id="rating-comment-{{$valoracion->id}}"> 
                                 <h6 class="uk-margin-remove">
                                    @if ($valoracion->user_id != 0)
                                       {{ $valoracion->user->names }} {{ $valoracion->user->last_names }}
                                    @else
                                       {{ $valoracion->name }}
                                    @endif
                                 </h6> 
                                 <span class="uk-text-small">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                                 <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $valoracion->comment }}</p> 
                              </div>                         
                           </div>
                        @endforeach
                     @else
                        El T-Course no tiene ninguna valoración aún...
                     @endif

                     <div class="uk-text-center course-content-accordion">
                        <h1 class="color-ligth2" id="rating-average">{{ number_format($curso->promedio, 2) }}</h1>
                        @if ($curso->avg[0] >= 1) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($curso->avg[0] >= 2) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($curso->avg[0] >= 3) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($curso->avg[0] >= 4) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($curso->avg[0] >= 5) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        <div class="color-ligth2" id="rating-label">Valoración del Curso</div>
                     </div>
                  </li>
               </ul>
            </div>
            
            {{-- Sección Derecha --}}
            <div class="uk-width-1-3 uk-text-center course-price-div">
               <div class="course-price-div-border">
                  <div class="color-ligth2" id="course-price">
                     @if ($curso->price > 0)
                        CO$ {{ number_format($curso->price, 0, ',', '.') }}
                     @else 
                        FREE
                     @endif
                  </div>
                  
                  <div class="uk-child-width-1-1 course-price-buttons-div" uk-grid>
                     <div>
                        @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                           @if ($curso->price > 0)
                              <a class="button-transformate button-aqua" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}">
                                 <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                              </a>
                           @else
                              <a class="button-transformate button-aqua" href="{{ route('students.courses.add', $curso->id) }}">
                                 <i class="fa fa-shopping-cart"></i> Tomar Curso
                              </a>
                           @endif
                        @endif
                        @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) )
                           <a class="button-transformate button-aqua" href="{{ route('instructors.discussions.group', ['course', $curso->slug, $curso->id]) }}">
                                 <i class="fa fa-comment"></i> Ir Al Foro
                              </a>
                        @endif
                     </div>
                     
                     @if (!is_null($curso->preview))
                        <div style="margin-top: 10px;">
                           <a class="button-transformate button-blue" href="javascript:;" onclick="loadPreview();" uk-tooltip="title: Video Resumen; delay: 200; pos: top; animation: uk-animation-scale-up">
                              <i class="fa fa-eye"></i> Vista Previa
                           </a>
                        </div>
                     @endif
                  </div>
                  
                  <div class="uk-text-left course-price-items-div">
                     <ul class="uk-list">
                        <li class="color-ligth2 course-header-item" id="list-item2-1">
                           <i class="fa fa-film fa-fw"></i> {{ $curso->lessons_count }} Lecciones
                        </li>
                        {{--<li class="color-ligth2 course-header-item" id="list-item2-2">
                           <i class="fa fa-star fa-fw"></i> {{ number_format($curso->promedio, 1) }} ({{ $curso->ratings_count }} Valoraciones)
                        </li>--}}
                        <li class="color-ligth2 course-header-item" id="list-item2-3">
                           <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                        </li>
                     </ul>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>

      <div class="course-instructor-section" uk-grid>
         <div class="uk-width-auto uk-text-center">
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
         </div>
         <div class="uk-width-expand uk-text-left" style="padding-top: 15px;">
            <a class="instructor-section-name" href="{{ route('landing.instructor.show-profile', [$instructor->slug, $instructor->id]) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a>
            <div class="color-ligth2" id="instructor-profession">
               {{ $instructor->profession }}
            </div>
            <div class="color-ligth2" id="instructor-review">
               {!! $instructor->review !!}
            </div>
         </div>
        
      </div>
   </div>

   <div class="background-ligth2 uk-hidden@s" id="main-div-movil">
      @if (!is_null($curso->preview))
         <div class="video-responsive">
            <video src="{{ $curso->preview }}" type="video/mp4" @if (!is_null($curso->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
         </div>
      @else
         Este curso no posee un video resumen...
      @endif

      <div class="color-ligth2 course-header-title" id="course-title-movil">
         {{ $curso->title }}
      </div>

      <div class="color-ligth2 course-header-category" id="course-category-movil">
         Categoría: <i class="{{ $curso->category->icon }}"></i> {{ $curso->category->title }}
      </div>

      <div class="padding-div">
         <ul class="list-inline">
            {{--<li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-1">
               <i class="fa fa-star fa-fw"></i> {{ number_format($curso->promedio, 1) }} ({{ $curso->ratings_count }} Valoraciones)
            </li>--}}
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-2">
               <i class="fa fa-film fa-fw"></i> {{ $curso->lessons_count }} Lecciones 
            </li>
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-3">
               <i class="fa fa-volume-up fa-fw"></i> Audio: Español
            </li>
         </ul>
      </div>

      <div class="color-ligth2 uk-text-center" id="course-price-movil">
         @if ($curso->price > 0)
            CO$ {{ number_format($curso->price, 0, ',', '.') }}
         @else 
            FREE
         @endif
      </div>
                  
      <div class="uk-child-width-1-1 course-price-buttons-div" uk-grid>
         <div>
            @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
               @if ($curso->price > 0)
                  <a class="button-transformate button-aqua" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}">
                     <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                  </a>
               @else
                  <a class="button-transformate button-aqua" href="{{ route('students.courses.add', $curso->id) }}">
                     <i class="fa fa-shopping-cart"></i> Tomar Curso
                  </a>
               @endif
            @endif
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) )
               <a class="button-transformate button-aqua" href="{{ route('instructors.discussions.group', ['course', $curso->slug, $curso->id]) }}">
                  <i class="fa fa-comment"></i> Ir Al Foro
               </a>
            @endif
         </div>
                     
         @if (!is_null($curso->preview))
            <div style="margin-top: 10px;">
               <a class="button-transformate button-blue" href="javascript:;" onclick="loadPreview();" uk-tooltip="title: Video Resumen; delay: 200; pos: top; animation: uk-animation-scale-up">
                  <i class="fa fa-eye"></i> Vista Previa
               </a>
            </div>
         @endif
      </div>
      
      <div class="background-ligth" id="accordion-section-movil">
         <ul class="uk-tab uk-margin-remove-top" uk-tab>
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($curso->status == 4))
               <li aria-expanded="true">
                  <a href="#" style="color: black; font-size: 13px;" id="revision-movil">Revisión</a>
               </li> 
            @endif
            <li aria-expanded="true" class="uk-active">
               <a href="#" style="color: black; font-size: 13px;" id="presentation-movil">Descripción</a>
            </li>                 
            <li>
               <a href="#" style="color: black; font-size: 13px;" id="temary-movil">Temario</a>
            </li>                 
            {{--<li>
               <a href="#" style="color: black; font-size: 13px;" id="ratings-movil">Valoraciones</a>
            </li>   --}}              
         </ul>
         <ul class="uk-switcher uk-margin uk-container-small">
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($curso->status == 4))
               <li>
                  <div class="course-content-accordion">
                     {!! $curso->evaluation_review !!}    
                  </div>
               </li>
            @endif
            <!-- Presentación-->
            <li class="uk-active">
               <div class="course-content-accordion">
                  <ul uk-accordion>
                     <li>
                        <a class="uk-accordion-title" href="#" id="objectives-title-movil" style="color: black;"><b>Objetivos</b></a>
                        <div class="uk-accordion-content color-ligth2" id="objectives-movil">
                           <p>{!! $curso->objectives !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="material-content-title-movil" style="color: black;"><b>¿Qué incluye éste curso?</b></a>
                        <div class="uk-accordion-content color-ligth2" id="material-content-movil">
                           <p>{!! $curso->material_content !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="destination-title-movil" style="color: black;"><b>¿A quién está dirigido?</b></a>
                        <div class="uk-accordion-content color-ligth2" id="destination-movil">
                           <p>{!! $curso->destination !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="requirements-title-movil" style="color: black;"><b>Requisitos</b></a>
                        <div class="uk-accordion-content color-ligth2" id="requirements-movil">
                           <p>{!! $curso->requirements !!}</p>
                        </div>
                     </li>
                  </ul>
               </div>
            </li>

            <!-- Temario -->
            <li>
               <div class="uk-child-width-1-2" uk-grid>
                  <div class="uk-text-center color-ligth2 modules-item-movil" id="modules-item-movil"><i class="fas fa-book-open fa-fw fa-temary"></i> {{ $curso->modules_count }} Módulos</div>
                  <div class="uk-text-center color-ligth2 modules-item-movil" id="resources-item-movil"><i class="fas fa-file-download fa-fw fa-temary"></i> {{ $curso->resource_files_count }} Recursos Descargables</div>
                  <div class="uk-text-center color-ligth2 modules-item-movil" id="lessons-item-movil"><i class="fas fa-book-reader fa-fw fa-temary"></i> {{ $curso->lessons_count }} Lecciones</div>
                  <div class="uk-text-center color-ligth2 modules-item-movil" id="audio-item-movil"><i class="fa fa-volume-up fa-fw fa-temary"></i> Audio: Español</div>
               </div>
               <div class="course-content-accordion">
                  <ul uk-accordion>
                     @foreach ($curso->modules as $modulo)
                        <li>
                           <a class="uk-accordion-title accordion-title-movil" href="#" id="module-title-movil-{{$modulo->priority_order}}" style="color: black;"><b>Unidad {{ $modulo->priority_order }}: {{ $modulo->title }}</b></a>
                           <div class="uk-accordion-content color-ligth2 accordion-content" id="content-movil">
                              <ul class="uk-list uk-list-divider">
                                 @foreach ($modulo->lessons as $leccion)
                                    <li class="color-ligth2" id="lesson-movil-{{$leccion->id}}">
                                       <a>Lección {{$leccion->priority_order}}: {{ $leccion->title }}</a>
                                       @if (!Auth::guest())
                                          @if ($modulo->priority_order <= 2)
                                             @if ($leccion->priority_order <= 2)
                                                <a href="javascript:;" onclick="showLesson({{$leccion}});"><i class="fa fa-play-circle"></i> Ver Gratis</a>
                                             @endif
                                          @endif
                                       @endif
                                    </li>
                                 @endforeach
                              </ul>
                           </div>
                        </li>
                     @endforeach
                  </ul>
               </div>
            </li>

            <!-- Valoraciones -->
            {{--<li class="papd"> 
               @if ($curso->ratings_count > 0)
                  @foreach ($curso->ratings as $valoracion)
                     <div uk-grid style="padding: 0 10px;"> 
                        <div class="uk-width-auto">
                           <img class="uk-border-circle uk-align-center uk-box-shadow-large rating-img" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}">
                        </div>
                        <div class="uk-width-expand uk-padding-remove-left color-ligth2" id="rating-comment-movil-{{$valoracion->id}}"> 
                           <h6 class="uk-margin-remove rating-user-movil">{{ $valoracion->user->names }} {{ $valoracion->user->last_names }}</h6> 
                           <span class="uk-text-small rating-date-movil">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                           <p class="uk-margin-remove-top uk-margin-small-bottom rating-comment-movil">{{ $valoracion->comment }}</p> 
                        </div>                         
                     </div>
                  @endforeach
               @else
                  El T-Course no tiene ninguna valoración aún...
               @endif

               <div class="uk-text-center course-content-accordion">
                  <h1 class="color-ligth2" id="rating-average-movil">{{ number_format($curso->promedio, 2) }}</h1>
                  @if ($curso->avg[0] >= 1) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($curso->avg[0] >= 2) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($curso->avg[0] >= 3) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($curso->avg[0] >= 4) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($curso->avg[0] >= 5) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  <div class="color-ligth2" id="rating-label-movil">Valoración del Curso</div>
               </div>
            </li>--}}
         </ul>
      </div>

      <div class="course-instructor-section" uk-grid>
         <div class="uk-width-auto uk-text-center">
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
         </div>
         <div class="uk-width-expand uk-text-left" style="padding-top: 15px;">
            <a class="instructor-section-name" href="{{ route('landing.instructor.show-profile', [$instructor->slug, $instructor->id]) }}">{{ $instructor->names }} {{ $instructor->last_names }}</a>
            <div class="color-ligth2" id="instructor-profession-movil">
               {{ $instructor->profession }}
            </div>
         </div>
         <div class="uk-width-1-1 color-ligth2" id="instructor-review-movil" style="margin-top: 5px;">
            {!! $instructor->review !!}
         </div>
      </div>
   </div>
   
    <!-- Modal de Vista Previa -->
   <div id="previewModal" uk-modal>
      <div class="uk-modal-dialog"> 
         <button class="uk-modal-close-default uk-margin-small-top uk-margin-small-right uk-light close-trailer" type="button" uk-close></button>                          
         <div class="modal-header-trailer header-ligth-trailer" id="header-trailer">
            <b class="uk-text-medium color-ligth-trailer" id="header-text-trailer">  Resumen</b>
         </div>
         <div class="video-responsive">
            <video  src="{{ $curso->preview }}" type="video/mp4" @if (!is_null($curso->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" @endif controls autoplay muted controlslist="nodownload" id="video-trailer"></video>
         </div>
         <div class="uk-modal-body modal-body-trailer background-ligth-trailer" id="body-trailer"> 
            <div class="title-trailer color-ligth-trailer" id="title-trailer">{{ $curso->title }}</div>
            <div class="instructor-trailer color-ligth-trailer" id="instructor-trailer">{{ $curso->user->names }} {{ $curso->user->last_names }}</div>
            <div class="subtitle-trailer color-ligth-trailer" id="subtitle-trailer">{{ $curso->subtitle }}</div>

            <div class="uk-child-width-1-1" uk-grid style="padding-top: 30px;">
               @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                  <div class="uk-text-center">
                     <button class="buttons-trailer btn-course-trailer"><a class="no-link" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Curso COP${{ number_format($curso->price, 0, ',', '.') }}</a></button>
                  </div>
               @endif    
               <div class="uk-text-center" style="margin-top: 10px; margin-bottom: 20px;">
                  <button class="buttons-trailer btn-show-trailer"><a class="no-link" href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}"><i class="fa fa-plus"></i>  Más Información</a></button>
               </div>           
            </div>
         </div> 
      </div>
   </div>

   <div id="lessonModal" uk-modal>
      <div class="uk-modal-dialog"> 
         <button class="uk-modal-close-default uk-margin-small-top uk-margin-small-right uk-light close-trailer2" type="button" uk-close></button>                          
         <div class="modal-header-trailer header-ligth-trailer" id="header-trailer2">
            <b class="uk-text-medium color-ligth-trailer" id="header-text-trailer2">  Video Lección</b>
         </div>
         <div class="video-responsive">
            <video src="" type="video/mp4" controls autoplay muted controlslist="nodownload" id="video-trailer2"></video>
         </div>
         <div class="uk-modal-body modal-body-trailer background-ligth-trailer" id="body-trailer2"> 
            <div class="title-trailer color-ligth-trailer" id="title-trailer2"></div>
         </div> 
      </div>
   </div>
@endsection