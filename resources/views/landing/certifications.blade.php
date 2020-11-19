@extends('layouts.coursesLanding')

@section('content')
    <div id="background-mentorings">
        @if (!is_null($portada))
            <div class="uk-visible@m">
                <img class="img_thumb" src="{{ asset('uploads/images/certifications/'.$portada->image_cover) }}">
                <div class="button-position">
                    @if (!is_null($portada->preview))
                        <a class="uk-button uk-button-white bri view-preview uk-margin-right" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$portada->id, 'certificacion']) }}"> Reproducir</a>
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('landing.certifications.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Mentoring</a>
                    @else 
                        @if (Auth::user()->role_id == 1)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.certifications.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Mentoring</a>
                        @elseif (Auth::user()->role_id == 2)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('instructors.certifications.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Mentoring</a>
                        @else
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('admins.certifications.resume', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Mentoring</a>
                        @endif
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('landing.shopping-cart.store', [$portada->id, 'certificacion']) }}"  aria-expanded="false"> Comprar</a>
                    @else
                        @if (Auth::user()->role_id == 1)
                            @if (is_null($portadaAgregada))
                                <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('students.shopping-cart.store', [$portada->id, 'certificacion']) }}"  aria-expanded="false"> Comprar</a>
                            @else
                                <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.certifications.resume', [$portada->slug, $portada->id]) }}"  aria-expanded="false"> Continuar T-Mentoring</a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        @endif 

        @for($i=0; $i< 2; $i++)
            @if ($categorys[$i]->certifications_count >= 4)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>   

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->certifications as $certificacion)
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                @if (!is_null($certificacion->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacion->id, 'certificacion']) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $certificacion->title }}</h3>
                                                    <p class="tile2">Una Mentoría de: {{ $certificacion->user->names.' '.$certificacion->user->last_names }}</p>
                                                    <p class="tile3">{{ $certificacion->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($certificacion->price*1.50, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($certificacion->price, 0, ',', '.') }}
                                                    </p>     
                                                </a>         
                                                <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($certificacion->id, $certificacionesAgregadas);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
        @endfor

        @if ($configuracion->most_sellers_slider_certification == 1)
            @if ($cantCertificacionesMasVendidas > 0)
                <!-- CERTIFICACIONES MAS VENDIDAS -->
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">T-Mentorings Más Vendidas</h2> 
                    </div> 
                </div>       
               
                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($certificacionesMasVendidas as $certificacionVendida)
                                    @if ($certificacionVendida->certification->status == 2)
                                        <li class="course-li">
                                            <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                                <div class="uk-card-media-top">
                                                    @if (!is_null($certificacionVendida->certification->preview))
                                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacionVendida->certification->id, 'certificacion']) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('students.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image"> 
                                                                </a>
                                                            @elseif (Auth::user()->role_id == 2)
                                                                <a href="{{ route('instructors.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image"> 
                                                                </a>
                                                            @else
                                                                <a href="{{ route('admins.certifications.resume', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image"> 
                                                                </a>
                                                            @endif
                                                        @endif 
                                                    @endif
                                                </div>
                                                <div class="uk-card-body">
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.certifications.show', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                        @else
                                                            <a href="{{ route('admins.certifications.resume', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}">
                                                        @endif
                                                    @endif
                                                        <h3 class="uk-card-title tile2">{{ $certificacionVendida->certification->title }}</h3>
                                                        <p class="tile2">Una Mentoría de: {{ $certificacionVendida->certification->user->names.' '.$certificacionVendida->certification->user->last_names }}</p>
                                                        <p class="tile3">{{ $certificacionVendida->certification->subtitle }}</p>
                                                        <p class="tile4">
                                                            <span class="desc">COP$ {{ number_format($certificacionVendida->certification->price*1.50, 0, ',', '.') }}</span><br>
                                                            COP$ {{ number_format($certificacionVendida->certification->price, 0, ',', '.') }}
                                                        </p> 
                                                    </a>            
                                                    <p class="tile5">      
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.shopping-cart.store', [$certificacionVendida->certification_id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                @php 
                                                                    $check = in_array($certificacionVendida->certification_id, $certificacionesAgregadas);
                                                                @endphp
                                                                @if (!$check)
                                                                    <a href="{{ route('students.shopping-cart.store', [$certificacionVendida->certification_id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                                @else
                                                                    <a href="{{ route('students.certifications.resume', [$certificacionVendida->certification->slug, $certificacionVendida->certification->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
                        <div class="uk-hidden@s uk-light">
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
            @endif
        @endif
        
        @if ($configuracion->most_recent_slider_certification == 1)
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">T-Mentorings Más Recientes</h2> 
                </div> 
            </div>  

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($certificacionesMasRecientes as $certificacionReciente)
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($certificacionReciente->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacionReciente->id, 'certificacion']) }}">
                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image"> 
                                                </a>
                                             @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.certifications.show', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.certifications.resume', [$certificacionReciente->slug, $certificacionReciente->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $certificacionReciente->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $certificacionReciente->user->names.' '.$certificacionReciente->user->last_names }}</p>
                                                <p class="tile3">{{ $certificacionReciente->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($certificacionReciente->price*1.50, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($certificacionReciente->price, 0, ',', '.') }}
                                                </p> 
                                            </a>    
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$certificacionReciente->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($certificacionReciente->id, $certificacionesAgregadas);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$certificacionReciente->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.certifications.resume', [$certificacionReciente->slug, $certificacionReciente->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
        
        @for($i=1; $i< count($categorys); $i++)     
            @if ($categorys[$i]->certifications_count >= 4)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->certifications as $certificacion)
                                    <li class="course-li">
                                        <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                            <div class="uk-card-media-top">
                                                @if (!is_null($certificacion->preview))
                                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacion->id, 'certificacion']) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                                <img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image"> 
                                                            </a>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="uk-card-body">
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacion->slug, $certificacion->id]) }}">
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}">
                                                    @endif
                                                @endif
                                                    <h3 class="uk-card-title tile2">{{ $certificacion->title }}</h3>
                                                    <p class="tile2">Una Mentoria de: {{ $certificacion->user->names.' '.$certificacion->user->last_names }}</p>
                                                    <p class="tile3">{{ $certificacion->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($certificacion->price*1.50, 0, ',', '.') }}</span><br>
                                                        COP$ {{ number_format($certificacion->price, 0, ',', '.') }}
                                                    </p>      
                                                </a>         
                                               <p class="tile5">      
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            @php 
                                                                $check = in_array($certificacion->id, $certificacionesAgregadas);
                                                            @endphp
                                                            @if (!$check)
                                                                <a href="{{ route('students.shopping-cart.store', [$certificacion->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                            @else
                                                                <a href="{{ route('students.certifications.resume', [$certificacion->slug, $certificacion->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
        @endfor

        @if ($cantCertificacionesRestantes > 0)
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Más T-Mentorings</h2> 
                </div> 
            </div>     

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($certificacionesRestantes as $certificacionRestante)                            
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($certificacionRestante->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$certificacionRestante->id, 'certificacion']) }}">
                                                    <img src="{{ asset('uploads/images/certifications/'.$certificacionRestante->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionRestante->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.certifications.resume', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/certifications/'.$certificacionRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.certifications.show', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.certifications.resume', [$certificacionRestante->slug, $certificacionRestante->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $certificacionRestante->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $certificacionRestante->user->names.' '.$certificacionRestante->user->last_names }}</p>
                                                <p class="tile3">{{ $certificacionRestante->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($certificacionRestante->price*5, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($certificacionRestante->price, 0, ',', '.') }}
                                                </p>   
                                            </a>          
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$certificacionRestante->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($certificacionRestante->id, $certificacionesAgregadas);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$certificacionRestante->id, 'certificacion']) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.certifications.resume', [$certificacionRestante->slug, $certificacionRestante->id]) }}" type="button"> <span class="btn-resume"><i class="fas fa-cart-plus"></i> CONTINUAR T-MENTORING</span></a>
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
    </div>
@endsection