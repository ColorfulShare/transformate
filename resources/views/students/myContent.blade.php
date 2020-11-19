@extends('layouts.coursesLanding')

@section('content')
    <div id="background-courses">
        @if (Session::has('msj-erroneo'))
            <div class="uk-alert-danger uk-text-center" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        @endif

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-danger uk-text-center" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif
        @if ( ($cantCursos == 0) && ($cantCertificaciones == 0) && ($cantPodcasts == 0) )
            <div class="uk-text-center category-title-s">
                <span>Usted no posee ningún contenido comprado...<span><br><br>
                <a href="{{ route('landing.courses') }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> EXPLORAR T-COURSES</span></a>
            </div>  
        @else
            @if ($cantCursos > 0)
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Mis T-Courses</h2> 
                    </div> 
                </div>  

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Mis T-Courses<span>
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($cursos as $curso)                            
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}">
                                                    <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
                                                </a>
                                            </div>
                                            <div class="uk-card-body card-body">
                                                <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}">
                                                    <h3 class="uk-card-title tile2">{{ $curso->title }}</h3>
                                                    <p class="tile2">Una Mentoria de: {{ $curso->user->names.' '.$curso->user->last_names }}</p>
                                                    <p class="tile3 uk-text-center" style="padding-bottom: 20px;">
                                                        <progress id="js-progressbar" class="uk-progress progress-green uk-margin-small-bottom uk-margin-small-top" value="{{ $curso->progress }}" max="100" style="height: 15px;"></progress>
                                                        <b>{{ $curso->progress }}%</b>
                                                    </p>
                                                </a>          
                                                <p class="tile5">
                                                    @if ($curso->progress != 100)  
                                                        <a href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-COURSE</span></a>
                                                    @else
                                                        <a href="{{ asset('certificates/courses/'.Auth::user()->id.'-'.$curso->id.'.pdf') }}" type="button" target="_blank"> <span class="btn-book"><i class="fas fa-medal"></i> VER CERTIFICADO</span></a>
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
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Mis T-Books</h2> 
                    </div> 
                </div>  

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Mis T-Books<span>
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($podcasts as $podcast)                            
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                <a href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}">
                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image"> 
                                                </a>
                                            </div>
                                            <div class="uk-card-body card-body">
                                                <a href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}">
                                                    <h3 class="uk-card-title tile2">{{ $podcast->title }}</h3>
                                                    <p class="tile2">Una Mentoría de: {{ $podcast->user->names.' '.$podcast->user->last_names }}</p>
                                                    <p class="tile3 uk-text-center" style="padding-bottom: 20px;">
                                                        
                                                    </p>
                                                </a>          
                                                <p class="tile5">  
                                                    <a href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-BOOK</span></a>
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

            @if ($cantCertificaciones > 0)
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Mis T-Mentorings</h2> 
                    </div> 
                </div>  

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Mis T-Mentorings<span>
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($certificaciones as $certificacion)                            
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                <a href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                </a>
                                            </div>
                                            <div class="uk-card-body card-body">
                                                <a href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                    <h3 class="uk-card-title tile2">{{ $certificacion->title }}</h3>
                                                    <p class="tile2">Una Mentoría de: {{ $certificacion->user->names.' '.$certificacion->user->last_names }}</p>
                                                    <p class="tile3 uk-text-center" style="padding-bottom: 20px;">
                                                        <progress id="js-progressbar" class="uk-progress progress-green uk-margin-small-bottom uk-margin-small-top" value="{{ $certificacion->progress }}" max="100" style="height: 15px;"></progress>
                                                        <b>{{ $certificacion->progress }}%</b>
                                                    </p>
                                                </a>          
                                                <p class="tile5">  
                                                    <a href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
        @endif
    </div>
@endsection