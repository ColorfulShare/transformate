@extends('layouts.landingOld')

@push('scripts')
   <script>
      function load_courses($categoria){
         document.getElementById("cursos").style.display = 'none';
         document.getElementById("wait").style.display = 'block';

         var url = {{ $www }};
         if (url == 1){
            var path = "https://www.transformatepro.com/ajax/load-courses-by-category/"+$categoria;
         }else{
            var path = "https://transformatepro.com/ajax/load-courses-by-category/"+$categoria;
         }

         //var path = "http://localhost:8000/ajax/load-courses-by-category/"+$categoria;
                        
         $.ajax({
            type:"GET",
            url:path,
            success:function(ans){
               $("#main-content").html(ans);     
               if (document.getElementById("tema").value == 'dark'){
                  $(".color-ligth2").each(function(index) {
                     $("#"+$(this).attr('id')).removeClass("color-ligth2");
                     $("#"+$(this).attr('id')).addClass("color-dark2");
                  });
                  $(".background-ligth2").each(function(index) {
                     $("#"+$(this).attr('id')).removeClass("background-ligth2");
                     $("#"+$(this).attr('id')).addClass("background-dark2");
                  });
               }                  
            }
         });
      }
   </script>
@endpush

@section('content')
   @if ($errors->any())
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif

   @if (Session::has('msj-exitoso'))
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-success">
            <strong>{{ Session::get('msj-exitoso') }}</strong>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif

   @if (Session::has('msj-erroneo'))
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-danger">
            <strong>{{ Session::get('msj-erroneo') }}</strong>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif
   
   
   @if ($cantEventos > 0)
      <div class="header-background-ligth" id="t-events" style="padding: 20px 5% 0px 5%;">
         {{-- Versión Escritorio --}}
         <div uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative uk-visible-toggle uk-light uk-visible@s" tabindex="-1">
               <ul class="uk-slider-items uk-grid">
                  @foreach ($eventos as $evento)
                     <li class="uk-width-1-1">
                        <div class="uk-panel">
                           <a href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}"><img src="{{ asset('uploads/events/images/'.$evento->image) }}" alt="{{ $evento->title }}"></a>
                        </div>
                     </li>
                  @endforeach
               </ul>
               <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous" style="color: black !important;"></a>
               <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next" style="color: black !important;"></a>
            </div>

            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
         </div>
         {{-- Versión Móvil --}}
         <div uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative uk-visible-toggle uk-light uk-hidden@s" tabindex="-1">
               <ul class="uk-slider-items uk-grid">
                  @foreach ($eventos as $evento2)
                     <li class="uk-width-1-1">
                        <div class="uk-panel">
                        <a href="{{ route('landing.events.show', [$evento2->slug, $evento2->id]) }}"><img src="{{ asset('uploads/events/images/'.$evento2->image_movil) }}" alt="{{ $evento2->title }}"></a>
                        </div>
                     </li>
                  @endforeach
               </ul>
               <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous" style="color: black !important;"></a>
               <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next" style="color: black !important;"></a>
            </div>

         </div>
         <div class="uk-width-1-1 uk-text-center more-courses-div">
            <a class="more-courses-button" href="{{ route('landing.events') }}"> Ver T-Events</a>
         </div>
      </div>
   @endif

   {{-- Sección Botones --}}
   <div class="t-mentor" style="padding: 20px 5% 20px 5%; ">
      <a href="{{ route('landing.t-member') }}" class="uk-visible@s"><img src="https://www.transformatepro.com/images/miembro-pro-10-10.jpg" /></a>
      <a href="{{ route('landing.t-member') }}" class="uk-hidden@s"><img src="https://www.transformatepro.com/images/miembro-pro-11-11.jpg" /></a>
      <div class="course-pro">
         <!--<div class="uk-visible@s">
            <div class="uk-text-bold title">TransfórmatePRO expande tu ser</div>
            <div class="description">Más que educación online, una comunidad de transformadores. <br>Conoce nuestro curso PRO del mes.</div>
         </div>
         <div class="uk-hidden@s">
            <div class="uk-text-bold title">TransfórmatePRO<br> expande tu ser</div>
         </div>
         
         <div class="t-mentor-button-div uk-visible@s">
            <div uk-grid >
               <div class="uk-width-1-2 course-button-white-div" style="text-align: right;">
                  <a class="courses-button-white" href="{{ route('landing.courses') }}">Ver Los Cursos</a>
               </div>
               <div class="uk-width-1-2 register-button-blue-div" style="text-align: left;">
                  <a class="register-button-blue" href="#modal-register" uk-toggle>Crea Tu Cuenta</a>
               </div>
            </div>
         </div>-->
      </div>
   </div>

    {{-- Sección Botones --}}
   <div class="course-pro-buttons background-ligth uk-hidden@s" id="transformatepro-section">
      <div uk-grid>
         <div class="uk-width-1-2" style="padding-right: 10px;">
            <a class="register-button" href="#modal-register" uk-toggle>Crear cuenta</a>
         </div>
         <div class="uk-width-1-2" style="padding-left: 10px;">
            <a class="courses-button" href="{{ route('landing.courses') }}">Ver T-Cursos</a>
         </div>
      </div>
   </div>

   {{-- Contenido Principal --}}
   <div class="content background-ligth2" id="main-content" style="padding-left: 5%; padding-right: 5%;">
      {{-- Sección de Categorías --}}
      <div class="categories">
         <div uk-slider="autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative">
               <div class="uk-slider-container uk-light">
                  <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
                     <li class="category">
                        <a onclick="load_courses(100);">
                           <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
                              <div class="category-icon"><i class="fab fa-tumblr"></i></div>
                              <div class="category-title">T-Master Clases</div>
                           </div>
                        </a>
                     </li>
                     <li class="category">
                        <a onclick="load_courses(0);">
                           <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #006B9B;">
                              <div class="category-icon"><i class="fas fa-book"></i></div>
                              <div class="category-title">T-Libros</div>
                           </div>
                        </a>
                     </li>
                     @foreach ($categorias as $categoria)
                        <li class="category">
                           <a onclick="load_courses({{$categoria->id}});">
                              <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: {{ $categoria->color }};">
                                 <div class="category-icon"><i class="{{ $categoria->icon }}"></i></div>
                                 <div class="category-title">{{ $categoria->title}}</div>
                              </div>
                           </a>
                        </li>
                     @endforeach
                  </ul>
               </div>
            </div>
         </div>
      </div>

      <div class="uk-text-center" id="wait" style="display: none;"> 
         <span uk-spinner="ratio: 4"></span>
      </div>
      
      {{-- Sección de Cursos por Categoría --}}
      <div id="cursos">
         {{-- Cursos Versión Móvil (4 Cards Verticales) --}}
         <div class="courses uk-hidden@s">
            @if ($cantCursos > 0)
               @if ($cantCursos >= 4)
                  @for($i=0; $i < 4; $i++)
                     <div class="uk-card uk-card-small card-background-ligth" id="curso-{{$cursos[$i]->id}}">
                        <div class="uk-card-media-top image-div">
                           @if (!is_null($cursos[$i]->preview))
                              @if ($categoriaSeleccionada == 100)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'clase']) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image"> 
                                 </a>
                              @endif
                           @endif
                           @if ($categoriaSeleccionada != 100)
                              <div class="image-category-div">{{ $cursos[$i]->category->title }}</div>        
                           @endif           
                        </div>
                        <div class="uk-card-body card-body" style="padding-top: 2%;">
                           @if ($categoriaSeleccionada == 100)
                              <a href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @elseif ($categoriaSeleccionada == 0)
                              <a href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @else
                              <a href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                           @endif
                              <div style="min-height: 100px;">
                                 <div class="course-title color-ligth2" id="course-title-{{$cursos[$i]->id}}">{{ $cursos[$i]->title }}</div>
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="course-instructor color-ligth2" id="course-instructor-{{$cursos[$i]->id}}">{{ $cursos[$i]->user->names.' '.$cursos[$i]->user->last_names }}</div>
                                 @endif
                                 <div class="course-subtitle color-ligth2" id="course-subtitle-{{$cursos[$i]->id}}">{{ strtolower($cursos[$i]->subtitle) }}</div>
                              </div>                   
                           </a>    
                           <div class="uk-text-center" style="padding-top: 15px;">   
                              <div class="uk-child-width-1-1" uk-grid>  
                                 @if ($categoriaSeleccionada == 100) 
                                    <div>
                                       <a class="link-course" href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                    </div>
                                 @else
                                    @if ($categoriaSeleccionada != 0)
                                       <div>
                                          <a class="link-course" href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if (Auth::guest())
                                          <div style="margin-top: 10px;">
                                             <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                          </div>
                                       @elseif (Auth::user()->role_id == 1)
                                          @if (!is_null(Auth::user()->membership_id))
         												@if (Auth::user()->membership_courses < 3)
         													<div style="margin-top: 10px;">
         														<a class="link-course" href="{{ route('students.courses.add', [$cursos[$i]->id, 'membresia']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Agregar a Mis Cursos</span></a>
         													</div>
         												@else
         													<div style="margin-top: 10px;">
         														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursos[$i]->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
         													</div>
         												@endif
      											 @else
      												@if ($cursos[$i]->price > 0)
      													<div style="margin-top: 10px;">
      														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoS[$i]->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
      													</div>
      												@endif
											       @endif
                                       @endif
                                    @else
                                       <div>
                                          <a class="link-course" href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if (Auth::guest())
                                          <div style="margin-top: 10px;">
                                             <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                          </div>
                                       @elseif (Auth::user()->role_id == 1)
                                          @if ($cursos[$i]->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursos[$i]->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @endif
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div><br>
                  @endfor
               @else
                  @foreach ($cursos as $curso2)
                     <div class="uk-card uk-card-small card-background-ligth" id="curso-{{$curso2->id}}">
                        <div class="uk-card-media-top image-div">
                           @if (!is_null($curso2->preview))
                              @if ($categoriaSeleccionada == 100)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'clase']) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$curso2->cover) }}" class="content-image">  
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/master-class/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/podcasts/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$curso2->cover) }}" class="content-image"> 
                                 </a>
                              @endif
                           @endif
                           @if ($categoriaSeleccionada != 100)
                              <div class="image-category-div">{{ $curso2->category->title }}</div>    
                           @endif               
                        </div>
                        <div class="uk-card-body card-body" style="padding-top: 2%;">
                           @if ($categoriaSeleccionada == 100)
                              <a href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}">
                           @elseif ($categoriaSeleccionada == 0)
                              <a href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}">
                           @else
                              <a href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}">
                           @endif
                              <div style="min-height: 100px;">
                                 <div class="course-title color-ligth2" id="course-title-{{$curso2->id}}">{{ $curso2->title }}</div>
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="course-instructor color-ligth2" id="course-instructor-{{$curso2->id}}">{{ $curso2->user->names.' '.$curso2->user->last_names }}</div>
                                 @endif
                                 <div class="course-subtitle color-ligth2" id="course-subtitle-{{$curso2->id}}">{{ strtolower($curso2->subtitle) }}</div>
                              </div>                   
                           </a>    
                           <div class="uk-text-center" style="padding-top: 15px;">   
                              <div class="uk-child-width-1-1" uk-grid>  
                                 @if ($categoriaSeleccionada == 100) 
                                    <div>
                                       <a class="link-course" href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                    </div>
                                 @else
                                    @if ($categoriaSeleccionada != 0)
                                       <div>
                                          <a class="link-course" href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if (Auth::guest())
                                          <div style="margin-top: 10px;">
                                             <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                          </div>
                                       @elseif  (Auth::user()->role_id == 1)
                                            @if (!is_null(Auth::user()->membership_id))
												@if (Auth::user()->membership_courses < 3)
													<div style="margin-top: 10px;">
														<a class="link-course" href="{{ route('students.courses.add', [$curso2->id, 'membresia']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Agregar a Mis Cursos</span></a>
													</div>
												@else
													<div style="margin-top: 10px;">
														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso2->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
													</div>
												@endif
											@else
												@if ($cursos[$i]->price > 0)
													<div style="margin-top: 10px;">
														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso2->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
													</div>
												@endif
											@endif
                                       @endif
                                    @else
                                       <div>
                                          <a class="link-course" href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                       </div>
                                       @if (Auth::guest())
                                          <div style="margin-top: 10px;">
                                             <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                          </div>
                                       @elseif (Auth::user()->role_id == 1)
                                          @if ($curso2->price > 0)
                                             <div style="margin-top: 10px;">
                                                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso2->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Book</span></a>
                                             </div>
                                          @endif
                                       @endif
                                    @endif
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div><br>
                  @endforeach
               @endif
            @endif
         </div>

         {{-- Cursos Versión Escritorio (Slider) --}}
         <div class="courses uk-visible@s">
            <div uk-slider class="content-carousel">
               <div class="uk-position-relative">
                  <div class="uk-slider-container uk-light">
                     <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                        @foreach ($cursos as $curso)                            
                           <li class="course uk-transition-toggle" tabindex="0">
                              <div class="uk-card uk-card-small card-background-ligth" id="curso-pc-{{$curso->id}}">
                                 <div class="uk-card-media-top image-div">
                                    @if (!is_null($curso->preview))
                                       @if ($categoriaSeleccionada == 100)
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}">
                                             <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}">
                                             <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @else
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                             <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">  
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @endif
                                    @else
                                       @if ($categoriaSeleccionada == 100)
                                          <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @else
                                          <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                          </a>
                                       @endif
                                    @endif
                                    @if ($categoriaSeleccionada != 100)
                                       <div class="image-category-div">{{ $curso->category->title }}</div>
                                    @endif
                                 </div>
                                 <div class="uk-card-body card-body" style="padding-top: 2%; ">
                                    @if ($categoriaSeleccionada == 100)
                                       <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
                                    @elseif ($categoriaSeleccionada == 0)
                                       <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
                                    @else
                                       <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                    @endif
                                       <div style="min-height: 120px;">
                                          <div class="course-title color-ligth2" id="course-title-pc-{{$curso->id}}">{{ $curso->title }}</div>
                                          @if ($categoriaSeleccionada != 100)
                                             <div class="course-instructor color-ligth2" id="course-instructor-pc-{{$curso->id}}">{{ $curso->user->names.' '.$curso->user->last_names }}</div>
                                          @endif
                                          <div class="course-subtitle color-ligth2" id="course-subtitle-pc-{{$curso->id}}">{{ strtolower($curso->subtitle) }}</div>
                                       </div>                    
                                    </a>    
                                    <div class="uk-text-center" style="padding-top: 15px;">
                                       <div class="uk-child-width-1-1" uk-grid>   
                                          @if ($categoriaSeleccionada == 100)
                                             <div>
                                                <a class="link-course" href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                             </div> 
                                          @else
                                             @if ($categoriaSeleccionada != 0)
                                                <div>
                                                   <a class="link-course" href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                                </div>
                                                @if (Auth::guest())
                                                   <div style="margin-top: 10px;">
                                                      <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
                                                   </div>
                                                @elseif (Auth::user()->role_id == 1)
                                                   @if (!is_null(Auth::user()->membership_id))
        												@if (Auth::user()->membership_courses < 3)
        													<div style="margin-top: 10px;">
        														<a class="link-course" href="{{ route('students.courses.add', [$curso->id, 'membresia']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Agregar a Mis Cursos</span></a>
        													</div>
        												@else
        													<div style="margin-top: 10px;">
        														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
        													</div>
        												@endif
        											@else
        												@if ($curso->price > 0)
        													<div style="margin-top: 10px;">
        														<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
        													</div>
        												@endif
        											@endif
                                                @endif
                                             @else
                                                <div>
                                                   <a class="link-course" href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
                                                </div>
                                                @if (Auth::guest())
                                                   <div style="margin-top: 10px;">
                                                      <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                                   </div>
                                                @elseif (Auth::user()->role_id == 1)
                                                   @if ($curso->price > 0)
                                                      <div style="margin-top: 10px;">
                                                         <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
                                                      </div>
                                                   @endif
                                                @endif
                                             @endif
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </li>
                        @endforeach
                     </ul>                       
                  </div>
                  <div class="controls">
                     <a class="uk-position-center-left-out" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                     <a class="uk-position-center-right-out" href="#" uk-slidenav-next uk-slider-item="next"></a>
                  </div> 
               </div>
            </div>
         </div>
      </div>
   </div>

   {{-- Botón Ver Más Cursos --}}
   <div class="uk-width-1-1 uk-text-center header-background-ligth more-courses-div" id="more-courses-div">
      <a class="more-courses-button" href="{{ route('landing.courses') }}"><i class="fas fa-video"></i> Ver Más T-Cursos</a>
   </div>

   {{-- Sección T-Mentor --}}
   <div class="t-mentor" style="padding: 20px 5% 20px 5%;">
      <img class="uk-hidden@s" src="https://www.transformatepro.com/template/images/t-mentor_movil.jpg" />
      <img class="uk-visible@s" src="https://www.transformatepro.com/template/images/banner-t-mentor.png" />
      <div class="t-mentor-text">
         <h1 class="uk-text-bold title">T-Mentor</h1>
         <span class="description">Haz parte de nuestra<br> comunidad de transformadores.</span>
         <div class="uk-width-1-1 uk-text-center t-mentor-button-div">
            <a class="t-mentor-button" href="#modal-register-instructor" uk-toggle>Inscríbete Aquí</a>
         </div>
      </div>
   </div>
   {{--@if (!is_null($evento))
      <div class="uk-width-1-1 uk-text-center header-background-ligth more-courses-div" id="t-events">
         <a class="more-courses-button" href="{{ route('landing.events') }}"> Ver T-Events</a>
      </div>
      <a class="uk-visible@s" href="#modal-event" uk-toggle><img src="{{ asset('images/events/'.$evento->image) }}" /></a>
      <a class="uk-hidden@s" href="#modal-event" uk-toggle><img src="{{ asset('images/events/'.$evento->image_movil) }}" /></a>
   @endif--}}
   
   {{-- T-Cursos Preferidos --}}
   <div class="uk-text-center best-sellers background-ligth2" id="best-sellers" style="padding-left: 5%; padding-right:5%;">
      <span class="best-sellers-title color-ligth " id="best-sellers-title">Categorías Destacadas</span>

      {{-- Versión Móvil (8 Cards Verticales en dos columnas) --}}
      <div class="best-sellers-cards uk-hidden@s">
         <div class="uk-child-width-1-2" uk-grid>
            @foreach ($cursosMasVendidos as $cursoVendido)
               <div class="best-seller-card">
                  <div class="best-seller-image-div">
                     @if (!is_null($cursoVendido->course->preview))
                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course->id, 'curso']) }}">
                           @if (!is_null($cursoVendido->course->miniature_cover))
                              <img src="{{ asset('uploads/images/courses/thumbnails/'.$cursoVendido->course->miniature_cover) }}" class="best-seller-img">
                           @else
                              <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="best-seller-img">
                           @endif  
                           <div class="uk-overlay uk-position-center">
                              <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course->id, 'curso']) }}"><i class="fas fa-play icon-play-card-small"></i></a>
                           </div>
                        </a>
                     @else
                        <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                           @if (!is_null($cursoVendido->course->miniature_cover))
                              <img src="{{ asset('uploads/images/courses/thumbnails/'.$cursoVendido->course->miniature_cover) }}" class="best-seller-img">
                           @else
                              <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="best-seller-img">
                           @endif  
                        </a>
                     @endif
                  </div>
                  <div class="best-seller-title">
                     <a class="color-ligth2" href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}" id="best-seller-title-{{$cursoVendido->course->id}}">{{ $cursoVendido->course->title }}</a>
                  </div>
                  <div class="best-seller-instructor color-ligth2" id="best-seller-instructor-{{$cursoVendido->course->id}}">{{ $cursoVendido->course->user->names.' '.$cursoVendido->course->user->last_names }}</div>
               </div>
            @endforeach
         </div>
      </div>

      {{-- Versión Escritorio (Slider) --}}
      <div class="best-sellers-cards uk-visible@s">
         <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid>
            @foreach ($cursosMasVendidos as $cursoVendido)
               <div class="best-seller-card">
                  <div class="best-seller-image-div">
                     @if (!is_null($cursoVendido->course->preview))
                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course->id, 'curso']) }}">
                           <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="best-seller-img">  
                           <div class="uk-overlay uk-position-center">
                              <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                           </div>
                        </a>
                     @else
                        <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                           <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="best-seller-img"> 
                        </a>
                     @endif
                  </div>
                  <div class="best-seller-title">
                     <a class="color-ligth2" href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}" id="best-seller-title-pc-{{$cursoVendido->course->id}}">{{ $cursoVendido->course->title }}</a>
                  </div>
                  <div class="best-seller-instructor color-ligth2" id="best-seller-instructor-pc-{{$cursoVendido->course->id}}">{{ $cursoVendido->course->user->names.' '.$cursoVendido->course->user->last_names }}</div>
               </div>
            @endforeach
         </div>
      </div>
      <br>
      <div class="uk-width-1-1 uk-text-center">
         <a class="show-more-button" href="{{ route('landing.courses') }}"> Ver Más</a>
      </div>
   </div>
@endsection