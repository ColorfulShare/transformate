@extends('layouts.landing')

@push('scripts')
   <script>
      $(function(){    
         $('.audio-div').bind('contextmenu',function() { return false; });

         $('.close-trailer2').on('click', function(){
            var vid = document.getElementById("video-trailer2");
            vid.pause();
         });
      });
   </script>
@endpush

@section('content')
   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
      <div class="course-details-div">
         @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
            <a href="{{ route('landing.courses', ['t-books', 'tbooks']) }}" class="link-back-to-courses">Volver a T-Libros</a>
         @else
            <a href="{{ route('landing.courses', ['t-books', 'tbooks']) }}" class="link-back-to-courses">Volver a T-Libros</a>
         @endif
         <div class="course-details-title">{{ $podcast->title }}</div>
         <div class="course-details-category"><i class="{{ $podcast->category->icon }}"></i> {{ $podcast->category->title }}</div>
         <div class="course-details-instructor">Posdfdgfgr: {{ $podcast->user->names }} {{ $podcast->user->last_names }}</div>
         <div class="course-details-price">COP {{ number_format($podcast->price, 0, '.', ',') }}</div>

         @if (Auth::guest())
            <a type="button" href="#modal-login" uk-toggle class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir T-Libro al carrito</a>
         @elseif (Auth::user()->role_id == 1)
            <a type="button" href="{{ route('landing.shopping-cart.store', [$podcast->id, 'podcast']) }}" class="uk-button courses-button-blue"><i class="fa fa-shopping-cart"></i> Añadir T-Curso al carrito</a>
         @endif
      </div>

      <div class="course-preview-div">
         @if (!is_null($podcast->preview))
            <div class="audio-div" style="padding: 50px 100px 0px 100px;">
               <img src="https://transformatepro.com/uploads/images/podcasts/{{ $podcast->cover }}" alt="">
               <audio src="{{ $podcast->preview }}" type="audio/mp3" controls draggable controlslist="nodownload" style="height: 50px;"></audio>  
            </div>
         @else
            Este T-Libro no posee un audio resumen...
         @endif
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5;">
      <div class="course-content-div">
         <div class="course-content-subtitle">{{ $podcast->subtitle }}</div>

         <div class="course-accordion">
            <ul uk-accordion>
               <li class="uk-open">
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
               <a href="{{ route('landing.courses', ['t-books', 'tbooks']) }}" class="link-back-to-courses"><b>Volver a T-Libros</b></a>
            </div>
         </div>
      </div>
      
      <div class="course-content-ratings">
         <div class="uk-card uk-card-default uk-card-body ratings-card">
            <div class="ratings-card-title">Valoraciones</div>
            <div class="ratings-card-content">
                  Adquiere y se el primero en valorar este podcast.
            </div>
         </div>
      </div>
   </div>
@endsection