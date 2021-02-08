@extends('layouts.landing')

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });

         $('.close-trailer2').on('click', function(){
            var vid = document.getElementById("video-trailer2");
            vid.pause();
         });
      });

      function showLesson($lesson){
         $("#video-trailer2").attr('src', $lesson.video);
         $("#title-trailer2").empty();
         $("#title-trailer2").append($lesson.title);
         modal = UIkit.modal("#lessonModal");
         modal.show();
      }
   </script>
@endpush

@section('content')
   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
      <div class="course-details-div">
         @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
            <a href="{{ route('landing.courses') }}" class="link-back-to-courses">Volver a T-Cursos</a>
         @else
            <a href="{{ route('instructors.courses.index') }}" class="link-back-to-courses">Volver a Mis Cursos</a>
         @endif
         <div class="course-details-title">{{ $curso->title }}</div>
         <div class="course-details-category"><i class="{{ $curso->category->icon }}"></i> {{ $curso->category->title }}</div>
         <div class="course-details-lessons"><i class="fas fa-info-circle"></i> {{ $curso->lessons_count }} Lecciones en Español</div>
         <div class="course-details-instructor">Por: {{ $curso->user->names }} {{ $curso->user->last_names }}</div>
         <div class="course-details-price">COP {{ number_format($curso->price, 0, '.', ',') }}</div>

         @if (Auth::guest())
            <a type="button" href="#modal-login" uk-toggle class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir curso al carrito</a>
         @elseif (Auth::user()->role_id == 1)
            @if (!is_null(Auth::user()->membership_id))
               @if (Auth::user()->membership_courses < 3)
                  <a type="button" href="{{ route('students.courses.add', $curso->id) }}" class="uk-button courses-button-blue"><i class="fa fa-list"></i> Añadir a mis cursos</a>
               @else
                  <a type="button" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}" class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir T-Curso al carrito</a>
               @endif
            @else
               <a type="button" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}" class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir T-Curso al carrito</a>
            @endif
         @endif
      </div>

      <div class="course-preview-div">
         @if (!is_null($curso->preview))
            <div class="video-responsive">
               <video src="{{ $curso->preview }}" type="video/mp4" @if (!is_null($curso->preview_cover)) poster="{{ asset('uploads/images/courses/preview_covers/'.$curso->preview_cover) }}" @endif controls controlslist="nodownload" class="course-preview-video"></video>
            </div>
         @else
            Este curso no posee un video resumen...
         @endif
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5;">
      <div class="course-content-div">
         <div class="course-content-subtitle">{{ $curso->subtitle }}</div>

         <div class="course-accordion">
            <ul uk-accordion>
               <li class="uk-open">
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Objetivos</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $curso->objectives !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>¿A quién está dirigido?</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $curso->destination !!}</p>
                  </div>
               </li> 
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Temario</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <ul uk-accordion>
                        @foreach ($curso->modules as $modulo)
                           <li>
                              <a class="uk-accordion-title uk-accordion-title2 module-accordion-title" href="#">Unidad {{ $modulo->priority_order }}: {{ $modulo->title }}</a>
                              <div class="uk-accordion-content">
                                 <ul class="uk-list uk-list-divider">
                                    @foreach ($modulo->lessons as $leccion)
                                       <li style="font-size: 18px; font-weight: 400; color: #0B132B !important; padding: 0 0 !important; margin: 0 0 !important">
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
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Requisitos</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <p>{!! $curso->requirements !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Sobre el instructor</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <p>{{ $curso->user->review }}</p>
                  </div>
               </li>
            </ul>

            <div style="padding-top: 30px;">
               @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                  <a href="{{ route('landing.courses') }}" class="link-back-to-courses">Volver a T-Cursos</a>
               @else
                  <a href="{{ route('instructors.courses.index') }}" class="link-back-to-courses">Volver a Mis Cursos</a>
               @endif
            </div>
         </div>
      </div>
      
      <div class="course-content-ratings">
         <div class="uk-card uk-card-default uk-card-body ratings-card">
            <div class="ratings-card-title">Valoraciones</div>
            <div class="ratings-card-content">
                  Adquiere y se el primero en valorar este curso.
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