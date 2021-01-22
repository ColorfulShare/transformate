@extends('layouts.landingNew')

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
        <div uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
            <div class="uk-position-relative uk-visible-toggle uk-light " tabindex="-1">
                <ul class="uk-slider-items uk-grid">
                    @foreach ($eventos as $evento)
                        <li class="uk-width-1-1">
                            <div class="flex-container">
                                <div class="flex-item-left" style="background-color: #3197BD;  display: flex; align-items: center;">
                                    <div class="contenflex" style="padding-left: 10%; padding-right: 10%;">
                                        <div class="uk-text-bold title" style="color: white; line-height: 28px;">{{ $evento->title }}</div>
                                        <p class="descrip">
                                            <h5>{{ $evento->legend }}</h5><br>
                                        </p>

                                        <div uk-grid>
                                            <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s" style="color:#fff">
                                                <a class="transf-button courses-button-white" href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}">Ver este evento</a>
                                            </div>
                                            <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s" style="color:#fff">
                                                <a class="transf-button courses-button-white" href="{{ route('landing.events') }}">Más eventos</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="flex-item-right">
                                    <a href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}"><img src="{{ asset('uploads/events/images/'.$evento->image) }}" alt="{{ $evento->title }}"></a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous" style="color: white !important;"></a>
                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next" style="color: white !important;"></a>
            </div>
        </div>
    @endif

