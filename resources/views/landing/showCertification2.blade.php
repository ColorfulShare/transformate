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
            <a href="{{ route('landing.courses', ['t-mentorings', 'tmentorings']) }}" class="link-back-to-courses">Volver a T-Mentorings</a>
         @else
            <a href="{{ route('instructors.certifications.index') }}" class="link-back-to-courses">Volver a Mis T-Mentorings</a>
         @endif
         <div class="course-details-title">{{ $certificacion->title }}</div>
         <div class="course-details-category"><i class="{{ $certificacion->category->icon }}"></i> {{ $certificacion->category->title }}</div>
         <div class="course-details-lessons"><i class="fas fa-info-circle"></i> {{ $certificacion->lessons_count }} Lecciones en Español</div>
         <div class="course-details-instructor">Por: {{ $certificacion->user->names }} {{ $certificacion->user->last_names }}</div>
         <div class="course-details-price">COP {{ number_format($certificacion->price, 0, '.', ',') }}</div>

         @if (Auth::guest())
            <a type="button" href="#modal-login" uk-toggle class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir certificacion al carrito</a>
         @elseif (Auth::user()->role_id == 1)
            <a type="button" href="{{ route('landing.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir T-Mentoring al carrito</a>
         @endif
      </div>

      <div class="course-preview-div">
         @if (!is_null($certificacion->preview))
            <div class="video-responsive">
               <video src="{{ $certificacion->preview }}" type="video/mp4" @if (!is_null($certificacion->preview_cover)) poster="{{ asset('uploads/images/certifications/preview_covers/'.$certificacion->preview_cover) }}" @endif controls controlslist="nodownload" class="course-preview-video"></video>
            </div>
         @else
            Esta mentoría no posee un video resumen...
         @endif
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5;">
      <div class="course-content-div">
         <div class="course-content-subtitle">{{ $certificacion->subtitle }}</div>

         <div class="course-accordion">
            <ul uk-accordion>
               <li class="uk-open">
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Objetivos</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $certificacion->objectives !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>¿A quién está dirigido?</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $certificacion->destination !!}</p>
                  </div>
               </li> 
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Temario</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <ul uk-accordion>
                        @foreach ($certificacion->modules as $modulo)
                           <li>
                              <a class="uk-accordion-title uk-accordion-title2 module-accordion-title" href="#">Unidad {{ $modulo->priority_order }}: {{ $modulo->title }}</a>
                              <div class="uk-accordion-content">
                                 <ul class="uk-list uk-list-divider">
                                    @foreach ($modulo->lessons as $leccion)
                                       <li style="font-size: 18px; font-weight: 400; color: #0B132B !important; padding: 0 0 !important; margin: 0 0 !important">
                                          <a>Lección {{$leccion->priority_order}}: {{ $leccion->title }}</a>
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
                     <p>{!! $certificacion->requirements !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Sobre el instructor</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <p>{{ $certificacion->user->review }}</p>
                  </div>
               </li>
            </ul>

            <div style="padding-top: 30px;">
               @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
                  <a href="{{ route('landing.courses', ['t-mentorings', 'tmentorings']) }}" class="link-back-to-courses">Volver a T-Mentorings</a>
               @else
                  <a href="{{ route('instructors.certifications.index') }}" class="link-back-to-courses">Volver a Mis Mentorías</a>
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