@extends('layouts.coursesLanding')

@section('content')
    <div id="background-mentorings">
        @if ($categoria->certifications_count > 0)
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Categoría: {{ $categoria->title }}</h2> 
                </div> 
            </div>    

             <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($categoria->certifications as $certificacionCat)                            
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-certifications">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($certificacionCat->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacionCat->id, 'certificacion']) }}">
                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionCat->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionCat->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionCat->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionCat->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionCat->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.certifications.show', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.certifications.resume', [$certificacionCat->slug, $certificacionCat->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $certificacionCat->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $certificacionCat->user->names.' '.$certificacionCat->user->last_names }}</p>
                                                <p class="tile3">{{ $certificacionCat->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($certificacionCat->price*1.50, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($certificacionCat->price, 0, ',', '.') }}
                                                </p>
                                            </a>
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$certificacionCat->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($certificacionCat->id, $certificacionesAgregadas);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$certificacionCat->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.podcasts.resume', [$certificacionCat->slug, $certificacionCat->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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

            @foreach ($categoria->subcategories as $subcategoria)
                @if ($subcategoria->certifications_count > 0)
                    <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Subcategoría: {{ $subcategoria->title }}</h2> 
                </div> 
            </div>   

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($subcategoria->certifications as $certificacionSub)                            
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-certifications">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($certificacionSub->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacionSub->id, 'certificacion']) }}">
                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionSub->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionSub->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionSub->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionSub->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionSub->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.certifications.show', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.certifications.resume', [$certificacionSub->slug, $certificacionSub->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $certificacionSub->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $certificacionSub->user->names.' '.$certificacionSub->user->last_names }}</p>
                                                <p class="tile3">{{ $certificacionSub->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($certificacionSub->price*1.50, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($certificacionSub->price, 0, ',', '.') }}
                                                </p>  
                                            </a>
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$certificacionSub->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($certificacionSub->id, $certificacionesAgregadas);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$certificacionSub->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.podcasts.resume', [$certificacionSub->slug, $certificacionSub->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
                @endif
            @endforeach
        @else
            <div class="uk-clearfix boundary-align"> 
                <div class="section-heading none-border uk-text-center">
                    <h3>No existen T-Mentorings relacionadas con ésta categoría aún...</h3>
                    <hr>
                    <h5>Realiza otra búsqueda...</h5>
                    <form action="{{ route('landing.search') }}" method="GET">
                        <div class="uk-margin">
                            <div class="uk-inline uk-width-1-3">
                                <span class="uk-form-icon"><i class="fas fa-search  uk-margin-small-left"></i></span><input class="uk-input uk-form-large" type="text" name="busqueda">
                            </div>
                        </div>
                        <input type="submit" class="uk-button uk-button-success uk-width-1-3 uk-margin-small-bottom" value="Buscar" />
                    </form>
                    <hr>
                </div>
            </div>
        @endif
    </div>
@endsection