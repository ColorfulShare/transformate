@extends('layouts.landing')

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
      <div class="header-background-ligth" id="t-events">
         {{-- Versión Escritorio --}}

         <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider id="slideropen">
            <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-2@m" style="height:600px">
                <li class="uk-width-1-3">
                    <img src="https://getuikit.com/docs/images/slider1.jpg" alt="">
                    <div class="uk-position-center">
                        <strong><h2>Vibra en la magia de los<br>números</h2></strong>
                        <h5>Vibra en la Magia de los Números</h5>
                        <h5>sincronízate con la guía Divina y</h5>
                        <h5>descubre tu propósito</h5><br>

                        <div uk-grid>
                            <div class="uk-width-1-2" style="color:#fff">
                                <a class="courses-button" uk-toggle>Ver este evento</a>
                            </div>
                            <div class="uk-width-1-2" style="color:#fff">
                                <a class="courses-button">Más eventos</a>
                            </div>
                        </div>

                    </div>
                </li>
                <li class="uk-width-2-3">
                    <img src="https://getuikit.com/docs/images/slider2.jpg" alt="">
                    <div class="uk-position-center"></div>
                </li>
            </ul>
        </div>
         {{-- Versión Móvil --}}
         <div uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative uk-visible-toggle uk-light uk-hidden@s" tabindex="-1">
               <ul class="uk-slider-items uk-grid">
                     <li class="uk-width-1-1">
                        <img src="https://getuikit.com/docs/images/slider1.jpg" alt="">
                        <div class="uk-position-center">
                            <h2>Vibra en la magia de los<br>números</h2>
                            <h5>Vibra en la Magia de los Números</h5>
                            <h5>sincronízate con la guía Divina y</h5>
                            <h5>descubre tu propósito</h5><br>

                            <div uk-grid>
                                <div class="uk-width-1-1" style="color:#fff">
                                    <a class="courses-button" uk-toggle>Ver este evento</a>
                                </div>
                                <div class="uk-width-1-1" style="color:#fff">
                                    <a class="courses-button">Más eventos</a>
                                </div>
                            </div>

                        </div>
                     </li>
               </ul>
            </div>

         </div>
      </div>
   @endif

   {{-- Contenido Principal --}}
   <div class="content background-ligth2" id="main-content" style="padding-left: 5%; padding-right: 5%;">

    <div class="uk-text-center" id="wait" style="display: none;">
         <span uk-spinner="ratio: 4"></span>
      </div>

    <div class="uk-text-center" style="padding: 2%;">
        <span class="best-sellers-title color-black">Nuestros T-Cursos</span>
        <p class="best-sellers-title color-black">Siempre hay una oportunidad de mejorar y reaprender</p>
    </div>

    <div class="uk-margin-medium-top">
        <ul class="uk-flex-center" uk-tab>
            <li class="uk-active"><a href="#"><h3>Destacados</h3></a></li>
            <li><a href="#"><h3>Más vendidos</h3></a></li>
            <li><a href="#"><h3>Recomendados</h3></a></li>
        </ul>
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
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursos[$i]->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$cursos[$i]->cover) }}" class="content-image">
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$cursos[$i]->cover) }}" class="content-image">
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$cursos[$i]->slug, $cursos[$i]->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursos[$i]->cover) }}" class="content-image">
                                 </a>
                              @endif
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
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="color-ligth3"><strong>{{ $cursos[$i]->category->title }}</strong></div>
                                 @endif
                                 <div class="course-subtitle color-ligth3" id="course-subtitle-{{$cursos[$i]->id}}">{{ strtolower($cursos[$i]->subtitle) }}</div>
                                 <br><a><div class="course-instructor color-ligth2">Ver más</div></a>
                              </div>
                           </a>
                           <div style="margin-top: 6%;margin-bottom:6%">
                                <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                            </div>
                        </div>
                        <div class="uk-card-footer" style="padding:0px">
                            <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                <h5 class="uk-text-center">Cop 59,900 USD 13,500</h5>
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
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$curso2->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$curso2->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @else
                                 <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$curso2->cover) }}" class="content-image">
                                    <div class="uk-overlay uk-position-center">
                                       <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso2->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                    </div>
                                 </a>
                              @endif
                           @else
                              @if ($categoriaSeleccionada == 100)
                                 <a href="{{ route('landing.master-class.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$curso2->cover) }}" class="content-image">
                                 </a>
                              @elseif ($categoriaSeleccionada == 0)
                                 <a href="{{ route('landing.podcasts.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$curso2->cover) }}" class="content-image">
                                 </a>
                              @else
                                 <a href="{{ route('landing.courses.show', [$curso2->slug, $curso2->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$curso2->cover) }}" class="content-image">
                                 </a>
                              @endif
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
                                 @if ($categoriaSeleccionada != 100)
                                    <div class="color-ligth3"><strong>{{ $curso2->category->title }}</strong></div>
                                 @endif
                                 <div class="course-subtitle color-ligth3" id="course-subtitle-pc-{{$curso->id}}">{{ strtolower($curso2->subtitle) }}</div>

                                 <br><a><div class="course-instructor color-ligth2">Ver más</div></a>
                              </div>
                           </a>
                           <div style="margin-top: 6%;margin-bottom:6%">
                                <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                            </div>
                        </div>
                        <div class="uk-card-footer" style="padding:0px">
                            <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                <h5 class="uk-text-center">Cop 59,900 USD 13,500</h5>
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
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$curso->cover) }}" class="content-image">
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}">
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$curso->cover) }}" class="content-image">
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @else
                                          <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$curso->cover) }}" class="content-image">
                                             <div class="uk-overlay uk-position-center">
                                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
                                             </div>
                                          </a>
                                       @endif
                                    @else
                                       @if ($categoriaSeleccionada == 100)
                                          <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/master-class/'.$curso->cover) }}" class="content-image">
                                          </a>
                                       @elseif ($categoriaSeleccionada == 0)
                                          <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/podcasts/'.$curso->cover) }}" class="content-image">
                                          </a>
                                       @else
                                          <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                             <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$curso->cover) }}" class="content-image">
                                          </a>
                                       @endif
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
                                          @if ($categoriaSeleccionada != 100)
                                             <div class="color-ligth3" id="course-subtitle-pc-{{$curso->id}}"><strong>{{ $cursos[$i]->category->title }}</strong></div>
                                          @endif
                                          <div class="course-subtitle color-ligth3" id="course-subtitle-pc-{{$curso->id}}">{{ strtolower($curso->subtitle) }}</div>

                                          <br><a><div class="course-instructor color-ligth2">Ver más</div></a>
                                       </div>
                                    </a>
                                    <div style="margin-top: 6%;margin-bottom:6%">
                                        <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                                    </div>
                                 </div>

                                 <div class="uk-card-footer" style="padding:0px">
                                     <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                        <h5 class="uk-text-center">Cop 59,900 USD 13,500</h5>
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

        <br><br>
        <div class="uk-width-1-1 uk-text-center">
            <a class="link-course" href="{{ route('landing.courses') }}"><h5>Ver todos los cursos disponibles<h5></a>
        </div>

    </div>

   {{-- T-Cursos Preferidos --}}
   <div class="uk-text-center best-sellers background-ligth2" id="best-sellers" style="padding-left: 5%; padding-right:5%;">

   <div class="uk-text-center" style="padding: 2%;">
        <span class="best-sellers-title color-black" style="color:#0B132B;">Categorías destacadas</span>
        <p class="best-sellers-title color-black" style="color:#0B132B;">Siempre hay una oportunidad de mejorar y reaprender</p>
    </div>

      {{-- Versión Móvil (8 Cards Verticales en dos columnas) --}}
      <div class="best-sellers-cards uk-hidden@s">
         <div class="uk-child-width-1" uk-grid>
            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon1.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformate kids</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon2.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformación empresa</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon3.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Educación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon4.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Empresa</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon5.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">T master class</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon6.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformate mujer</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon7.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Innovación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon8.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Financiación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon9.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformación personal</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon10.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Creatividad</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>
         </div>
      </div>

      {{-- Versión Escritorio (Slider) --}}
      <div class="best-sellers-cards uk-visible@s">
         <div class="uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l" uk-grid>
            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon1.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformate kids</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon2.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformación empresa</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon3.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Educación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon4.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Empresa</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon5.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">T master class</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon6.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformate mujer</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon7.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Innovación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon8.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Financiación</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon9.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Transformación personal</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img src="{{ asset('/images/icon10.png') }}" style="width:40px;">
                        </div>
                        <div class="uk-width-expand" style="text-align:left;line-height:1px">
                            <h3 class="uk-card-title uk-margin-remove-bottom" style="height:24px;font-weight:bold;font-size:18px;color:#3A506B;">Creatividad</h3>
                            <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">21 cursos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
   </div>


   {{-- Sección final --}}
    <div class="flex-container">
        <div class="flex-item-left">
            <div class="contenflex">

                <div class="uk-text-bold title">Se parte de la transformación y expande tu ser</div>
                <p class="descrip">Con acceso a mucho más que educación<br> online serás parte de una comunidad de<br> transformadores.</p>

                    <a class="link-course"> <span class="btn-course2">Crear cuenta</span></a>

                    <a class="link-course"> <span class="btn-course2">Volverme mentor de cursos</span></a>
            </div>

        </div>
        <div class="flex-item-right">
            <img src="{{ asset('/images/image2.jpg') }}" alt="">
        </div>
    </div>

        {{-- Sección Botones --}}
    <div class="course-pro-buttons background-ligth uk-hidden@s" id="transformatepro-section">
      <div uk-grid>
         <div class="uk-width-1-1" style="padding-right: 10px;color:#fff">
            <a class="courses-button" uk-toggle>Crear cuenta</a>
         </div>
         <div class="uk-width-1-1" style="padding-left: 10px;color:#fff">
            <a class="courses-button">Volverme mentor de cursos</a>
         </div>
      </div>
    </div>

@endsection
