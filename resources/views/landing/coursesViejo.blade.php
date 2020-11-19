@extends('layouts.coursesLanding')

@push('styles')
    <style>
        .video-content{
            position: absolute; 
            margin-top:-280px;
            margin-left: 100px; 
            height: 100vh;
        }
        .video-image{
            width: 55%; 
            margin-top:-420px;
        }
        .video-text{
            color:white; 
            margin-top:-350px;
        }
        .tau{
            font-size: 65px;
        }
        .tau2{
            font-size: 60px;
        }
        .tau3{
            font-size:30px;
        }
        @media (min-width: 640px) and (max-width: 749px) {
            .video-image{
                margin-top:-80px;
            }
            .video-text{
                margin-top:-10px;
            }
            .tau{
                font-size: 45px;
            }
            .tau2{
                font-size: 40px;
            }
            .tau3{
                font-size: 20px;
            }
        }
        @media (min-width: 750px) and (max-width: 999px) {
            .video-image{
                margin-top:-120px;
            }
            .video-text{
                margin-top:-50px;
            }
            .tau{
                font-size: 45px;
            }
            .tau2{
                font-size: 40px;
            }
            .tau3{
                font-size: 20px;
            }
        }
        @media (min-width: 1000px) and (max-width: 1300px) {
            .video-image{
                margin-top:-220px;
            }
            .video-text{
                margin-top:-150px;
            }
           
        }
    </style>
@endpush
@push('scripts')
    <script>
        function prueba($curso){
            var route = "https://transformatepro.com/ajax/cargar-curso-original/"+$curso;
            //var route = "http://localhost:8000/ajax/cargar-curso-original/"+$curso;

            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#original-content").html(ans);                       
                }
            });
        }
    </script>
