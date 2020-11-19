@extends('layouts.student2')

@section('content')
    <div class="description-section">
        <div class="uk-container">
           <div uk-grid>
                <div class="uk-width-1-4@m">
                    <div class="uk-grid-small uk-padding-small" uk-grid> 
                        <div class="uk-width-4-4@m uk-first-column uk-text-center">
                            <img class="uk-width-3-3 uk-margin-small-top uk-margin-small-bottom uk-border-circle uk-box-shadow-large  uk-animation-scale-up" src="{{ asset('uploads/images/users/'.$instructor->avatar) }}"> <br>
                            <div class="since">
                                <h3 class="course-teacher__name uk-text-center">
                                    <ul class="list-inline">
                                        @if (!is_null($instructor->facebook))
                                            <li class="list-inline-item">
                                                <a title="Facebook de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->facebook }}" target="_blank"><i class="fab fa-facebook"></i></a>
                                            </li>
                                        @endif
                                        @if (!is_null($instructor->twitter))
                                            <li class="list-inline-item">
                                                <a title="Twitter de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->twitter }}" target="_blank"><i class="fab fa-twitter"></i></a>
                                            </li>
                                        @endif
                                        @if (!is_null($instructor->instagram))
                                            <li class="list-inline-item">
                                                <a title="Instagram de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a>
                                            </li>
                                        @endif
                                        @if (!is_null($instructor->youtube))
                                            <li class="list-inline-item">
                                                <a title="Canal de YouTube de {{ $instructor->names }} {{ $instructor->last_names }} Instagram" rel="external nofollow" href="{{ $instructor->youtube }}" target="_blank"><i class="fab fa-youtube"></i></a>
                                            </li>
                                        @endif
                                        @if (!is_null($instructor->pinterest))
                                            <li class="list-inline-item">
                                                <a title="Pinterest de {{ $instructor->names }} {{ $instructor->last_names }}" rel="external nofollow" href="{{ $instructor->pinterest }}" target="_blank"><i class="fab fa-pinterest"></i></a>
                                            </li>
                                        @endif
                                    </ul>
                                </h3>
                            </div>                        
                        </div>                       
                    </div>   
                </div>              


                <div class="uk-width-3-4@m"> 
                    <strong>{{ $instructor->names }} {{ $instructor->last_names }}</strong> <br><br>
                    {!! $instructor->review !!}              
                    <br>
                    <div class="uk-grid-divider uk-grid-small items-section" uk-grid>
                        <div>
                            <i class="fa fa-users"></i> Total de Estudiantes<br>
                            <h4>{{ $cantEstudiantes }}</h4>
                        </div>
                        <div>
                            <i class="fa fa-video"></i> Cursos<br>
                            <h4>{{ $instructor->courses_count }}</h4>
                        </div>
                    </div>         
                </div>                     
            </div>
        </div>
    </div>

    <div class="courses-section">
        @if ($instructor->courses_count > 0)
            <div class="uk-clearfix boundary-align brt uk-visible@s"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca" style="color: black;">T-Courses</h2> 
                </div> 
            </div>   

            <div class="uk-text-center uk-hidden@s category-title-s">
                <span>T-Courses<span>
            </div>

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($instructor->courses as $curso)                            
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
                                                            <a href="{{ route('students.shopping-cart.store', [$curso->id, 'curso']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
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

       {{--   @if ($instructor->certifications_count > 0)
            <div class="uk-clearfix boundary-align"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="uk-text-black">T-Mentoring</h2> 
                </div> 
            </div>     

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                            @foreach ($instructor->certifications as $certificacion)
                                <li class="card-course">
                                    <div class="uk-card uk-card-default uk-card-small">
                                        <div class="uk-card-media-top">
                                            <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image">
                                        </div>
                                        <div class="uk-card-body">
                                            <a href="{{ route('landing.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                <h3 class="uk-card-title">{{ $certificacion->title }}</h3>
                                            </a>
                                            <p class="tile2">Mentor: {{ $certificacion->user->names.' '.$certificacion->user->last_names }}</p>
                                            <p class="tile4">{{ strip_tags($certificacion->review) }}</p>                                            
                                            <p class="tile5"><span class="desc">{{ $certificacion->price*1.50 }} COP</span><br>
                                                <a href="{{ route('landing.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus icon-large"></i>  {{ $certificacion->price }} COP</span></a>
                                            </p>
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
                        <a class="uk-position-center-left-out uk-position-small uk-text-black" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right-out uk-position-small uk-text-black" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div> 
                    <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                </div>
            </div>
        @endif

        @if ($instructor->podcasts_count > 0)
            <div class="uk-clearfix boundary-align"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="uk-text-black">T-Books</h2> 
                </div> 
            </div>     

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                            @foreach ($instructor->podcasts as $podcast)                            
                                <li class="card-course">
                                    <div class="uk-card uk-card-default uk-card-small">
                                        <div class="uk-card-media-top">
                                            <img src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="content-image">
                                        </div>
                                        <div class="uk-card-body">
                                            <a href="{{ route('landing.podcasts.show', [$podcast->slug, $podcast->id]) }}">
                                                <h3 class="uk-card-title">{{ $podcast->title }}</h3>
                                            </a>
                                            <p class="tile2">Mentor: {{ $podcast->user->names.' '.$podcast->user->last_names }}</p>
                                            <p class="tile4">{{ strip_tags($podcast->review) }}</p>                                            
                                            <p class="tile5"><span class="desc">{{ $podcast->price*1.50 }} COP</span><br>
                                                <a href="{{ route('landing.shopping-cart.store', [$podcast->id, 'podcast']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus icon-large"></i>  {{ $podcast->price }} COP</span></a>
                                            </p>
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
                        <a class="uk-position-center-left-out uk-position-small uk-text-black" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                        <a class="uk-position-center-right-out uk-position-small uk-text-black" href="#" uk-slidenav-next uk-slider-item="next"></a>
                    </div> 
                    <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                </div>
            </div>
        @endif--}}
    </div>
@endsection