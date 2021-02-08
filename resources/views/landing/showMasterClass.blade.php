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
   </script>
@endpush

@section('content')
   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
      <div class="course-details-div">
         <a href="{{ route('landing.courses', ['t-master-class', 100]) }}" class="link-back-to-courses">Volver a T-Master Class</a>
         <div class="course-details-title">{{ $clase->title }}</div>
         <div class="course-details-instructor">{{ $clase->subtitle }}</div>
      </div>

      <div class="course-preview-div">
         <div class="audio-div" style="padding: 50px 100px 0px 100px;">
            <img src="https://transformatepro.com/uploads/images/master-class/{{ $clase->cover }}" alt="">
         </div>
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5;">
      <div class="course-content-div">
         <div class="course-content-subtitle">{!! $clase->review !!}</div>

         @if (!Auth::guest())
            <div class="video-responsive">
               <video src="{{ $clase->video_file }}" type="video/mp4" @if (!is_null($clase->cover)) poster="{{ asset('uploads/images/master-class/'.$clase->cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
            </div>
         @else
            Debes iniciar sesión o registrarte para poder disfrutar de la clase...
         @endif

         <div style="padding-top: 30px;">
            <a href="{{ route('landing.courses', ['t-master-class', 100]) }}" class="link-back-to-courses"><b>Volver a T-Master Class</b></a>
         </div>
      </div>
      
      <div class="course-content-ratings">
         <div class="uk-card uk-card-default uk-card-body ratings-card">
            <div class="ratings-card-title">Detalles</div>
            <div class="ratings-card-content">
               <ul class="uk-list">
                  <li class="color-ligth2 course-header-item" id="list-item2-1">
                     <i class="fa fa-film fa-fw"></i> 1 Archivo de Video (45 Minutos)
                  </li>
                  <li class="color-ligth2 course-header-item" id="list-item2-3">
                     <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
@endsection