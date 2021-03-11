@extends('layouts.landing')

@push('styles')
   <link rel="stylesheet" type="text/css" href="{{ asset('css/courseDescription.css') }}">
@endpush

@section('fb-events')
   fbq('track', 'AddToCart');
@endsection

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });
         $('.close-trailer').on('click', function(){
            var vid = document.getElementById("video-trailer");
            vid.pause();
         });
      });
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
               {{ $podcast->title }}
            </div>

            <div class="color-ligth2 course-header-category" id="course-category">
               Categoría: <i class="{{ $podcast->category->icon }}"></i> {{ $podcast->category->title }}
            </div>

            <div class="padding-div">
               <ul class="list-inline">
                  {{--<li class="list-inline-item color-ligth2 course-header-item" id="course-item-1">
                     <i class="fa fa-star fa-fw"></i> {{ number_format($podcast->promedio, 1) }} ({{ $podcast->ratings_count }} Valoraciones)
                  </li>--}}
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-2">
                     <i class="fa fa-film fa-fw"></i> 1 Archivo de Audio
                  </li>
                  <li class="list-inline-item color-ligth2 course-header-item" id="course-item-3">
                     <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                  </li>
               </ul>
            </div>
         </div>

         <div class="uk-width-1-3 uk-text-right">
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$podcast->user->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
            <a class="course-header-instructor-name" href="{{ route('landing.instructor.show-profile', [$podcast->user->slug, $podcast->user->id]) }}">{{ $podcast->user->names }} {{ $podcast->user->last_names }}</a>
         </div>
      </div>

      <div class="background-ligth" id="course-content-tabs">
         <div uk-grid>
            {{-- Sección Izquierda --}}
            <div class="uk-width-2-3">
               <ul class="uk-tab uk-margin-remove-top" uk-tab>
                  @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($podcast->status == 4) )
                     <li>
                        <a href="#" style="color: black;" id="review"> Revisión </a>
                     </li>
                  @endif 
                  <li aria-expanded="true" class="uk-active">
                     <a href="#" style="color: black;" id="presentation"> Descripción  </a>
                  </li>                 
                  {{--<li>
                     <a href="#" style="color: black;" id="ratings"> Valoraciones </a>
                  </li>  --}}               
               </ul>
               <ul class="uk-switcher uk-margin uk-container-small">
                  @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($podcast->status == 4) )
                     <li>
                        <div class="course-content-accordion">
                           {!! $podcast->evaluation_review !!} 
                        </div>
                     </li>
                  @endif
                  <!-- Presentación-->
                  <li class="uk-active">
                     @if (!is_null($podcast->preview))
                        <div class="video-responsive">
                           <audio src="{{ $podcast->preview }}" type="audio/mp3" controls autoplay controlslist="nodownload"></audio>
                        </div>
                     @else
                        Este T-Book no posee un audio resumen...
                     @endif
                     
                     <div class="course-content-accordion">
                        <ul uk-accordion>
                           <li>
                              <a class="uk-accordion-title" href="#" id="inspired-title" style="color: black;"><b>Inspirado en</b></a>
                              <div class="uk-accordion-content color-ligth2" id="inspired">
                                 <p>{!! $podcast->inspired_in !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="objectives-title" style="color: black;"><b>Objetivos</b></a>
                              <div class="uk-accordion-content color-ligth2" id="objectives">
                                 <p>{!! $podcast->objectives !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="material-content-title" style="color: black;"><b>¿Qué incluye éste curso?</b></a>
                              <div class="uk-accordion-content color-ligth2" id="material-content">
                                 <p>{!! $podcast->material_content !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="destination-title" style="color: black;"><b>¿A quién está dirigido?</b></a>
                              <div class="uk-accordion-content color-ligth2" id="destination">
                                 <p>{!! $podcast->destination !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="importance-title" style="color: black;"><b>Importancia</b></a>
                              <div class="uk-accordion-content color-ligth2" id="importance">
                                 <p>{!! $podcast->importance !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="prologue-title" style="color: black;"><b>Prólogo</b></a>
                              <div class="uk-accordion-content color-ligth2" id="prologue">
                                 <p>{!! $podcast->prologue !!}</p>
                              </div>
                           </li>
                           <li>
                              <a class="uk-accordion-title" href="#" id="impact-title" style="color: black;"><b>Impacto Potencial</b></a>
                              <div class="uk-accordion-content color-ligth2" id="impact">
                                 <p>{!! $podcast->potential_impact !!}</p>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </li>

                  <!-- Valoraciones -->
                  <li class="papd"> 
                     @if ($podcast->ratings_count > 0)
                        @foreach ($podcast->ratings as $valoracion)
                           <div uk-grid> 
                              <div class="uk-width-auto">
                                 <img class="uk-border-circle uk-align-center uk-box-shadow-large rating-img" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}">
                              </div>
                              <div class="uk-width-expand uk-padding-remove-left color-ligth2" id="rating-comment-{{$valoracion->id}}"> 
                                 <h6 class="uk-margin-remove">{{ $valoracion->user->names }} {{ $valoracion->user->last_names }}</h6> 
                                 <span class="uk-text-small">{{ date('d-m-Y H:i A', strtotime("$valoracion->created_at -5 Hours")) }}</span> 
                                 <p class="uk-margin-remove-top uk-margin-small-bottom">{{ $valoracion->comment }}</p> 
                              </div>                         
                           </div>
                        @endforeach
                     @else
                        El T-Book no tiene ninguna valoración aún...
                     @endif
                     <div class="uk-text-center course-content-accordion">
                        <h1 class="color-ligth2" id="rating-average">{{ number_format($podcast->promedio, 2) }}</h1>
                        @if ($podcast->avg[0] >= 1) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($podcast->avg[0] >= 2) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($podcast->avg[0] >= 3) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($podcast->avg[0] >= 4) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        @if ($podcast->avg[0] >= 5) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                        <div class="color-ligth2" id="rating-label">Valoración del T-Book</div>
                     </div>
                  </li>
               </ul>
            </div>
            
            {{-- Sección Derecha --}}
            <div class="uk-width-1-3 uk-text-center course-price-div">
               <div class="course-price-div-border">
                  <div class="color-ligth2" id="course-price">
                     @if ($podcast->price > 0)
                        CO$ {{ number_format($podcast->price, 0, ',', '.') }}
                     @else 
                        FREE
                     @endif
                  </div>
                  
                  <div class="uk-child-width-1-1 course-price-buttons-div" uk-grid>
                     <div>
                        @if (Auth::guest()) 
                           <a class="button-transformate button-aqua" ref="#modal-login" uk-toggle>
                              <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                           </a>
                        @elseif (Auth::user()->role_id == 1) 
                           @if ($podcast->price > 0)
                              <a class="button-transformate button-aqua" href="{{ route('landing.shopping-cart.store', [$podcast->id, 'podcast']) }}">
                                 <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                              </a>
                           @else
                              <a class="button-transformate button-aqua" href="{{ route('students.podcasts.add', $podcast->id) }}">
                                 <i class="fa fa-shopping-cart"></i> Añadir a Mis T-Books
                              </a>
                           @endif
                        @endif
                        @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) )
                           <a class="button-transformate button-aqua" href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}">
                                 <i class="fa fa-comment"></i> Ir Al Foro
                              </a>
                        @endif
                     </div>
                  </div>
                  
                  <div class="uk-text-left course-price-items-div">
                     <ul class="uk-list">
                        <li class="color-ligth2 course-header-item" id="list-item2-1">
                           <i class="fa fa-film fa-fw"></i> 1 Archivo de Audio
                        </li>
                        {{--<li class="color-ligth2 course-header-item" id="list-item2-2">
                           <i class="fa fa-star fa-fw"></i> {{ number_format($podcast->promedio, 1) }} ({{ $podcast->ratings_count }} Valoraciones)
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
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$podcast->user->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
         </div>
         <div class="uk-width-expand uk-text-left" style="padding-top: 15px;">
            <a class="instructor-section-name" href="{{ route('landing.instructor.show-profile', [$podcast->user->slug, $podcast->user->id]) }}">{{ $podcast->user->names }} {{ $podcast->user->last_names }}</a>
            <div class="color-ligth2" id="instructor-profession">
               {{ $podcast->user->profession }}
            </div>
            <div class="color-ligth2" id="instructor-review">
               {!! $podcast->user->review !!}
            </div>
         </div>
        
      </div>
   </div>

   <div class="background-ligth2 uk-hidden@s" id="main-div-movil">
      @if (!is_null($podcast->preview))
         <div class="video-responsive">
            <audio src="{{ $podcast->preview }}" type="audio/mp3" controls controlslist="nodownload"></audio>
         </div>
      @else
         Este T-Book no posee un audio resumen...
      @endif

      <div class="color-ligth2 course-header-title" id="course-title-movil">
         {{ $podcast->title }}
      </div>

      <div class="color-ligth2 course-header-category" id="course-category-movil">
         Categoría: <i class="{{ $podcast->category->icon }}"></i> {{ $podcast->category->title }}
      </div>

      <div class="padding-div">
         <ul class="list-inline">
            {{--<li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-1">
               <i class="fa fa-star fa-fw"></i> {{ number_format($podcast->promedio, 1) }} ({{ $podcast->ratings_count }} Valoraciones)
            </li>--}}
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-2">
               <i class="fa fa-film fa-fw"></i> 1 Archivo de Audio
            </li>
            <li class="list-inline-item color-ligth2 course-header-item" id="course-item-movil-3">
               <i class="fa fa-volume-up fa-fw"></i> Audio: Español
            </li>
         </ul>
      </div>

      <div class="color-ligth2 uk-text-center" id="course-price-movil">
         @if ($podcast->price > 0)
            CO$ {{ number_format($podcast->price, 0, ',', '.') }}
         @else 
            FREE
         @endif
      </div>
                  
      <div class="uk-child-width-1-1 course-price-buttons-div" uk-grid>
         <div>
            @if (Auth::guest())
               <a class="button-transformate button-aqua" ref="#modal-login" uk-toggle>
                  <i class="fa fa-shopping-cart"></i> Añadir al Carrito
               </a>
            @elseif (Auth::user()->role_id == 1) 
               @if ($podcast->price > 0)
                  <a class="button-transformate button-aqua" href="{{ route('landing.shopping-cart.store', [$podcast->id, 'podcast']) }}">
                     <i class="fa fa-shopping-cart"></i> Añadir al Carrito
                  </a>
               @else
                  <a class="button-transformate button-aqua" href="{{ route('students.podcasts.add', $podcast->id) }}">
                     <i class="fa fa-shopping-cart"></i> Añadir a Mis T-Books
                  </a>
                @endif
            @endif
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) )
               <a class="button-transformate button-aqua" href="{{ route('instructors.discussions.group', ['podcast', $podcast->slug, $podcast->id]) }}">
                  <i class="fa fa-comment"></i> Ir Al Foro
               </a>
            @endif
         </div>
      </div>
      
      <div class="background-ligth" id="accordion-section-movil">
         <ul class="uk-tab uk-margin-remove-top" uk-tab>
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($podcast->status == 4))
               <li aria-expanded="true">
                  <a href="#" style="color: black; font-size: 13px;" id="revision-movil">Revisión</a>
               </li> 
            @endif
            <li aria-expanded="true" class="uk-active">
               <a href="#" style="color: black; font-size: 13px;" id="presentation-movil">Descripción</a>
            </li>                
            {{--<li>
               <a href="#" style="color: black; font-size: 13px;" id="ratings-movil">Valoraciones</a>
            </li> --}}                
         </ul>
         <ul class="uk-switcher uk-margin uk-container-small">
            @if ( (!Auth::guest()) && (Auth::user()->role_id == 2) && ($podcast->status == 4))
               <li>
                  <div class="course-content-accordion">
                     {!! $podcast->evaluation_review !!}    
                  </div>
               </li>
            @endif
            <!-- Presentación-->
            <li class="uk-active">
               <div class="course-content-accordion">
                  <ul uk-accordion>
                     <li>
                        <a class="uk-accordion-title" href="#" id="inspired-title-movil" style="color: black;"><b>Inspirado en</b></a>
                        <div class="uk-accordion-content color-ligth2" id="inspired-movil">
                           <p>{!! $podcast->inspired_in !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="objectives-title-movil" style="color: black;"><b>Objetivos</b></a>
                        <div class="uk-accordion-content color-ligth2" id="objectives-movil">
                           <p>{!! $podcast->objectives !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="material-content-title-movil" style="color: black;"><b>¿Qué incluye éste T-Book?</b></a>
                        <div class="uk-accordion-content color-ligth2" id="material-content-movil">
                           <p>{!! $podcast->material_content !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="destination-title-movil" style="color: black;"><b>¿A quién está dirigido?</b></a>
                        <div class="uk-accordion-content color-ligth2" id="destination-movil">
                           <p>{!! $podcast->destination !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="importance-title-movil" style="color: black;"><b>Importancia</b></a>
                        <div class="uk-accordion-content color-ligth2" id="importance-movil">
                           <p>{!! $podcast->importance !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="prologue-title-movil" style="color: black;"><b>Prólogo</b></a>
                        <div class="uk-accordion-content color-ligth2" id="prologue-movil">
                           <p>{!! $podcast->prologue !!}</p>
                        </div>
                     </li>
                     <li>
                        <a class="uk-accordion-title" href="#" id="impact-title-movil" style="color: black;"><b>Impacto Potencial</b></a>
                        <div class="uk-accordion-content color-ligth2" id="impact-movil">
                           <p>{!! $podcast->potential_impact !!}</p>
                        </div>
                     </li>
                  </ul>
               </div>
            </li>

            <!-- Valoraciones -->
            <li class="papd"> 
               @if ($podcast->ratings_count > 0)
                  @foreach ($podcast->ratings as $valoracion)
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
                  El T-Book no tiene ninguna valoración aún...
               @endif
               <div class="uk-text-center course-content-accordion">
                  <h1 class="color-ligth2" id="rating-average-movil">{{ number_format($podcast->promedio, 2) }}</h1>
                  @if ($podcast->avg[0] >= 1) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($podcast->avg[0] >= 2) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($podcast->avg[0] >= 3) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($podcast->avg[0] >= 4) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  @if ($podcast->avg[0] >= 5) <i class="fas fa-star fa-rating"></i> @else <i class="far fa-star fa-rating"></i> @endif
                  <div class="color-ligth2" id="rating-label-movil">Valoración del Curso</div>
               </div>
            </li>
         </ul>
      </div>

      <div class="course-instructor-section" uk-grid>
         <div class="uk-width-auto uk-text-center">
            <img class="uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up course-header-instructor-img" src="{{ asset('uploads/images/users/'.$podcast->user->avatar) }}">
            <div>
               <span class="badge badge-pro">PROFESOR</span> <span class="badge badge-pro">PRO</span>
            </div>
         </div>
         <div class="uk-width-expand uk-text-left" style="padding-top: 15px;">
            <a class="instructor-section-name" href="{{ route('landing.instructor.show-profile', [$podcast->user->slug, $podcast->user->id]) }}">{{ $podcast->user->names }} {{ $podcast->user->last_names }}</a>
            <div class="color-ligth2" id="instructor-profession-movil">
               {{ $podcast->user->profession }}
            </div>
         </div>
         <div class="uk-width-1-1 color-ligth2" id="instructor-review-movil" style="margin-top: 5px;">
            {!! $podcast->user->review !!}
         </div>
      </div>
   </div>
@endsection