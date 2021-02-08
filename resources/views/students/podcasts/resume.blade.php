@extends('layouts.student2')

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
         $('.audio-div').bind('contextmenu',function() { return false; });
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

      function cargarModalValoracion(){
         document.getElementById("podcast_id").value = "{{ $podcast->id }}";
         UIkit.modal("#modalValorar").show();
      }
   </script>
@endpush

@section('content')
   @if (Session::has('msj'))
      <div id="note">
         ¡¡Felicidades!! Has comprado el podcast de forma exitosa. ¡Disfrútalo! 
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
      </div>
   @endif

   @if (Session::has('msj-exitoso'))
      <div id="note">
         El código de regalo ha sido aplicado con exito. ¡¡Disfrute su nuevo T-Libro!!
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
      </div>
   @endif

   @if (Session::has('msj-exitoso-rating'))
      <div id="note">
         {{ Session::get('msj-exitoso-rating') }}
         <a class="uk-margin-medium-right uk-margin-remove-bottom uk-position-relative uk-icon-button uk-align-right  uk-margin-small-top" uk-toggle="target: #note; cls:uk-hidden"> <i class="fas fa-times  uk-position-center"></i> </a>
      </div>
   @endif

   @include('students.ratings.createModal')

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: white;">
      <div class="course-details-div">
         <a href="{{ route('students.my-content') }}" class="link-back-to-courses">Volver a Mis Cursos</a>
         <div class="course-details-title">{{ $podcast->title }}</div>
         <div class="course-details-category"><i class="{{ $podcast->category->icon }}"></i> {{ $podcast->category->title }}</div>
         <div class="course-details-lessons"><i class="fas fa-info-circle"></i> {{ $podcast->lessons_count }} Lecciones en Español</div>
         <div class="course-details-instructor">Por: {{ $podcast->user->names }} {{ $podcast->user->last_names }}</div>
         
         @if (is_null($miValoracion))
            <div class="course-details-buttons">
               <a type="button" class="uk-button courses-button-blue" href="javascript:;" onclick="cargarModalValoracion();"><i class="fa fa-comments"></i> Dejar Valoración</a>  
            </div>
         @endif
      </div>

      <div class="course-preview-div">
         @if (!is_null($podcast->preview))
            <div class="audio-div" style="padding: 50px 100px 30px 100px;">
               <img src="https://transformatepro.com/uploads/images/podcasts/{{ $podcast->cover }}" alt="">
               <audio src="{{ $podcast->preview }}" type="audio/mp3" controls draggable controlslist="nodownload" style="height: 50px;"></audio>  
            </div>
         @else
            Este T-Libro no posee un audio resumen...
         @endif
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5; margin-top: 0px;">
      <div class="course-content-div">
         <div class="course-content-subtitle">{{ $podcast->subtitle }}</div>

         <div class="course-accordion">
            <ul uk-accordion>
               <li class="uk-open">
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>T-Libro</b></a>

                  @if (!is_null($podcast->audio_file))
                     <div class="audio-div">
                        <img src="https://transformatepro.com/uploads/images/podcasts/{{ $podcast->cover }}" alt="">
                        <audio src="{{ $podcast->audio_file }}" type="audio/mp3" controls draggable controlslist="nodownload" style="height: 50px;"></audio>  
                     </div>
                  @else 
                     <i class="fas fa-exclamation-circle icon-small"></i> Sin Archivo de Audio
                  @endif
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Objetivos</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $podcast->objectives !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>¿A quién está dirigido?</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $podcast->destination !!}</p>
                  </div>
               </li> 
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>¿Qué incluye?</b></a>
                  <div class="uk-accordion-content course-accordion-content">
                     <p>{!! $podcast->material_content !!}</p>
                  </div>
               </li>      
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Importancia</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <p>{!! $podcast->importance !!}</p>
                  </div>
               </li>
               <li>
                  <a class="uk-accordion-title course-accordion-title" href="#"><b>Sobre el instructor</b></a>
                  <div class="uk-accordion-content course-accordion-content" >
                     <p>{{ $podcast->user->review }}</p>
                  </div>
               </li>
            </ul>

            <div style="padding-top: 30px;">
               <a href="{{ route('students.my-content') }}" class="link-back-to-courses"><b>Volver a Mis Cursos</b></a>
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
                        <div class="uk-text-center" style="padding-top: 10px;"><a href="javascript:;" class="link-back-to-courses" id="link-show-more" onclick="showMoreRatings();" data-route="{{ route('students.ratings.show-more', [$podcast->id, 2, 'podcast']) }}"><b><i class="fas fa-search-plus"></i> Ver más...</b></a></div>
                     @endif
                  </div>
              </div>

            </div>
         </div>
      </div>
   </div>
@endsection