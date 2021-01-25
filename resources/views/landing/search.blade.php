@extends('layouts.landing')

@section('content')
    <div class="background-ligth2" id="search-content" style="padding-bottom: 40px;">
    	{{-- Sección de Categorías --}}
		<div class="categories" style="padding-bottom: 15px;">
			<div uk-slider="autoplay: true; autoplay-interval: 3000;">
				<div class="uk-position-relative">
					<div class="uk-slider-container uk-light">
					    <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
					        <li class="category">
					            <a href="{{ route('landing.courses', ['t-libros', 0]) }}">
					                <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #006B9B;">
					                    <div class="category-icon"><i class="fas fa-book"></i></div>
					                    <div class="category-title">T-Libros</div>
					                </div>
					            </a>
					        </li>
					        @foreach ($categorias as $categoria)
					            <li class="category">
					                <a href="{{ route('landing.courses', [$categoria->slug, $categoria->id]) }}">
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
    	
    	<div id="cursos">
	    	@if ( ($cursosRelacionados->count() == 0) && ($librosRelacionados->count() == 0) )
	    		<div class="uk-clearfix boundary-align"> 
				    <div class="section-heading none-border uk-text-center">
					    <h3>No se encontraron resultados relacionados con su búsqueda...</h3>
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
	    	@else
	    		<div class="uk-hidden@s">
					<div class="courses">
				    	@if ($cursosRelacionados->count() > 0)
			                <div class="category-title-s color-ligth2" id="cursos-relacionados">
			                    <span>Cursos Relacionados<span>
			                </div>

			                @foreach ($cursosRelacionados as $cursoRelacionado)
						        <div class="uk-card uk-card-small card-background-ligth" id="curso-relacionado-{{$cursoRelacionado->id}}">
						            <div class="uk-card-media-top image-div">
						                @if (!is_null($cursoRelacionado->preview))
							                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRelacionado->id, 'curso']) }}">
							                    <img src="{{ asset('uploads/images/courses/'.$cursoRelacionado->cover) }}" class="content-image">  
							                    <div class="uk-overlay uk-position-center">
							                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRelacionado->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
							                    </div>
							                </a>
						                @else
						                    <a href="{{ route('landing.courses.show', [$cursoRelacionado->slug, $cursoRelacionado->id]) }}">
						                        <img src="{{ asset('uploads/images/courses/'.$cursoRelacionado->cover) }}" class="content-image"> 
						                    </a>
						                @endif
						                <div class="image-category-div">{{ $cursoRelacionado->category->title }}</div>  
						            </div>
						            <div class="uk-card-body card-body" style="padding-top: 2%;">
							            <a href="{{ route('landing.courses.show', [$cursoRelacionado->slug, $cursoRelacionado->id]) }}">
								            <div style="min-height: 100px;">
								                <div class="course-title color-ligth2" id="course-title-{{$cursoRelacionado->id}}">{{ $cursoRelacionado->title }}</div>
								                <div class="course-instructor color-ligth2" id="course-instructor-{{$cursoRelacionado->id}}">{{ $cursoRelacionado->user->names.' '.$cursoRelacionado->user->last_names }}</div>
								                <div class="course-subtitle color-ligth2" id="course-subtitle-{{$cursoRelacionado->id}}">{{ strtolower($cursoRelacionado->subtitle) }}</div>
								            </div>                    
						                </a>    
						                <div class="uk-text-center" style="padding-top: 15px;">
							                <div class="uk-child-width-1-1" uk-grid> 
							                    <div>
							                        <a class="link-course" href="{{ route('landing.courses.show', [$cursoRelacionado->slug, $cursoRelacionado->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
							                    </div>
							                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
								                    <div style="margin-top: 10px;">
								                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoRelacionado->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
								                    </div>
								                @endif
							                </div>
						               	</div>
						            </div>
						        </div><br>
						    @endforeach
				    	@endif

				    	@if ($librosRelacionados->count() > 0)
			                <div class="category-title-s color-ligth2" id="libros-relacionados">
			                    <span>Libros Relacionados<span>
			                </div>

			                @foreach ($librosRelacionados as $libroRelacionado)
						        <div class="uk-card uk-card-small card-background-ligth" id="libro-relacionado-{{$libroRelacionado->id}}">
						            <div class="uk-card-media-top image-div">
						                @if (!is_null($libroRelacionado->preview))
							                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroRelacionado->id, 'podcast']) }}">
							                    <img src="{{ asset('uploads/images/podcasts/'.$libroRelacionado->cover) }}" class="content-image">  
							                    <div class="uk-overlay uk-position-center">
							                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroRelacionado->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
							                    </div>
							                </a>
						                @else
						                    <a href="{{ route('landing.podcasts.show', [$libroRelacionado->slug, $libroRelacionado->id]) }}">
						                        <img src="{{ asset('uploads/images/podcasts/'.$libroRelacionado->cover) }}" class="content-image"> 
						                    </a>
						                @endif
						                <div class="image-category-div">{{ $libroRelacionado->category->title }}</div>  
						            </div>
						            <div class="uk-card-body card-body" style="padding-top: 2%;">
							            <a href="{{ route('landing.podcasts.show', [$libroRelacionado->slug, $libroRelacionado->id]) }}">
								            <div style="min-height: 100px;">
								                <div class="course-title color-ligth2" id="book-title-{{$libroRelacionado->id}}">{{ $libroRelacionado->title }}</div>
								                <div class="course-instructor color-ligth2" id="book-instructor-{{$libroRelacionado->id}}">{{ $libroRelacionado->user->names.' '.$libroRelacionado->user->last_names }}</div>
								                <div class="course-subtitle color-ligth2" id="book-subtitle-{{$libroRelacionado->id}}">{{ strtolower($libroRelacionado->subtitle) }}</div>
								            </div>                    
						                </a>    
						                <div class="uk-text-center" style="padding-top: 15px;">
							                <div class="uk-child-width-1-1" uk-grid> 
							                    <div>
							                        <a class="link-course" href="{{ route('landing.podcasts.show', [$libroRelacionado->slug, $libroRelacionado->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
							                    </div>
							                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
								                    <div style="margin-top: 10px;">
								                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroRelacionado->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
								                    </div>
								                @endif
							                </div>
						               	</div>
						            </div>
						        </div><br>
						    @endforeach
				    	@endif
					</div>
				</div>

				{{-- Versión PC --}}
	   			<div class="uk-visible@s" style="padding: 0 20px;">
			   		@if ($cursosRelacionados->count() > 0)
			   			<div class="t-courses-category color-ligth2" id="t-books" style="padding: 30px 0px 15px 10px;">T-Cursos Relacionados</div>

			   			<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
				   			@foreach ($cursosRelacionados as $cursoRelacionadoPC)
				   				<div>
					   				<div class="uk-card uk-card-small card-background-ligth" id="curso-pc-{{$cursoRelacionadoPC->id}}">
							           	<div class="uk-card-media-top image-div">
						                    @if (!is_null($cursoRelacionadoPC->preview))
							                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRelacionadoPC->id, 'curso']) }}">
							                        <img src="{{ asset('uploads/images/courses/'.$cursoRelacionadoPC->cover) }}" class="content-image">  
								                    <div class="uk-overlay uk-position-center">
									                    <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoRelacionadoPC->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
									                </div>
									            </a>
								            @else
									            <a href="{{ route('landing.courses.show', [$cursoRelacionadoPC->slug, $cursoRelacionadoPC->id]) }}">
									                <img src="{{ asset('uploads/images/courses/'.$cursoRelacionadoPC->cover) }}" class="content-image"> 
									            </a>
								            @endif
								            <div class="image-category-div">{{ $cursoRelacionadoPC->category->title }}</div>  
								        </div>
								        <div class="uk-card-body card-body" style="padding-top: 2%;">
									        <a href="{{ route('landing.courses.show', [$cursoRelacionadoPC->slug, $cursoRelacionadoPC->id]) }}">
										        <div style="min-height: 100px;">
										            <div class="course-title color-ligth2" id="course-pc-title-{{$cursoRelacionadoPC->id}}">{{ $cursoRelacionadoPC->title }}</div>
										            <div class="course-instructor color-ligth2" id="course-pc-instructor-{{$cursoRelacionadoPC->id}}">{{ $cursoRelacionadoPC->user->names.' '.$cursoRelacionadoPC->user->last_names }}</div>
										            <div class="course-subtitle color-ligth2" id="course-pc-subtitle-{{$cursoRelacionadoPC->id}}">{{ strtolower($cursoRelacionadoPC->subtitle) }}</div>
										        </div>                    
								            </a>    
							                <div class="uk-text-center" style="padding-top: 15px;">
								                <div class="uk-child-width-1-1" uk-grid>
										            <div>
										                <a class="link-course" href="{{ route('landing.courses.show', [$cursoRelacionadoPC->slug, $cursoRelacionadoPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										            </div>
										            @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											            <div style="margin-top: 10px;">
										                    <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoRelacionadoPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
										                </div>
										            @endif
								                </div>
							                </div>
							            </div>
							        </div>
							    </div>
			   				@endforeach
			   			</div>	
		   			@endif

			   		@if ($librosRelacionados->count() > 0)
			   			<div class="t-courses-category color-ligth2" id="t-books" style="padding: 30px 0px 15px 10px;">T-Libros Relacionados</div>
			   					
			   			<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
				   			@foreach ($librosRelacionados as $libroRelacionadoPC)
				   				<div>
					   				<div class="uk-card uk-card-small card-background-ligth" id="libro-pc-{{$libroRelacionadoPC->id}}">
								        <div class="uk-card-media-top image-div">
								            @if (!is_null($libroRelacionadoPC->preview))
								               	<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroRelacionadoPC->id, 'podcast']) }}">
							                        <img src="{{ asset('uploads/images/podcasts/'.$libroRelacionadoPC->cover) }}" class="content-image">  
						                            <div class="uk-overlay uk-position-center">
						                                <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroRelacionadoPC->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
						                            </div>
							                    </a>
							               	@else
								             <a href="{{ route('landing.podcasts.show', [$libroRelacionadoPC->slug, $libroRelacionadoPC->id]) }}">
							                        <img src="{{ asset('uploads/images/podcasts/'.$libroRelacionadoPC->cover) }}" class="content-image"> 
									                    </a>
						                    @endif
						                   	<div class="image-category-div">{{ $libroRelacionadoPC->category->title }}</div>  
						                </div>
								        <div class="uk-card-body card-body" style="padding-top: 2%;">
								            <a href="{{ route('landing.podcasts.show', [$libroRelacionadoPC->slug, $libroRelacionadoPC->id]) }}">
								                <div style="min-height: 100px;">
								                    <div class="course-title color-ligth2" id="book-pc-title-{{$libroRelacionadoPC->id}}">{{ $libroRelacionadoPC->title }}</div>
								                    <div class="course-instructor color-ligth2" id="book-pc-instructor-{{$libroRelacionadoPC->id}}">{{ $libroRelacionadoPC->user->names.' '.$libroRelacionadoPC->user->last_names }}</div>
								                    <div class="course-subtitle color-ligth2" id="book-pc-subtitle-{{$libroRelacionadoPC->id}}">{{ strtolower($libroRelacionadoPC->subtitle) }}</div>
										        </div>                    
								           	</a>    
							           		<div class="uk-text-center" style="padding-top: 15px;">
						                    	<div class="uk-child-width-1-1" uk-grid>
							                        <div>
							                            <a class="link-course" href="{{ route('landing.podcasts.show', [$libroRelacionadoPC->slug, $libroRelacionadoPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
								                    </div>
										            @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											            <div style="margin-top: 10px;">
									                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroRelacionadoPC->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
									                    </div>
								                    @endif
							                   	</div>
						               		</div>
							           	</div>
							       	</div>
						    	</div>
				   			@endforeach
						</div>	
	   				@endif
		   		</div>
		    @endif
		</div>
    </div>
@endsection