{{-- Contenido Principal --}}
<div class="content background-ligth" id="main-content" style="padding-left: 5%; padding-right: 5%;">
    <div class="uk-text-center" id="wait" style="display: none;">
        <span uk-spinner="ratio: 4"></span>
    </div>

    <div class="uk-text-center" style="padding: 2%;">
        <span class="big-title color-black">Nuestros T-Cursos</span>
        <p class="small-title color-black">Encuentra todo para tu Crecimiento Personal, Social y Profesional, con Propósito y Consciencia.</p>
    </div>

    <div>
        <ul class="uk-child-width-1-3@xl uk-child-width-1-3@l uk-child-width-1-3@m uk-child-width-1-1@s" uk-tab>
            <li class="uk-active"><a href="#">
                    <h3>Destacados</h3>
                </a></li>
            <li><a href="#">
                    <h3>Más vendidos</h3>
                </a></li>
            <li><a href="#">
                    <h3>Recomendados</h3>
                </a></li>
        </ul>
        <ul class="uk-switcher uk-margin">
            <!-- CURSOS DESTACADOS -->
            <li>
                <ul class="uk-child-width-1-1 uk-child-width-1-1@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($cursosDestacados as $cursoDestacado)
                    <li class="course uk-transition-toggle" tabindex="0">
                        <div class="uk-card uk-card-small card-background-ligth" id="curso-destacado-{{$cursoDestacado->id}}">
                            <div class="uk-card-media-top image-div">
                                @if (!is_null($cursoDestacado->preview))
                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoDestacado->id, 'curso']) }}">
                                        <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoDestacado->cover) }}" class="content-image">
                                    </a>
                                @else
                                <a href="{{ route('landing.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoDestacado->cover) }}" class="content-image">
                                </a>
                                @endif
                            </div>
                            <div class="uk-card-body card-body" style="padding-top: 2%; ">
                                <a href="{{ route('landing.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">
                                    <div style="min-height: 215px;">
                                        <div class="course-title">{{ $cursoDestacado->title }}</div>
                                        <div class="course-instructor">Por: {{ $cursoDestacado->user->names.' '.$cursoDestacado->user->last_names }}</div>
                                        <div class="course-category"><strong>{{ $cursoDestacado->category->title }}</strong></div>
                                        <div class="course-subtitle">{{ strtolower($cursoDestacado->subtitle) }}</div>

                                        <br>
                                        <a class="show-more-link" href="{{ route('landing.courses.show', [$cursoDestacado->slug, $cursoDestacado->id]) }}">Ver más</a>
                                    </div>
                                </a>
                                <div style="margin-top: 6%;margin-bottom:6%">
                                    <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                                </div>
                            </div>
                            <div class="uk-card-footer" style="padding:0px">
                                <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                    <div class="uk-text-center course-price">COP {{ number_format($cursoDestacado->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
            <!-- CURSOS MÁS VENDIDOS -->
            <li>
                <ul class="uk-child-width-1-1 uk-child-width-1-1@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($cursosVendidos as $cursoVendido)
                    <li class="course uk-transition-toggle" tabindex="0">
                        <div class="uk-card uk-card-small card-background-ligth" id="curso-vendido-{{$cursoVendido->course_id}}">
                            <div class="uk-card-media-top image-div">
                                @if (!is_null($cursoVendido->course->preview))
                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoVendido->course_id, 'curso']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoVendido->course->cover) }}" class="content-image">
                                </a>
                                @else
                                <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course_id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoVendido->course_cover) }}" class="content-image">
                                </a>
                                @endif
                            </div>
                            <div class="uk-card-body card-body" style="padding-top: 2%; ">
                                <a href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course_id]) }}">
                                    <div style="min-height: 215px;">
                                        <div class="course-title">{{ $cursoVendido->course->title }}</div>
                                        <div class="course-instructor">Por: {{ $cursoVendido->course->user->names.' '.$cursoVendido->course->user->last_names }}</div>
                                        <div class="course-category"><strong>{{ $cursoVendido->course->category->title }}</strong></div>
                                        <div class="course-subtitle">{{ strtolower($cursoVendido->course->subtitle) }}</div>

                                        <br>
                                        <a class="show-more-link" href="{{ route('landing.courses.show', [$cursoVendido->course->slug, $cursoVendido->course_id]) }}">Ver más</a>
                                    </div>
                                </a>
                                <div style="margin-top: 6%;margin-bottom:6%">
                                    <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                                </div>
                            </div>
                            <div class="uk-card-footer" style="padding:0px">
                                <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                    <div class="uk-text-center course-price">COP {{ number_format($cursoVendido->course->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
            <!-- CURSOS RECOMENDADOS -->
            <li>
                <ul class="uk-child-width-1-1 uk-child-width-1-1@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($cursosRecomendados as $cursoRecomendado)
                    <li class="course uk-transition-toggle" tabindex="0">
                        <div class="uk-card uk-card-small card-background-ligth" id="curso-recomendado-{{$cursoRecomendado->id}}">
                            <div class="uk-card-media-top image-div">
                                @if (!is_null($cursoRecomendado->preview))
                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRecomendado->id, 'curso']) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoRecomendado->cover) }}" class="content-image">
                                </a>
                                @else
                                <a href="{{ route('landing.courses.show', [$cursoRecomendado->slug, $cursoRecomendado->id]) }}">
                                    <img src="{{ asset('https://transformatepro.com/uploads/images/courses/'.$cursoRecomendado->cover) }}" class="content-image">
                                </a>
                                @endif
                            </div>
                            <div class="uk-card-body card-body" style="padding-top: 2%; ">
                                <a href="{{ route('landing.courses.show', [$cursoRecomendado->slug, $cursoRecomendado->id]) }}">
                                    <div style="min-height: 215px;">
                                        <div class="course-title">{{ $cursoRecomendado->title }}</div>
                                        <div class="course-instructor">Por: {{ $cursoRecomendado->user->names.' '.$cursoRecomendado->user->last_names }}</div>
                                        <div class="course-category"><strong>{{ $cursoRecomendado->category->title }}</strong></div>
                                        <div class="course-subtitle">{{ strtolower($cursoRecomendado->subtitle) }}</div>

                                        <br>
                                        <a class="show-more-link" href="{{ route('landing.courses.show', [$cursoRecomendado->slug, $cursoRecomendado->id]) }}">Ver más</a>
                                    </div>
                                </a>
                                <div style="margin-top: 6%;margin-bottom:6%">
                                    <a class="link-course"> <span class="btn-course2">Agregar al carrito</span></a>
                                </div>
                            </div>
                            <div class="uk-card-footer" style="padding:0px">
                                <div class="uk-box-shadow-hover-small uk-padding uk-card-primary" style="padding:10px;background:#1172A9;">
                                    <div class="uk-text-center course-price">COP {{ number_format($cursoRecomendado->price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>

    <div class="uk-width-1-1 uk-text-center" style="padding-top: 20px;">
        <a class="show-more-link" href="{{ route('landing.courses') }}">
            <h5>Ver todos los cursos disponibles<h5>
        </a>
    </div>
</div>

<!-- Categorías -->
<div class="uk-text-center best-sellers background-ligth2" id="best-sellers" style="padding-left: 5%; padding-right: 5%;">

    <div class="uk-text-center" style="padding: 2%;">
        <span class="big-title color-black" style="color:#0B132B;">Nuestras Categorías</span>
        <p class="small-title color-black" style="color:#0B132B;">Encuentra la Ruta que transforma más apropiada para ti y Transformate para Transformar.</p>
    </div>

    {{-- Versión Móvil (8 Cards Verticales en dos columnas) --}}
    <div class="best-sellers-cards">
        <div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-1@s" uk-grid>
            <div>
                <a href="{{ route('landing.courses', ['t-master-class', 100]) }}">
                    <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img src="{{ asset('/images/icon11.png') }}" style="width:40px;">
                            </div>
                            <div class="uk-width-expand" style="text-align:left;line-height:20px">
                                <h3 class="uk-card-title uk-margin-remove-bottom" style="height:50px;font-weight:bold;font-size:18px;color:#3A506B;">T-Master Class</h3>
                                <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">{{ $cantMasterClass }} cursos</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div>
                <a href="{{ route('landing.courses', ['t-books', 0]) }}">
                    <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-auto">
                                <img src="{{ asset('/images/icon12.png') }}" style="width:40px;">
                            </div>
                            <div class="uk-width-expand" style="text-align:left;line-height:20px">
                                <h3 class="uk-card-title uk-margin-remove-bottom" style="height:50px;font-weight:bold;font-size:18px;color:#3A506B;">T-Books</h3>
                                <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">{{ $cantPodcasts }} cursos</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @foreach ($categoriasHome as $categoriaH)
                <div>
                    <a href="{{ route('landing.courses', [$categoriaH->slug, $categoriaH->id]) }}">
                        <div class="uk-box-shadow-hover-small uk-padding uk-card-default" style="border-radius:5px;">
                            <div class="uk-grid-small uk-flex-middle" uk-grid>
                                <div class="uk-width-auto">
                                    <img src="{{ asset('/images/'.$categoriaH->image) }}" style="width:40px;">
                                </div>
                                <div class="uk-width-expand" style="text-align:left;line-height:20px">
                                    <h3 class="uk-card-title uk-margin-remove-bottom" style="height:50px;font-weight:bold;font-size:18px;color:#3A506B;">{{ $categoriaH->title }}</h3>
                                    <p class="uk-text-meta uk-margin-remove-top" style="color:#5FA8D3">{{ $categoriaH->courses_count }} cursos</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

    <div class="flex-container">
        <div class="flex-item-left" style="display: flex; align-items: center;">
            <div class="contenflex">
                <div class="uk-text-bold title">Se parte de la transformación y expande tu ser</div>
                <p class="descrip">Con acceso a la posibilidad de crear tus cursos y productos online, expande tu ser y haz parte de nuestra Comunidad de Transformadores.</p>

                <a class="link-course" href="#modal-register" uk-toggle> <span class="btn-course2">Crear cuenta</span></a>
                <br class="uk-hidden@s">

                <a class="link-course" href="#modal-register-instructor" uk-toggle> <span class="btn-course2">Volverme mentor de cursos</span></a>
            </div>

        </div>
        <div class="flex-item-right">
            <img src="{{ asset('/images/image2.jpg') }}" alt="">
        </div>
    </div>
@endsection