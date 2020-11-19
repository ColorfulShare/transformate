@extends('layouts.coursesLanding')

@section('content')
    <div id="background-books">
        @if (!is_null($portada))
            <div class="uk-visible@m">
                <img class="img_thumb" src="{{ asset('uploads/images/podcasts/'.$portada->image_cover) }}">
                <div class="button-position">
                    @if (!is_null($portada->preview))
                        <a class="uk-button uk-button-white bri view-preview uk-margin-right" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$portada->id, 'podcast']) }}"> Reproducir</a>
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('landing.podcasts.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Book</a>
                    @else 
                        @if (Auth::user()->role_id == 1)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.podcasts.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Book</a>
                        @elseif (Auth::user()->role_id == 2)
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('instructors.podcasts.show', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Book</a>
                        @else
                            <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('admins.podcasts.resume', [$portada->slug, $portada->id]) }}"  title="" aria-expanded="false"> Ver T-Book</a>
                        @endif
                    @endif
                    @if (Auth::guest())
                        <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('landing.shopping-cart.store', [$portada->id, 'podcast']) }}"  aria-expanded="false"> Comprar</a>
                    @else
                        @if (Auth::user()->role_id == 1)
                            @if (is_null($portadaAgregada))
                                <a class="uk-button uk-button btn-buy-course uk-margin-right" href="{{ route('students.shopping-cart.store', [$portada->id, 'podcast']) }}"  aria-expanded="false"> Comprar</a>
                            @else
                                <a class="uk-button uk-button-white bri uk-margin-right" href="{{ route('students.podcasts.resume', [$portada->slug, $portada->id]) }}"  aria-expanded="false"> Continuar T-Book</a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        @endif 

        @for($i=0; $i< 2; $i++)
            @if ($categorys[$i]->podcasts_count >= 4)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>   

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->podcasts as $podcast)
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
                                            <div class="uk-card-body">
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
                                                        <span class="desc">COP$ {{ number_format($podcast->price*1.50, 0, ',', '.') }}</span><br>
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

        @if ($configuracion->most_sellers_slider_podcast == 1)
            @if ($cantPodcastsMasVendidos > 0)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">T-Books Más Vendidos</h2> 
                    </div> 
                </div>       
               
                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($podcastsMasVendidos as $podcastVendido)
                                    @if ($podcastVendido->podcast->status == 2)
                                        <li class="course-li">
                                            <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                                <div class="uk-card-media-top">
                                                    @if (!is_null($podcastVendido->podcast->preview))
                                                        <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$podcastVendido->podcast->id, 'podcast']) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastVendido->podcast->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                                <img src="{{ asset('uploads/images/podcasts/'.$podcastVendido->podcast->cover) }}" class="content-image"> 
                                                            </a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                <a href="{{ route('students.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcastVendido->podcast->cover) }}" class="content-image"> 
                                                                </a>
                                                            @elseif (Auth::user()->role_id == 2)
                                                                <a href="{{ route('instructors.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcastVendido->podcast->cover) }}" class="content-image"> 
                                                                </a>
                                                            @else
                                                                <a href="{{ route('admins.podcasts.resume', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcastVendido->podcast->cover) }}" class="content-image"> 
                                                                </a>
                                                            @endif
                                                        @endif 
                                                    @endif
                                                </div>
                                                <div class="uk-card-body">
                                                    @if (Auth::guest())
                                                        <a href="{{ route('landing.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                    @else
                                                        @if (Auth::user()->role_id == 1)
                                                            <a href="{{ route('students.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                        @elseif (Auth::user()->role_id == 2)
                                                            <a href="{{ route('instructors.podcasts.show', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                        @else
                                                            <a href="{{ route('admins.podcasts.resume', [$podcastVendido->podcast->slug, $podcastVendido->podcast->id]) }}">
                                                        @endif
                                                    @endif
                                                        <h3 class="uk-card-title tile2">{{ $podcastVendido->podcast->title }}</h3>
                                                        <p class="tile2">Una Mentoría de: {{ $podcastVendido->podcast->user->names.' '.$podcastVendido->podcast->user->last_names }}</p>
                                                        <p class="tile3">{{ $ppodcast->certification->subtitle }}</p>
                                                        <p class="tile4">
                                                            <span class="desc">COP$ {{ number_format($podcastVendido->podcast->price*1.50, 0, ',', '.') }}</span><br>
                                                            COP$ {{ number_format($podcastVendido->podcast->price, 0, ',', '.') }}
                                                        </p> 
                                                    </a>            
                                                    <p class="tile5">      
                                                        @if (Auth::guest())
                                                            <a href="{{ route('landing.shopping-cart.store', [$podcastVendido->podcast_id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            @if (Auth::user()->role_id == 1)
                                                                @php 
                                                                    $check = in_array($podcastVendido->podcast_id, $podcastsAgregados);
                                                                @endphp
                                                                @if (!$check)
                                                                    <a href="{{ route('students.shopping-cart.store', [$podcastVendido->podcast_id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                                @else
                                                                    <a href="{{ route('students.podcasts.resume', [$podcastVendido->podcast->slug, $podcastVendido->podcast_id]) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i> CONTINUAR T-BOOK</span></a>
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
        
        @if ($configuracion->most_recent_slider_podcast == 1)
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">T-Books Más Recientes</h2> 
                </div> 
            </div>  

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($podcastsMasRecientes as $podcastReciente)
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($podcastReciente->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$podcastReciente->id, 'podcast']) }}">
                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcastReciente->cover) }}" class="content-image"> 
                                                </a>
                                             @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                        <img src="{{ asset('uploads/images/podcasts/'.$podcastReciente->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.podcasts.resume', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastReciente->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.podcasts.show', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.podcasts.resume', [$podcastReciente->slug, $podcastReciente->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $podcastReciente->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $podcastReciente->user->names.' '.$podcastReciente->user->last_names }}</p>
                                                <p class="tile3">{{ $podcastReciente->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($podcastReciente->price*1.50, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($podcastReciente->price, 0, ',', '.') }}
                                                </p> 
                                            </a>    
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$podcastReciente->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($podcastReciente->id, $podcastsAgregados);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$podcastReciente->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.podcasts.resume', [$podcastReciente->slug, $podcastReciente->id]) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i> CONTINUAR T-BOOK</span></a>
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
            @if ($categorys[$i]->podcasts_count >= 4)
                <div class="uk-clearfix boundary-align brt"> 
                    <div class="uk-float-left section-heading none-border"> 
                        <h2 class="sca">Categoría: {{ $categorys[$i]->title }}</h2> 
                    </div> 
                </div>     

                <div uk-slider class="content-carousel">
                    <div class="uk-position-relative">
                        <div class="uk-slider-container uk-light">
                            <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                                @foreach ($categorys[$i]->podcasts as $podcast)
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
                                            <div class="uk-card-body">
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
                                                    <p class="tile2">Una Mentoria de: {{ $podcast->user->names.' '.$podcast->user->last_names }}</p>
                                                    <p class="tile3">{{ $podcast->subtitle }}</p>
                                                    <p class="tile4">
                                                        <span class="desc">COP$ {{ number_format($podcast->price*1.50, 0, ',', '.') }}</span><br>
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

        @if ($cantPodcastsRestantes > 0)
            <div class="uk-clearfix boundary-align brt"> 
                <div class="uk-float-left section-heading none-border"> 
                    <h2 class="sca">Más T-Books</h2> 
                </div> 
            </div>     

            <div uk-slider class="content-carousel">
                <div class="uk-position-relative">
                    <div class="uk-slider-container uk-light">
                        <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-3@s uk-child-width-1-4@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
                            @foreach ($podcastsRestantes as $podcastRestante)                            
                                <li class="course-li">
                                    <div class="uk-card uk-card-default uk-card-small uk-card-courses">
                                        <div class="uk-card-media-top">
                                            @if (!is_null($podcastRestante->preview))
                                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$podcastRestante->id, 'podcast']) }}">
                                                    <img src="{{ asset('uploads/images/podcasts/'.$podcastRestante->cover) }}" class="content-image"> 
                                                </a>
                                            @else
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                        <img src="{{ asset('uploads/images/podcasts/'.$podcastRestante->cover) }}" class="content-image"> 
                                                    </a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        <a href="{{ route('students.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @elseif (Auth::user()->role_id == 2)
                                                        <a href="{{ route('instructors.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admins.podcasts.resume', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                            <img src="{{ asset('uploads/images/podcasts/'.$podcastRestante->cover) }}" class="content-image"> 
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                        <div class="uk-card-body">
                                            @if (Auth::guest())
                                                <a href="{{ route('landing.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                            @else
                                                @if (Auth::user()->role_id == 1)
                                                    <a href="{{ route('students.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                @elseif (Auth::user()->role_id == 2)
                                                    <a href="{{ route('instructors.podcasts.show', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                @else
                                                    <a href="{{ route('admins.podcasts.resume', [$podcastRestante->slug, $podcastRestante->id]) }}">
                                                @endif
                                            @endif
                                                <h3 class="uk-card-title tile2">{{ $podcastRestante->title }}</h3>
                                                <p class="tile2">Una Mentoria de: {{ $podcastRestante->user->names.' '.$podcastRestante->user->last_names }}</p>
                                                <p class="tile3">{{ $podcastRestante->subtitle }}</p>
                                                <p class="tile4">
                                                    <span class="desc">COP$ {{ number_format($podcastRestante->price*5, 0, ',', '.') }}</span><br>
                                                    COP$ {{ number_format($podcastRestante->price, 0, ',', '.') }}
                                                </p>   
                                            </a>          
                                            <p class="tile5">      
                                                @if (Auth::guest())
                                                    <a href="{{ route('landing.shopping-cart.store', [$podcastRestante->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                @else
                                                    @if (Auth::user()->role_id == 1)
                                                        @php 
                                                            $check = in_array($podcastRestante->id, $podcastsAgregados);
                                                        @endphp
                                                        @if (!$check)
                                                            <a href="{{ route('students.shopping-cart.store', [$podcastRestante->id, 'podcast']) }}" type="button"> <span class="btn-book"><i class="fas fa-cart-plus"></i>  COMPRAR AHORA</span></a>
                                                        @else
                                                            <a href="{{ route('students.podcasts.resume', [$podcastRestante->slug, $podcastRestante->id]) }}" type="button"> <span class="btn-sc"><i class="fas fa-cart-plus"></i> CONTINUAR T-BOOK</span></a>
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