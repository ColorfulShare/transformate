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
   </script>
@endpush

@section('content')
   <div class="background-ligth2 uk-visible@s" id="main-div">
      <div class="header-course" uk-grid>
         <div class="uk-width-1-1">
            <div class="color-ligth2 course-header-title" id="course-title">
               {{ $clase->title }}
            </div>

            <div class="padding-div">
               <ul class="list-inline">
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-2">
                     <i class="fa fa-film fa-fw"></i> 1 Archivo de Video (45 Minutos)
                  </li>
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-3">
                     <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                  </li>
               </ul>
            </div>
         </div>
      </div>

      <div class="background-ligth" id="course-content-tabs">
         <div uk-grid>
            {{-- Sección Izquierda --}}
            <div class="uk-width-2-3">
               <ul class="uk-tab uk-margin-remove-top" uk-tab>
                  <li aria-expanded="true" class="uk-active">
                     <a href="#" style="color: black;" id="presentation"> Descripción  </a>
                  </li>   
                  @if (!Auth::guest())
                     <li aria-expanded="true">
                        <a href="#" style="color: black;" id="temary"> Contenido </a>
                     </li> 
                     @if ($clase->resources_count > 0)
                        <li>
                           <a href="#" style="color: black;" id="resources">Recursos Descargables</a>
                        </li> 
                     @endif
                  @endif       
               </ul>
               <ul class="uk-switcher uk-margin uk-container-small">
                  <!-- Presentación-->
                  <li class="uk-active">
                     <div><img src="{{ asset('uploads/images/master-class/'.$clase->cover) }}" alt=""><br>
                     </div>
                     
                     
                     <div class="course-content-accordion">
                        <ul uk-accordion>
                            <li>
                              <a class="uk-accordion-title" href="#" id="objectives-title" style="color: black;"><b>Reseña</b></a>
                              <div class="uk-accordion-content color-ligth2" id="objectives">
                                 <p>{!! $clase->review !!}</p>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </li>

                  @if (!Auth::guest())
                     <li>
                        <div class="uk-child-width-1-2" uk-grid>
                           <div class="uk-text-center color-ligth2" id="resources-item"><i class="fas fa-file-download fa-fw fa-temary"></i><br> {{ $clase->resources_count }} Recursos Descargables</div>
                           <div class="uk-text-center color-ligth2" id="audio-item"><i class="fa fa-volume-up fa-fw fa-temary"></i><br> Audio: Español</div>
                        </div>
                        <div class="course-content-accordion">
                           @if (!is_null($clase->video_file))
                              <div class="video-responsive">
                                 <video src="{{ $clase->video_file }}" type="video/mp4" @if (!is_null($clase->cover)) poster="{{ asset('uploads/images/master-class/'.$clase->cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
                              </div>
                           @else
                              Esta T-Master Class no posee un archivo de video...
                           @endif
                        </div>
                     </li>

                     @if ($clase->resources_count > 0)
                        <!-- Recursos Descargables -->
                        <li>
                           <div class="course-content-accordion">
                              <ul uk-accordion>
                                 <li>
                                    <ul class="uk-list uk-list-divider">
                                       @foreach ($clase->resources as $recurso)
                                          <li class="color-ligth2" id="recurso-{{$recurso->id}}">
                                             <a href="{{ $recurso->link }}" target="_blank">{{ $recurso->filename }}</a>
                                          </li>
                                       @endforeach
                                    </ul>
                                 </li>
                              </ul>
                           </div>
                        </li>
                     @endif
                  @endif
               </ul>
            </div>
            
            {{-- Sección Derecha --}}
            <div class="uk-width-1-3 uk-text-center course-price-div">
               <div class="course-price-div-border">
                  <div class="uk-text-left course-price-items-div">
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
      </div>
   </div>

   <div class="background-ligth2 uk-hidden@s" id="main-div-movil">
      <img src="{{ $clase->cover }}" alt=""><br>

      <div class="color-ligth2 course-header-title" id="course-title-movil">
         {{ $clase->title }}
      </div>

      <div class="padding-div">
         <ul class="list-inline">
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-2">
               <i class="fa fa-film fa-fw"></i> 1 Archivo de Video (45 Minutos)
            </li>
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-3">
               <i class="fa fa-volume-up fa-fw"></i> Audio: Español
            </li>
         </ul>
      </div>
      
      <div class="background-ligth" id="accordion-section-movil">
         <ul class="uk-tab uk-margin-remove-top" uk-tab>
            <li aria-expanded="true" class="uk-active">
               <a href="#" style="color: black; font-size: 13px;" id="presentation-movil">Descripción</a>
            </li>  
            @if (!Auth::guest())               
               <li>
                  <a href="#" style="color: black; font-size: 13px;" id="temary-movil">Temario</a>
               </li>
               @if ($clase->resources_count > 0)
                  <li>
                     <a href="#" style="color: black; font-size: 13px;" id="resources-movil">Recursos Descargables</a>
                  </li> 
               @endif
            @endif                 
         </ul>
         <ul class="uk-switcher uk-margin uk-container-small">
            <!-- Presentación-->
            <li class="uk-active">
               <div class="course-content-accordion">
                  <ul uk-accordion>
                     <li>
                        <a class="uk-accordion-title" href="#" id="objectives-title-movil" style="color: black;"><b>Reseña</b></a>
                        <div class="uk-accordion-content color-ligth2" id="objectives-movil">
                           <p>{!! $clase->review !!}</p>
                        </div>
                     </li>
                  </ul>
               </div>
            </li>

            @if (!Auth::guest())
               <!-- Temario -->
               <li>
                  <div class="uk-child-width-1-2" uk-grid>
                     <div class="uk-text-center color-ligth2 modules-item-movil" id="resources-item-movil"><i class="fas fa-file-download fa-fw fa-temary"></i> {{ $clase->resources_count }} Recursos Descargables</div>
                     <div class="uk-text-center color-ligth2 modules-item-movil" id="audio-item-movil"><i class="fa fa-volume-up fa-fw fa-temary"></i> Audio: Español</div>
                  </div>
                  <div class="course-content-accordion">
                     @if (!is_null($clase->video_file))
                        <div class="video-responsive">
                           <video src="{{ $clase->video_file }}" type="video/mp4" @if (!is_null($clase->cover)) poster="{{ asset('uploads/images/master-class/'.$clase->cover) }}" @endif controls autoplay muted controlslist="nodownload"></video>
                        </div>
                     @else
                        Esta T-Master Class no posee un archivo de video...
                     @endif
                  </div>
               </li>

               @if ($clase->resources_count > 0)
                  <!-- Recursos Descargables -->
                  <li>
                     <div class="course-content-accordion">
                        <ul class="uk-list uk-list-divider">
                           @foreach ($clase->resources as $recurso)
                              <li class="color-ligth2" id="recurso-{{$recurso->id}}">
                                 <a href="{{ $recurso->link }}" target="_blank">{{ $recurso->filename }}</a>
                              </li>
                           @endforeach
                        </ul>
                     </div>
                  </li>
               @endif
            @endif
         </ul>
      </div>
   </div>
@endsection