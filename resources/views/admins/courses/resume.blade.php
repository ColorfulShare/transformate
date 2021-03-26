@extends('layouts.admin')

@push('styles')
   <style>
      input[type="radio"] {
         display: none;
      }

      label {
         color: gray;
      }

      .clasificacion {
         direction: rtl;
         unicode-bidi: bidi-override;
      }

      label:hover,
      label:hover ~ label {
         color: #034A91;
      }

      input[type="radio"]:checked ~ label {
         color: #034A91;
      }

      .icon-star-rating{
         color: #034A91;
      }
      .icon-star-big{
         font-size: 22px;
      }
      .icon-star-small{
         font-size: 16px;
      }
   </style>
@endpush

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });
      });

      function showMoreRatings(){
         var route = $("#link-show-more").attr('data-route');
         $.ajax({
            url:route,
            type:'GET',
            success:function(ans){
               $("#ratings-content").html(ans);  
            }
         });
      }
   </script>
@endpush

@section('content')
   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: white;">
      <div class="course-details-div">
         <a href="{{ route('admins.courses.index') }}" class="link-back-to-courses">Volver a T-Cursos</a>
         <div class="course-details-title">{{ $curso->title }}</div>
         <div class="course-details-category"><i class="{{ $curso->category->icon }}"></i> {{ $curso->category->title }}</div>
         <div class="course-details-lessons"><i class="fas fa-info-circle"></i> {{ $curso->lessons_count }} Lecciones en Español</div>
         <div class="course-details-instructor">Por: {{ $curso->user->names }} {{ $curso->user->last_names }}</div>
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

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5; margin-top: 0px !important;">
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
                                          @if (!is_null($leccion->video))
                                             <div uk-lightbox>
                                                <a href="{{ $leccion->video }}"><i class="fas fa-play"></i> Lección {{$leccion->priority_order}}: {{ $leccion->title }} </a>
                                             </div>
                                          @else
                                             <a href="#"><i class="fas fa-exclamation-triangle"></i> Lección {{$leccion->priority_order}}: {{ $leccion->title }} </a>
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
               <a href="{{ route('admins.courses.index') }}" class="link-back-to-courses"><b>Volver a T-Cursos</b></a>
            </div>
         </div>
      </div>
      
      <div class="course-content-ratings">
         <div class="uk-card uk-card-default uk-card-body ratings-card">
            <div class="ratings-card-title">Valoraciones</div>
            <div class="ratings-card-content">
               <div>
                  @if ($promedio[0] >= 1) <i class="fas fa-star icon-star-rating icon-star-big"></i> @else <i class="far fa-star icon-star-rating icon-star-big"></i> @endif
                  @if ($promedio[0] >= 2) <i class="fas fa-star icon-star-rating icon-star-big"></i> @else <i class="far fa-star icon-star-rating icon-star-big"></i> @endif
                  @if ($promedio[0] >= 3) <i class="fas fa-star icon-star-rating icon-star-big"></i> @else <i class="far fa-star icon-star-rating icon-star-big"></i> @endif
                  @if ($promedio[0] >= 4) <i class="fas fa-star icon-star-rating icon-star-big"></i> @else <i class="far fa-star icon-star-rating icon-star-big"></i> @endif
                  @if ($promedio[0] >= 5) <i class="fas fa-star icon-star-rating icon-star-big"></i> @else <i class="far fa-star icon-star-rating icon-star-big"></i> @endif

                  <hr style="border: none; height: 1px; color: black; background-color: black;">
               </div>

               <div id="ratings-content" uk-scrollspy="target: > div; cls: uk-animation-slide-bottom; delay: 500" style="height: 500px; overflow-y: scroll;">
                  <div style="height: auto;">
                     @foreach ($valoraciones as $valoracion)
                        <div>
                           <div class="rating-title">
                              @if ($valoracion->user_id != 0)
                                 {{ $valoracion->user->names }} {{ $valoracion->user->last_names }}
                              @else
                                 {{ $valoracion->name }}
                              @endif
                           </div>
                           <div class="rating-date">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</div>
                           <div>
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
                              @if ($valoracion->points >= 1) <i class="fas fa-star icon-star-rating icon-star-small"></i> @else <i class="far fa-star icon-star-rating icon-star-small"></i> @endif
                           </div>
                           <div class="rating-comment">{{ $valoracion->comment }}</div>
                        </div>
                     @endforeach

                     @if ($totalValoraciones > 2)
                        <div class="uk-text-center" style="padding-top: 10px;"><a href="javascript:;" class="link-back-to-courses" id="link-show-more" onclick="showMoreRatings();" data-route="{{ route('admins.ratings.show-more', [$curso->id, 2]) }}"><b><i class="fas fa-search-plus"></i> Ver más...</b></a></div>
                     @endif
                  </div>
              </div>

            </div>
         </div>
      </div>
   </div>
@endsection