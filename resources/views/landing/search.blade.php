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
	    	@if ( ($cantCursos == 0) && ($cantLibros) && ( $cantCategorias == 0) && ($cantSubcategorias) && ($cantMentores == 0) )
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
				    	@if ($cantCursos > 0)
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

				    	@if ($cantLibros > 0)
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

				    	@if ($cantCategorias > 0)
				    		@foreach ($categoriasRelacionadas as $categoria)
				    			@if ( ($categoria->courses_count > 0) || ($categoria->podcasts_count > 0) )
				    				<div class="category-title-s color-ligth2" id="categoria-relacionada-{{$categoria->id}}">
						                <span>Categoría: {{ $categoria->title }}<span>
						            </div>

					    			@if ($categoria->courses_count > 0) 
						                @foreach ($categoria->courses as $cursoCategoria)
									        <div class="uk-card uk-card-small card-background-ligth" id="curso-categoria-{{$cursoCategoria->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($cursoCategoria->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoCategoria->id, 'curso']) }}">
										                    <img src="{{ asset('uploads/images/courses/'.$cursoCategoria->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoCategoria->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.courses.show', [$cursoCategoria->slug, $cursoCategoria->id]) }}">
									                        <img src="{{ asset('uploads/images/courses/'.$cursoCategoria->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $cursoCategoria->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.courses.show', [$cursoCategoria->slug, $cursoCategoria->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="course2-title-{{$cursoCategoria->id}}">{{ $cursoCategoria->title }}</div>
											                <div class="course-instructor color-ligth2" id="course2-instructor-{{$cursoCategoria->id}}">{{ $cursoCategoria->user->names.' '.$cursoCategoria->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="course2-subtitle-{{$cursoCategoria->id}}">{{ strtolower($cursoCategoria->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.courses.show', [$cursoCategoria->slug, $cursoCategoria->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoCategoria->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif

							        @if ($categoria->podcasts_count > 0) 
						                @foreach ($categoria->podcasts as $libroCategoria)
									        <div class="uk-card uk-card-small card-background-ligth" id="libro-categoria-{{$libroCategoria->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($libroCategoria->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroCategoria->id, 'podcast']) }}">
										                    <img src="{{ asset('uploads/images/podcasts/'.$libroCategoria->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroCategoria->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.podcasts.show', [$libroCategoria->slug, $libroCategoria->id]) }}">
									                        <img src="{{ asset('uploads/images/podcasts/'.$libroCategoria->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $libroCategoria->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.podcasts.show', [$libroCategoria->slug, $libroCategoria->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="book2-title-{{$libroCategoria->id}}">{{ $libroCategoria->title }}</div>
											                <div class="course-instructor color-ligth2" id="book2-instructor-{{$libroCategoria->id}}">{{ $libroCategoria->user->names.' '.$libroCategoria->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="book2-subtitle-{{$libroCategoria->id}}">{{ strtolower($libroCategoria->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.podcasts.show', [$libroCategoria->slug, $libroCategoria->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroCategoria->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif
						        @endif
				    		@endforeach
					    @endif

					    @if ($cantSubcategorias > 0)
				    		@foreach ($subcategoriasRelacionadas as $subcategoria)
				    			@if ( ($subcategoria->courses_count > 0) || ($subcategoria->podcasts_count > 0) )
				    				<div class="category-title-s color-ligth2" id="subcategoria-relacionada-{{$subcategoria->id}}">
						                <span>Subcategoría: {{ $subcategoria->title }}<span>
						            </div>
					    			@if ($subcategoria->courses_count > 0)
						                @foreach ($subcategoria->courses as $cursoSubcategoria)
									        <div class="uk-card uk-card-small card-background-ligth" id="curso-subcategoria-{{$cursoSubcategoria->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($cursoSubcategoria->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoSubcategoria->id, 'curso']) }}">
										                    <img src="{{ asset('uploads/images/courses/'.$cursoSubcategoria->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoSubcategoria->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.courses.show', [$cursoSubcategoria->slug, $cursoSubcategoria->id]) }}">
									                        <img src="{{ asset('uploads/images/courses/'.$cursoSubcategoria->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $cursoSubcategoria->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.courses.show', [$cursoSubcategoria->slug, $cursoSubcategoria->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="course3-title-{{$cursoSubcategoria->id}}">{{ $cursoSubcategoria->title }}</div>
											                <div class="course-instructor color-ligth2" id="course3-instructor-{{$cursoSubcategoria->id}}">{{ $cursoSubcategoria->user->names.' '.$cursoSubcategoria->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="course3-subtitle-{{$cursoSubcategoria->id}}">{{ strtolower($cursoSubcategoria->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.courses.show', [$cursoSubcategoria->slug, $cursoSubcategoria->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoSubcategoria->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif
							        @if ($subcategoria->podcasts_count > 0)
						                @foreach ($subcategoria->podcasts as $libroSubcategoria)
						                	<div class="uk-card uk-card-small card-background-ligth" id="libro-subcategoria-{{$libroSubcategoria->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($libroSubcategoria->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroSubcategoria->id, 'podcast']) }}">
										                    <img src="{{ asset('uploads/images/podcasts/'.$libroSubcategoria->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroSubcategoria->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.podcasts.show', [$libroSubcategoria->slug, $libroSubcategoria->id]) }}">
									                        <img src="{{ asset('uploads/images/podcasts/'.$libroSubcategoria->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $libroSubcategoria->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.podcasts.show', [$libroSubcategoria->slug, $libroSubcategoria->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="book3-title-{{$libroSubcategoria->id}}">{{ $libroSubcategoria->title }}</div>
											                <div class="course-instructor color-ligth2" id="book3-instructor-{{$libroSubcategoria->id}}">{{ $libroSubcategoria->user->names.' '.$libroSubcategoria->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="book3-subtitle-{{$libroSubcategoria->id}}">{{ strtolower($libroSubcategoria->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.podcasts.show', [$libroSubcategoria->slug, $libroSubcategoria->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroSubcategoria->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif
						        @endif
				    		@endforeach
					    @endif

					    @if ($cantMentores > 0)
				    		@foreach ($mentoresRelacionados as $mentor)
				    			@if ( ($mentor->courses_count > 0) || ($mentor->podcasts_count > 0) )
				    				<div class="category-title-s color-ligth2" id="mentor-relacionado-{{$mentor->id}}">
						                <span>Mentor: {{ $mentor->names }} {{ $mentor->last_names }}<span>
						            </div>
					    			@if ($mentor->courses_count > 0)
						                @foreach ($mentor->courses as $cursoMentor)
									        <div class="uk-card uk-card-small card-background-ligth" id="curso-mentor-{{$cursoMentor->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($cursoMentor->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoMentor->id, 'curso']) }}">
										                    <img src="{{ asset('uploads/images/courses/'.$cursoMentor->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoMentor->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.courses.show', [$cursoMentor->slug, $cursoMentor->id]) }}">
									                        <img src="{{ asset('uploads/images/courses/'.$cursoMentor->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $cursoMentor->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.courses.show', [$cursoMentor->slug, $cursoMentor->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="course4-title-{{$cursoMentor->id}}">{{ $cursoMentor->title }}</div>
											                <div class="course-instructor color-ligth2" id="course4-instructor-{{$cursoMentor->id}}">{{ $cursoMentor->user->names.' '.$cursoMentor->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="course4-subtitle-{{$cursoMentor->id}}">{{ strtolower($cursoMentor->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.courses.show', [$cursoMentor->slug, $cursoMentor->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoMentor->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif

							        @if ($mentor->podcasts_count > 0)
						                @foreach ($mentor->podcasts as $libroMentor)
									        <div class="uk-card uk-card-small card-background-ligth" id="libro-mentor-{{$libroMentor->id}}">
									            <div class="uk-card-media-top image-div">
									                @if (!is_null($libroMentor->preview))
										                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroMentor->id, 'podcast']) }}">
										                    <img src="{{ asset('uploads/images/podcasts/'.$libroMentor->cover) }}" class="content-image">  
										                    <div class="uk-overlay uk-position-center">
										                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroMentor->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
										                    </div>
										                </a>
									                @else
									                    <a href="{{ route('landing.podcasts.show', [$libroMentor->slug, $cursoMentor->id]) }}">
									                        <img src="{{ asset('uploads/images/podcasts/'.$libroMentor->cover) }}" class="content-image"> 
									                    </a>
									                @endif
									                <div class="image-category-div">{{ $libroMentor->category->title }}</div>  
									            </div>
									            <div class="uk-card-body card-body" style="padding-top: 2%;">
										            <a href="{{ route('landing.podcasts.show', [$libroMentor->slug, $libroMentor->id]) }}">
											            <div style="min-height: 100px;">
											                <div class="course-title color-ligth2" id="book4-title-{{$libroMentor->id}}">{{ $libroMentor->title }}</div>
											                <div class="course-instructor color-ligth2" id="book4-instructor-{{$libroMentor->id}}">{{ $libroMentor->user->names.' '.$libroMentor->user->last_names }}</div>
											                <div class="course-subtitle color-ligth2" id="book4-subtitle-{{$libroMentor->id}}">{{ strtolower($libroMentor->subtitle) }}</div>
											            </div>                    
									                </a>    
									                <div class="uk-text-center" style="padding-top: 15px;">
										                <div class="uk-child-width-1-1" uk-grid> 
										                    <div>
										                        <a class="link-course" href="{{ route('landing.podcasts.show', [$libroMentor->slug, $libroMentor->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
										                    </div>
										                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
											                    <div style="margin-top: 10px;">
											                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroMentor->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
											                    </div>
											                @endif
										                </div>
									               	</div>
									            </div>
									        </div><br>
									    @endforeach
							        @endif
							    @endif
				    		@endforeach
					    @endif
					</div>
				</div>

				{{-- Versión PC --}}
	   			<div class="uk-visible@s" style="padding: 0 20px;">
			   		@if ($cantCursos > 0)
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

			   		@if ($cantLibros > 0)
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

			   		@if ($cantCategorias > 0)
						@foreach ($categoriasRelacionadas as $categoria)
				    		@if ( ($categoria->courses_count > 0) || ($categoria->podcasts_count > 0) )
				    			<div class="t-courses-category color-ligth2" id="categoria-relacionada-pc-{{$categoria->id}}" style="padding: 30px 0px 15px 10px;">Categoría: {{ $categoria->title }}</div>
										
								<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
				    				@if ($categoria->courses_count > 0) 
							           	@foreach ($categoria->courses as $cursoCategoriaPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="curso-categoria-pc-{{$cursoCategoriaPC->id}}">
							           				<div class="uk-card-media-top image-div">
						                   				@if (!is_null($cursoCategoriaPC->preview))
						                       				<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoCategoriaPC->id, 'curso']) }}">
						                            			<img src="{{ asset('uploads/images/courses/'.$cursoCategoriaPC->cover) }}" class="content-image">  
							                            		<div class="uk-overlay uk-position-center">
								                           			<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoCategoriaPC->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                       			</div>
								                   			</a>
							               				@else
										                    <a href="{{ route('landing.courses.show', [$cursoCategoriaPC->slug, $cursoCategoriaPC->id]) }}">
										                        <img src="{{ asset('uploads/images/courses/'.$cursoCategoriaPC->cover) }}" class="content-image"> 
										                    </a>
						                    			@endif
						                   				<div class="image-category-div">{{ $cursoCategoriaPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										       			<a href="{{ route('landing.courses.show', [$cursoCategoriaPC->slug, $cursoCategoriaPC->id]) }}">
									              			<div style="min-height: 100px;">
								                    			<div class="course-title color-ligth2" id="course-cat-pc-title-{{$cursoCategoriaPC->id}}">{{ $cursoCategoriaPC->title }}</div>
								                    			<div class="course-instructor color-ligth2" id="course-cat-pc-instructor-{{$cursoCategoriaPC->id}}">{{ $cursoCategoriaPC->user->names.' '.$cursoCategoriaPC->user->last_names }}</div>
								                    			<div class="course-subtitle color-ligth2" id="course-cat-pc-subtitle-{{$cursoCategoriaPC->id}}">{{ strtolower($cursoCategoriaPC->subtitle) }}</div>
								                			</div>                    
							                   			</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								               				<div class="uk-child-width-1-1" uk-grid>
									                   			<div>
										                            <a class="link-course" href="{{ route('landing.courses.show', [$cursoCategoriaPC->slug, $cursoCategoriaPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
											                    </div>
								                    			@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
												                    <div style="margin-top: 10px;">
													                    <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoCategoriaPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif

							        @if ($categoria->podcasts_count > 0) 
							            @foreach ($categoria->podcasts as $libroCategoriaPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="libro-categoria-pc-{{$libroCategoriaPC->id}}">
								               		<div class="uk-card-media-top image-div">
								                   		@if (!is_null($libroCategoriaPC->preview))
								                       		<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroCategoriaPC->id, 'podcast']) }}">
								                            	<img src="{{ asset('uploads/images/podcasts/'.$libroCategoriaPC->cover) }}" class="content-image">  
								                            	<div class="uk-overlay uk-position-center">
								                                	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroCategoriaPC->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                            	</div>
								                        	</a>
							                   			@else
												            <a href="{{ route('landing.podcasts.show', [$libroCategoriaPC->slug, $libroCategoriaPC->id]) }}">
												                <img src="{{ asset('uploads/images/podcasts/'.$libroCategoriaPC->cover) }}" class="content-image"> 
												            </a>
								                    	@endif
								                   		<div class="image-category-div">{{ $libroCategoriaPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										            	<a href="{{ route('landing.podcasts.show', [$libroCategoriaPC->slug, $libroCategoriaPC->id]) }}">
										                	<div style="min-height: 100px;">
										                    	<div class="course-title color-ligth2" id="book-cat-pc-title-{{$libroCategoriaPC->id}}">{{ $libroCategoriaPC->title }}</div>
										                    	<div class="course-instructor color-ligth2" id="book-cat-pc-instructor-{{$libroCategoriaPC->id}}">{{ $libroCategoriaPC->user->names.' '.$libroCategoriaPC->user->last_names }}</div>
										                    	<div class="course-subtitle color-ligth2" id="book-cat-pc-subtitle-{{$libroCategoriaPC->id}}">{{ strtolower($libroCategoriaPC->subtitle) }}</div>
										                	</div>                    
								                   		</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								                    		<div class="uk-child-width-1-1" uk-grid>
									                        	<div>
												                    <a class="link-course" href="{{ route('landing.podcasts.show', [$libroCategoriaPC->slug, $libroCategoriaPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													            </div>
										                    	@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														            <div style="margin-top: 10px;">
														                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroCategoriaPC->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif
							    </div>
				    		@endif
				    	@endforeach
				    @endif

				    @if ($cantSubcategorias > 0)
				    	@foreach ($subcategoriasRelacionadas as $subcategoria)
				    		@if ( ($subcategoria->courses_count > 0) || ($subcategoria->podcasts_count > 0) )
				    			<div class="t-courses-category color-ligth2" id="subcategoria-relacionada-pc-{{$subcategoria->id}}" style="padding: 30px 0px 15px 10px;">Categoría: {{ $subcategoria->title }}</div>
										
								<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
				    				@if ($subcategoria->courses_count > 0) 
							            @foreach ($subcategoria->courses as $cursoSubcategoriaPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="curso-subcategoria-pc-{{$cursoSubcategoriaPC->id}}">
								               		<div class="uk-card-media-top image-div">
								                   		@if (!is_null($cursoSubcategoriaPC->preview))
								                       		<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoSubcategoriaPC->id, 'curso']) }}">
								                            	<img src="{{ asset('uploads/images/courses/'.$cursoSubcategoriaPC->cover) }}" class="content-image">  
								                            	<div class="uk-overlay uk-position-center">
								                                	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoSubcategoriaPC->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                            	</div>
								                        	</a>
							                   			@else
												            <a href="{{ route('landing.courses.show', [$cursoSubcategoriaPC->slug, $cursoSubcategoriaPC->id]) }}">
												                <img src="{{ asset('uploads/images/courses/'.$cursoSubcategoriaPC->cover) }}" class="content-image"> 
												            </a>
								                    	@endif
								                   		<div class="image-category-div">{{ $cursoSubcategoriaPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										            	<a href="{{ route('landing.courses.show', [$cursoSubcategoriaPC->slug, $cursoSubcategoriaPC->id]) }}">
										                	<div style="min-height: 100px;">
										                    	<div class="course-title color-ligth2" id="course-subcat-pc-title-{{$cursoSubcategoriaPC->id}}">{{ $cursoSubcategoriaPC->title }}</div>
										                    	<div class="course-instructor color-ligth2" id="course-subcat-pc-instructor-{{$cursoSubcategoriaPC->id}}">{{ $cursoSubcategoriaPC->user->names.' '.$cursoSubcategoriaPC->user->last_names }}</div>
										                    	<div class="course-subtitle color-ligth2" id="course-subcat-pc-subtitle-{{$cursoSubcategoriaPC->id}}">{{ strtolower($cursoSubcategoriaPC->subtitle) }}</div>
										                	</div>                    
								                   		</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								                    		<div class="uk-child-width-1-1" uk-grid>
									                        	<div>
												                    <a class="link-course" href="{{ route('landing.courses.show', [$cursoSubcategoriaPC->slug, $cursoSubcategoriaPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													            </div>
										                    	@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														            <div style="margin-top: 10px;">
														                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoSubcategoriaPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif

							        @if ($subcategoria->podcasts_count > 0) 
							            @foreach ($subcategoria->podcasts as $libroSubcategoriaPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="libro-subcategoria-pc-{{$libroSubcategoriaPC->id}}">
								               		<div class="uk-card-media-top image-div">
								                   		@if (!is_null($libroSubcategoriaPC->preview))
								                       		<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroSubcategoriaPC->id, 'podcast']) }}">
								                            	<img src="{{ asset('uploads/images/podcasts/'.$libroSubcategoriaPC->cover) }}" class="content-image">  
								                            	<div class="uk-overlay uk-position-center">
								                                	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroSubcategoriaPC->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                            	</div>
								                        	</a>
							                   			@else
												            <a href="{{ route('landing.podcasts.show', [$libroSubcategoriaPC->slug, $libroSubcategoriaPC->id]) }}">
												                <img src="{{ asset('uploads/images/podcasts/'.$libroSubcategoriaPC->cover) }}" class="content-image"> 
												            </a>
								                    	@endif
								                   		<div class="image-category-div">{{ $libroSubcategoriaPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										            	<a href="{{ route('landing.podcasts.show', [$libroSubcategoriaPC->slug, $libroSubcategoriaPC->id]) }}">
										                	<div style="min-height: 100px;">
										                    	<div class="course-title color-ligth2" id="book-subcat-pc-title-{{$libroSubcategoriaPC->id}}">{{ $libroSubcategoriaPC->title }}</div>
										                    	<div class="course-instructor color-ligth2" id="book-subcat-pc-instructor-{{$libroSubcategoriaPC->id}}">{{ $libroSubcategoriaPC->user->names.' '.$libroSubcategoriaPC->user->last_names }}</div>
										                    	<div class="course-subtitle color-ligth2" id="book-subcat-pc-subtitle-{{$libroSubcategoriaPC->id}}">{{ strtolower($libroSubcategoriaPC->subtitle) }}</div>
										                	</div>                    
								                   		</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								                    		<div class="uk-child-width-1-1" uk-grid>
									                        	<div>
												                    <a class="link-course" href="{{ route('landing.podcasts.show', [$libroSubcategoriaPC->slug, $libroSubcategoriaPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													            </div>
										                    	@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														            <div style="margin-top: 10px;">
														                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroSubcategoriaPC->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif
							    </div>
				    		@endif
				    	@endforeach
				    @endif

				    @if ($cantMentores > 0)
				    	@foreach ($mentoresRelacionados as $mentor)
				    		@if ( ($mentor->courses_count > 0) || ($mentor->podcasts_count > 0) )
				    			<div class="t-courses-category color-ligth2" id="mentor-relacionado-pc-{{$mentor->id}}" style="padding: 30px 0px 15px 10px;">Mentor: {{ $mentor->names }} {{ $mentor->last_names }}</div>

				    			<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
				    				@if ($mentor->courses_count > 0) 
							            @foreach ($mentor->courses as $cursoMentorPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="curso-mentor-pc-{{$cursoMentorPC->id}}">
								               		<div class="uk-card-media-top image-div">
								                   		@if (!is_null($cursoMentorPC->preview))
								                       		<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoMentorPC->id, 'curso']) }}">
								                            	<img src="{{ asset('uploads/images/courses/'.$cursoMentorPC->cover) }}" class="content-image">  
								                            	<div class="uk-overlay uk-position-center">
								                                	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoMentorPC->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                            	</div>
								                        	</a>
							                   			@else
												            <a href="{{ route('landing.courses.show', [$cursoMentorPC->slug, $cursoMentorPC->id]) }}">
												                <img src="{{ asset('uploads/images/courses/'.$cursoMentorPC->cover) }}" class="content-image"> 
												            </a>
								                    	@endif
								                   		<div class="image-category-div">{{ $cursoMentorPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										            	<a href="{{ route('landing.courses.show', [$cursoMentorPC->slug, $cursoMentorPC->id]) }}">
										                	<div style="min-height: 100px;">
										                    	<div class="course-title color-ligth2" id="course-mentor-pc-title-{{$cursoMentorPC->id}}">{{ $cursoMentorPC->title }}</div>
										                    	<div class="course-instructor color-ligth2" id="course-mentor-pc-instructor-{{$cursoMentorPC->id}}">{{ $cursoMentorPC->user->names.' '.$cursoMentorPC->user->last_names }}</div>
										                    	<div class="course-subtitle color-ligth2" id="course-mentor-pc-subtitle-{{$cursoMentorPC->id}}">{{ strtolower($cursoMentorPC->subtitle) }}</div>
										                	</div>                    
								                   		</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								                    		<div class="uk-child-width-1-1" uk-grid>
									                        	<div>
												                    <a class="link-course" href="{{ route('landing.courses.show', [$cursoMentorPC->slug, $cursoMentorPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													            </div>
										                    	@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														            <div style="margin-top: 10px;">
														               <a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoMentorPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif

							        @if ($mentor->podcasts_count > 0) 
							            @foreach ($mentor->podcasts as $libroMentorPC)
											<div>
					   							<div class="uk-card uk-card-small card-background-ligth" id="libro-mentor-pc-{{$libroMentorPC->id}}">
								               		<div class="uk-card-media-top image-div">
								                   		@if (!is_null($libroMentorPC->preview))
								                       		<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroMentorPC->id, 'podcast']) }}">
								                            	<img src="{{ asset('uploads/images/podcasts/'.$libroMentorPC->cover) }}" class="content-image">  
								                            	<div class="uk-overlay uk-position-center">
								                                	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroMentorPC->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
								                            	</div>
								                        	</a>
							                   			@else
												            <a href="{{ route('landing.podcasts.show', [$libroMentorPC->slug, $libroMentorPC->id]) }}">
												                <img src="{{ asset('uploads/images/podcasts/'.$libroMentorPC->cover) }}" class="content-image"> 
												            </a>
								                    	@endif
								                   		<div class="image-category-div">{{ $libroMentorPC->category->title }}</div>  
								                	</div>
								               		<div class="uk-card-body card-body" style="padding-top: 2%;">
										            	<a href="{{ route('landing.podcasts.show', [$libroMentorPC->slug, $libroMentorPC->id]) }}">
										                	<div style="min-height: 100px;">
										                    	<div class="course-title color-ligth2" id="book-mentor-pc-title-{{$libroMentorPC->id}}">{{ $libroMentorPC->title }}</div>
										                    	<div class="course-instructor color-ligth2" id="book-mentor-pc-instructor-{{$libroMentorPC->id}}">{{ $libroMentorPC->user->names.' '.$libroMentorPC->user->last_names }}</div>
										                    	<div class="course-subtitle color-ligth2" id="book-mentor-pc-subtitle-{{$libroMentorPC->id}}">{{ strtolower($libroMentorPC->subtitle) }}</div>
										                	</div>                    
								                   		</a>    
							                   			<div class="uk-text-center" style="padding-top: 15px;">
								                    		<div class="uk-child-width-1-1" uk-grid>
									                        	<div>
												                    <a class="link-course" href="{{ route('landing.podcasts.show', [$libroMentorPC->slug, $libroMentorPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													            </div>
										                    	@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														            <div style="margin-top: 10px;">
														                <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroMentorPC->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
														            </div>
													            @endif
									                   		</div>
								               			</div>
								           			</div>
							            		</div>
							           		</div>
							            @endforeach
							        @endif
							    </div>
				    		@endif
				    	@endforeach
				    @endif
		   		</div>
		    @endif
		</div>
    </div>
@endsection