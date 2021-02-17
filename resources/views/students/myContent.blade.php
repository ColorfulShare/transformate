@extends('layouts.student2')

@section('content')
    <div id="background-courses" style="padding-left: 10%; padding-right: 10%;">
        @if (Session::has('msj-erroneo'))
            <div class="uk-alert-danger uk-text-center" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        @endif

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success uk-text-center" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        @if ( ($cantCursos == 0) && ($cantCertificaciones == 0) && ($cantPodcasts == 0) )
            <div class="uk-text-center category-title-s">
                <span>Usted no posee ningún contenido comprado...<span><br><br>
                <a href="{{ route('landing.courses') }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> EXPLORAR T-CURSOS</span></a>
            </div>  
        @else
            @if ($cantCursos > 0)
                <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Mis T-Cursos</h2> 
                    </div> 
                </div>  

                <div class="uk-text-center uk-hidden@s category-title-s">
                    <span>Mis T-Cursos<span>
                </div>     

                <ul class="uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($cursos as $curso)
                        <li class="course uk-transition-toggle" tabindex="0">
                            <div class="uk-card uk-card-small course-card">
                                <div class="uk-card-media-top image-div">
                                    @if (!is_null($curso->preview))
                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
                                            <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">
                                        </a>
                                    @else
                                        <a href="#">
                                            <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">
                                        </a>
                                    @endif
                                </div>
                                <div class="uk-card-body card-body">
                                    <a href="#">
                                        <div>
                                            <div class="course-title">{{ $curso->title }}</div>
                                            <div class="course-instructor">Por: {{ $curso->user->names.' '.$curso->user->last_names }}</div>
                                            <div class="course-category"><strong>{{ $curso->category->title }}</strong></div>
                                            <div class="course-subtitle">{{ ucfirst($curso->subtitle) }}</div>
                                        </div>
                                    </a>
                                    <div class="card-buttons uk-text-center" style="height: 70px;">
                                        <a class="link-course" href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}"> <span class="btn-course2">Ir al T-Curso</span></a>
                                        @if ($curso->progress == 100)
                                            <br><br>
                                            <a class="link-course" href="{{ asset('certificates/courses/'.Auth::user()->id.'-'.$curso->id.'.pdf') }}" target="_blank"> <span class="btn-course3">Ver Certificado</span></a>
                                        @endif
                                    </div>
                                </div>
                                <div class="uk-card-footer" style="padding:0px">
                                    <div class="uk-box-shadow-hover-small uk-padding uk-card-primary card-footer">
                                        <div class="uk-text-center course-price">Progreso: <b>{{ $curso->progress }}%</b></div>
                                    </div>         
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
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

                <ul class="uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($podcasts as $podcast)
                        <li class="course uk-transition-toggle" tabindex="0">
                            <div class="uk-card uk-card-small course-card">
                                <div class="uk-card-media-top image-div">
                                    @if (!is_null($podcast->preview))
                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$podcast->id, 'podcast']) }}">
                                            <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image">
                                        </a>
                                    @else
                                        <a href="#">
                                            <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image">
                                        </a>
                                    @endif
                                </div>
                                <div class="uk-card-body card-body">
                                    <a href="#">
                                        <div>
                                            <div class="course-title">{{ $podcast->title }}</div>
                                            <div class="course-instructor">Por: {{ $podcast->user->names.' '.$podcast->user->last_names }}</div>
                                            <div class="course-category"><strong>{{ $podcast->category->title }}</strong></div>
                                            <div class="course-subtitle">{{ ucfirst($podcast->subtitle) }}</div>
                                        </div>
                                    </a>
                                    <div class="card-buttons uk-text-center">
                                        <a class="link-course" href="{{ route('students.podcasts.resume', [$podcast->slug, $podcast->id]) }}"> <span class="btn-course2">Ir al T-Book</span></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
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

                <ul class="uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
                    @foreach ($certificaciones as $certificacion)
                        <li class="course uk-transition-toggle" tabindex="0">
                            <div class="uk-card uk-card-small course-card">
                                <div class="uk-card-media-top image-div">
                                    @if (!is_null($certificacion->preview))
                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacion->id, 'certificacion']) }}">
                                            <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image">
                                        </a>
                                    @else
                                        <a href="#">
                                            <img src="{{ asset('uploads/images/courses/'.$certificacion->cover) }}" class="content-image">
                                        </a>
                                    @endif
                                </div>
                                <div class="uk-card-body card-body">
                                    <a href="#">
                                        <div>
                                            <div class="course-title">{{ $certificacion->title }}</div>
                                            <div class="course-instructor">Por: {{ $certificacion->user->names.' '.$certificacion->user->last_names }}</div>
                                            <div class="course-category"><strong>{{ $certificacion->category->title }}</strong></div>
                                            <div class="course-subtitle">{{ ucfirst($certificacion->subtitle) }}</div>
                                        </div>
                                    </a>
                                    <div class="card-buttons uk-text-center" style="height: 70px;">
                                        <a class="link-course" href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}"> <span class="btn-course2">Ir a la T-Mentoring</span></a>
                                        @if ($certificacion->progress == 100)
                                            <br><br>
                                            <a class="link-course" href="{{ asset('certificates/certifications/'.Auth::user()->id.'-'.$certificacion->id.'.pdf') }}" target="_blank"> <span class="btn-course3">Ver Certificado</span></a>
                                        @endif
                                    </div>
                                </div>
                                <div class="uk-card-footer" style="padding:0px">
                                    <div class="uk-box-shadow-hover-small uk-padding uk-card-primary card-footer">
                                        <div class="uk-text-center course-price">Progreso: <b>{{ $certificacion->progress }}%</b></div>
                                    </div>         
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>
@endsection