@endpush
@section('content')
    <div id="background-courses">
        @if (!is_null($portada))
            <section class="uk-visible@s" style="height: 80vh;">
                <video id="videobcg" preload="auto" autoplay="" loop="" muted="" playsinline="" class="relative hidden sm:block min-w-full min-h-full h-full w-full top-0 left-0" style="object-fit:cover">
                    <source src="{{ asset('/template/images/videos/video_pagina1.mp4')}}" type="video/mp4">
                    <source src="{{ asset('/template/images/videos/video_pagina1.mp4')}}" type="video/mp4">Sorry, your browser does not support HTML5 video.
                </video>
                
                
                <div class="video-content">
                    <div>
                        <img class="video-image" src="template/images/logoLinea.svg">
                    </div>
                    <div class="video-text"> 
                        <h1 class="tau"><strong>Curso PRO del mes</strong></h1>
                        <p>
                            <span class="tau2">Transfórmate Tú 2.0</span><br>
                            <span class="tau3">Encuentra el camino a tu real esencia y crea tu mejor versión</span>
                        </p>
                    </div>
                    @if (!is_null($portada->preview))
                        <a class="uk-button uk-button-white bri view-preview uk-margin-right" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$portada->id, 'curso']) }}"> Reproducir</a>
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('landing.courses.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Course</a>
                    @else 
                        @if (Auth::user()->role_id == 1)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.courses.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Course</a>
                        @elseif (Auth::user()->role_id == 2)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('instructors.courses.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Course</a>
                        @else
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('admins.courses.resume', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Course</a>
                        @endif
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('landing.shopping-cart.store', [$portada->id, 'curso']) }}"  aria-expanded="false"> Comprar</a>
                    @else
                        @if (Auth::user()->role_id == 1)
                            @if (is_null($portadaAgregada))
                                <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('students.shopping-cart.store', [$portada->id, 'curso']) }}"  aria-expanded="false"> Comprar</a>
                            @else
                                <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.courses.resume', [$portada->slug, $portada->id]) }}"  aria-expanded="false"> Continuar T-Course</a>
                            @endif
                        @endif
                    @endif
                </div>
            </section>
        @endif 

        @for($i=0; $i< 2; $i++)
            @if ($categorys[$i]->courses_count >= 4)
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>   

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Categoría: {{ $categorys[$i]->title }}<span>
                </div>

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->courses as $curso)                            
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses" >
                                            <div class="uk-card-media-top">
                                                @if (!is_null($curso->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                                        <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.courses.show', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.courses.show', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.courses.resume', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.courses.show', [$curso->slug, $curso->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.courses.show', [$curso->slug, $curso->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.courses.resume', [$curso->slug, $curso->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $curso->title }}</h3>
                                                    <p class="tile2">Una Mentoria de: {{ $curso->user->names.' '.$curso->user->last_names }}</p>
                                                    <p class="tile3">{{ $curso->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($curso->price*5, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($curso->price, 0, ',', '.') }}
                                                    </p>                    
                                                </a>    
                                                <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($curso->id, $cursosAgregados);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$curso->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>                       
                        </div>
                        <div class="uk-hidden@s hiddens-controls">
                            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                        <div class="uk-visible@s">
                            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div> 
                    </div>
                </div>
            @endif
        @endfor
 
        {{--  @if ($cantCursosOriginales > 0)
            <!-- CURSOS ORIGINALES -->
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Categoría: T-Courses Creados por Transfórmate</h2> 
                </div> 
            </div> 

            <div class="content-carousel">
                <div class="uk-child-width-1-2@s uk-text-center" uk-grid id="original-content">
                    <div class="">
                        <div class="uk-background-default">
                            @if (Auth::guest())
                                <a href="{{ route('landing.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                                    <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
                                </a>
                            @else
                                @if (Auth::user()->role_id == 1)
                                    <a href="{{ route('students.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                                        <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
                                    </a>
                                @elseif (Auth::user()->role_id == 2)
                                    <a href="{{ route('instructors.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                                        <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}"> 
                                    </a>
                                @else
                                    <a href="{{ route('admins.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">
                                        <img src="{{ asset('uploads/images/courses/'.$primerCursoOriginal->cover) }}">
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="">
                            <p class="uk-h4 uk-text-white uk-text-left">
                                @if (Auth::guest())
                                    <a href="{{ route('landing.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                                @else
                                    @if (Auth::user()->role_id == 1)
                                        <a href="{{ route('students.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                                    @elseif (Auth::user()->role_id == 2)
                                        <a href="{{ route('instructors.courses.show', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                                    @else
                                        <a href="{{ route('admins.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}">{{ $primerCursoOriginal->title }}</a>
                                    @endif
                                @endif
                            </p>
                            <div class="uk-text-white uk-text-justify uk-margin-medium-bottom">
                                {!!  strip_tags($primerCursoOriginal->review) !!}
                            </div>
                      
                            <div class="uk-child-width-1-2@s uk-text-center" uk-grid >
                                <div>
                                    <p class="discount uk-text-left uk-text-white uk-text-bold">COP$ {{number_format($primerCursoOriginal->price*5, 0, ',', '.') }}</p>
                                    <p class="uk-text-bold price-course-original uk-text-left">COP$ {{ number_format($primerCursoOriginal->price, 0, ',', '.') }}</p>
                                </div>
                                @if (!is_null($primerCursoOriginal->preview))
                                    <a class="uk-button add-sc view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$primerCursoOriginal->id, 'curso']) }}"><i class="fas fa-play-circle"></i> Ver Trailer</a>
                                @endif
                            </div>
                            <br>
                            @if (Auth::guest())
                                <a href="{{ route('landing.shopping-cart.store', [$primerCursoOriginal->id, 'curso']) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Añadir al carrito</button></a>
                            @else
                                @if (Auth::user()->role_id == 1)
                                    @php 
                                        $check = in_array($primerCursoOriginal->id, $cursosAgregados);
                                    @endphp
                                    @if (!$check)
                                        <a href="{{ route('students.shopping-cart.store', [$primerCursoOriginal->id, 'curso']) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Añadir al carrito</button></a>
                                    @else
                                        <a href="{{ route('students.courses.resume', [$primerCursoOriginal->slug, $primerCursoOriginal->id]) }}"><button class="uk-button add-sc btn-lg btn-block"><i class="fa fa-shopping-cart"></i>  Continuar Curso</button></a>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>
            
            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                            @foreach ($cursosOriginales as $cursoOriginal)
                                <li class="card-course">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses davis">
                                        <div class="uk-card-media-top davi"> 
                                            <img src="{{ asset('uploads/images/courses/'.$cursoOriginal->cover) }}" class="content-image" style="cursor: pointer;" onclick="prueba({{ $cursoOriginal->id }});">
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>                        
                    </div>
                    <div class="uk-hidden@s uk-light">
                        <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div>
                    <div class="uk-visible@s">
                        <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div> 
                </div>                       
            </div>  
        @endif    --}}

        @if ($configuracion->most_sellers_slider_course == 1)
            @if ($cantCursosMasVendidos > 0)
                <!-- CURSOS MAS VENDIDOS -->
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">T-Courses Más Vendidos</h2> 
                    </div> 
                </div> 

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>T-Courses Más Vendidos<span>
                </div>      
               
                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($cursosMasVendidos as $cursoVendido)
                                    @if ($cursoVendido->course->status == 2)                        
                                        <li class="course-li">
                                            <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                                <div class="uk-card-media-top">
                                                    @if (!is_null($cursoVendido->course->preview))
                                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course->id, 'curso']) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('students.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                                    <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image"> 
                                                                </a>
                                                            @elseif (Auth::user()->role_id == 2)
                                                                <a href="{{ route('instructors.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                                    <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image"> 
                                                                </a>
                                                            @else
                                                                <a href="{{ route('admins.courses.resume', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                                    <img src="{{ asset('uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image"> 
                                                                </a>
                                                            @endif
                                                        @endif                 
                                                    @endif
                                                </div>
                                                <div class="uk-card-body card-body">
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.courses.show', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                        @else
                                                            <a href="{{ route('admins.courses.resume', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}">
                                                        @endif
                                                    @endif
                                                        <h3 class="uk-card-title tile2">{{ $cursoVendido->course->title }}</h3>
                                                        <p class="tile2">Una Mentoria de: {{ $cursoVendido->course->user->names.' '.$cursoVendido->course->user->last_names }}</p>
                                                        <p class="tile3">{{ $cursoVendido->course->subtitle }}</p>
                                                        <p class="tile4">
                                                            <span class="desc">COP$ {{ number_format($cursoVendido->course->price*5, 0, ',', '.') }}</span><br>
                                                            COP$ {{ number_format($cursoVendido->course->price, 0, ',', '.') }}
                                                        </p>  
                                                    </a>        
                                                    <p class="tile5">      
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.shopping-cart.store', [$cursoVendido->course_id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                @php 
                                                                    $check = in_array($cursoVendido->course_id, $cursosAgregados);
                                                                @endphp
                                                                @if (!$check)
                                                                    <a href="{{ route('students.shopping-cart.store', [$cursoVendido->course_id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                                @else
                                                                    <a href="{{ route('students.courses.resume', [$cursoVendido->course->slug, $cursoVendido->course->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>                           
                        </div>
                        <div class="uk-hidden@s hiddens-controls">
                            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                        <div class="uk-visible@s">
                            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div> 
                        <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                    </div>    
                </div>                                              
                <!-- FIN DE CURSOS MAS VENDIDOS -->
            @endif
        @endif
        
        @if ($configuracion->most_recent_slider_course == 1)
            <!-- CURSOS MAS RECIENTES -->
            <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">T-Courses Más Recientes</h2> 
                </div> 
            </div>

            <div class="uk-text-center uk-hidden@s category-title-s">
                <span>T-Courses Más Recientes<span>
            </div>  

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($cursosMasRecientes as $cursoReciente)
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($cursoReciente->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoReciente->id, 'curso']) }}">
                                                    <img src="{{ asset('uploads/images/courses/'.$cursoReciente->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                        <img src="{{ asset('uploads/images/courses/'.$cursoReciente->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.courses.resume', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.courses.show', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.courses.resume', [$cursoReciente->slug, $cursoReciente->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $cursoReciente->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $cursoReciente->user->names.' '.$cursoReciente->user->last_names }}</p>
                                                <p class="tile3">{{ $cursoReciente->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($cursoReciente->price*5, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($cursoReciente->price, 0, ',', '.') }}
                                                </p> 
                                            </a>           
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$cursoReciente->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($cursoReciente->id, $cursosAgregados);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$cursoReciente->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.courses.resume', [$cursoReciente->slug, $cursoReciente->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>                                           
                    </div>
                    <div class="uk-hidden@s hiddens-controls">
                        <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div>
                    <div class="uk-visible@s">
                        <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div>            
                </div> 
            </div>
        @endif
        
        {{--  @if ($configuracion->most_taken_slider_course == 1)
            @if ($cantCursosMasCursados > 0)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">T-Courses Preferidos</h2> 
                    </div> 
                </div>  

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-5@l uk-child-width-1-5@xl uk-grid">
                                @foreach ($cursosMasCursados as $cursoCursado)
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                @if (!is_null($cursoCursado->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoCursado->id, 'curso']) }}">
                                                        <img src="{{ asset('uploads/images/courses/'.$cursoCursado->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoCursado->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoCursado->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoCursado->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.courses.resume', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoCursado->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.courses.show', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.courses.resume', [$cursoCursado->slug, $cursoCursado->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $cursoCursado->title }}</h3>
                                                    <p class="tile2">Una Mentoria de: {{ $cursoCursado->user->names.' '.$cursoCursado->user->last_names }}</p>
                                                    <p class="tile3">{{ $cursoCursado->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($cursoCursado->price*5, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($cursoCursado->price, 0, ',', '.') }}
                                                    </p>    
                                                </a>    
                                                <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$cursoCursado->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($cursoCursado->id, $cursosAgregados);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$cursoCursado->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.courses.resume', [$cursoCursado->slug, $cursoCursado->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="uk-hidden@s hiddens-controls">
                            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                        <div class="uk-visible@s">
                            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div> 
                    </div> 
                </div> 
            @endif
        @endif--}}
        
        @for($i=1; $i< count($categorys); $i++)     
            {{--  @if ($cantCursosDestacados > 0)
                @if ($categorys[$i]->id == 7)
                    <div class="uk-clearfix boundary-align brt"> 
                        <div class="uk-float-left section-heading none-border"> 
                            <h2 class="sca">T-Courses Destacados</h2> 
                        </div> 
                    </div>  
                    
                    <div class="content-carousel">
                        <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slideshow style="height: 350px;">
                            <ul class="uk-slideshow-items">
                                @foreach ($cursosDestacados as $cursoDestacado)
                                    <li>
                                        <div class="uk-child-width-1-2@s" uk-grid style="color: black;background-color: white;">
                                            <div class="">
                                                <div class="uk-background-default">
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoDestacado->cover) }}" style="width: 100%; height: 100%;">
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoDestacado->cover) }}" style="width: 100%; height: 100%;">
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoDestacado->cover) }}" style="width: 100%; height: 100%;">
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.courses.resume', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$cursoDestacado->cover) }}" style="width: 100%; height: 100%;">
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <div class="uk-margin-small-top">
                                                    <h3 class="uk-card-title" style="color: black;">{{ $cursoDestacado->title }}</h3>
                                                    <p class="tile2">Una mentoría de: {{ $cursoDestacado->user->names }} {{ $cursoDestacado->user->last_names }}</p>

                                                    <div class="uk-margin-small-top">
                                                        <ul class="list-inline hero--course__stats">
                                                            <li class="list-inline-item">
                                                                <i class="fa fa-star fa-fw"></i> {{ number_format($cursoDestacado->promedio, 2, '.', ',') }} ({{ $cursoDestacado->ratings_count }} Valoraciones)
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <i class="fa fa-users fa-fw"></i> {{ $cursoDestacado->students_count }} Estudiantes
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <i class="fa fa-film fa-fw"></i> 30 Lecciones (3 Horas)
                                                            </li>
                                                            <li class="list-inline-item">
                                                                <i class="fa fa-volume-up fa-fw"></i> Audio: Español
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="uk-text-justify uk-margin-medium-bottom" style="padding-top: 20px;">
                                                        {!!  strip_tags($cursoDestacado->review) !!}
                                                    </div>                                            
                                                    <p class="tile5 uk-text-left">
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}" type="button"> 
                                                                <span class="btn-sc"><i class="fas fa-search"></i> Descubre el T-Course</span>
                                                            </a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('students.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}" type="button"> 
                                                                    <span class="btn-sc"><i class="fas fa-search"></i> Descubre el T-Course</span>
                                                                </a>
                                                            @elseif (Auth::user()->role_id == 2)
                                                                <a href="{{ route('instructors.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}" type="button"> 
                                                                    <span class="btn-sc"><i class="fas fa-search"></i> Descubre el T-Course</span>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('admins.courses.resume', [$cursoDestacado->slug, $cursoDestacado->id]) }}" type="button"> 
                                                                    <span class="btn-sc"><i class="fas fa-search"></i> Descubre el T-Course</span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </p>
                                                    <p class="tile4 uk-text-right">
                                                        <span class="desc">{{ number_format($cursoDestacado->price*5, 0, ',', '.') }} COP</span><br>
                                                        COP$ {{ number_format($cursoDestacado->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slideshow-item="previous" style="color: black;"></a>
                            <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slideshow-item="next" style="color: black;"></a>
                        </div>
                    </div>
                @endif
            @endif--}}

            @if ($categorys[$i]->courses_count >= 4)
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>  

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Categoría: {{ $categorys[$i]->title }}<span>
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->courses as $curso)                            
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                @if (!is_null($curso->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                                        <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.courses.show', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.courses.show', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.courses.resume', [$curso->slug, $curso->id]) }}">
                                                                <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.courses.show', [$curso->slug, $curso->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.courses.show', [$curso->slug, $curso->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.courses.resume', [$curso->slug, $curso->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $curso->title }}</h3>
                                                    <p class="tile2">Una Mentoria de: {{ $curso->user->names.' '.$curso->user->last_names }}</p>
                                                    <p class="tile3">{{ $curso->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($curso->price*5, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($curso->price, 0, ',', '.') }}
                                                    </p>   
                                                </a>          
                                                <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($curso->id, $cursosAgregados);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$curso->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>          
                        </div>
                        <div class="uk-hidden@s hiddens-controls">
                            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                        <div class="uk-visible@s">
                            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div> 
                    </div>
                </div>
            @endif
        @endfor

        @if ($cantCursosRestantes > 0)
            <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Más T-Courses</h2> 
                </div> 
            </div>     
            
            <div class="uk-text-center uk-hidden@s category-title-s">
                <span>Más T-Courses<span>
            </div>

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($cursosRestantes as $cursoRestante)                            
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($cursoRestante->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRestante->id, 'curso']) }}">
                                                    <img src="{{ asset('uploads/images/courses/'.$cursoRestante->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                        <img src="{{ asset('uploads/images/courses/'.$cursoRestante->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.courses.resume', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/courses/'.$cursoRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.courses.show', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.courses.resume', [$cursoRestante->slug, $cursoRestante->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $cursoRestante->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $cursoRestante->user->names.' '.$cursoRestante->user->last_names }}</p>
                                                <p class="tile3">{{ $cursoRestante->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($cursoRestante->price*5, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($cursoRestante->price, 0, ',', '.') }}
                                                </p>   
                                            </a>          
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$cursoRestante->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($cursoRestante->id, $cursosAgregados);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$cursoRestante->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.courses.resume', [$cursoRestante->slug, $cursoRestante->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                        @endif
                                                    @endif
                                                @endif
                                             </p>
                                         </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>          
                    </div>
                    <div class="uk-hidden@s hiddens-controls">
                        <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div>
                    <div class="uk-visible@s">
                        <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div> 
                </div>
            </div>
        @endif
        
        @if ($cantPodcasts > 0)      
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border uk-visible@s"> 
                    <h2 class="sca">T-AudioCourses</h2> 
                    </div> 
                </div>   

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>T-AudioCourses<span>
                </div>

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($podcasts as $podcast)
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                @if (!is_null($podcast->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$podcast->id, 'podcast']) }}">
                                                        <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                                <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                                <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.podcasts.resume', [$podcast->slug, $podcast->id]) }}">
                                                                <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.podcasts.resume', [$podcast->slug, $podcast->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $podcast->title }}</h3>
                                                    <p class="tile2">Una Mentoría de: {{ $podcast->user->names.' '.$podcast->user->last_names }}</p>
                                                    <p class="tile3">{{ $podcast->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($podcast->price*2, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($podcast->price, 0, ',', '.') }}
                                                    </p>     
                                                </a>         
                                                <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$podcast->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($podcast->id, $podcastsAgregados);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$podcast->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i> CONTINUAR T-BOOK</span></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>                       
                        </div>
                        <div class="uk-hidden@s hiddens-controls">
                            <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div>
                        <div class="uk-visible@s">
                            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
                        </div> 
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection