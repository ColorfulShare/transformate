@extends('layouts.landing')

@section('content')
    @if (Session::has('msj-informativo'))
      	<div class="row">
         	<div class="col-md-12 alert alert-info uk-text-center" style="margin-bottom: 0 !important;">
            	<strong>{{ Session::get('msj-informativo') }}</strong>
         	</div>
      	</div>
    @endif

    @if ($cursosRegalo > 0)
      	<div class="row" >
         	<div class="col-md-12 alert alert-success uk-text-center" style="margin-bottom: 0 !important;">
            	<strong>Tienes nuevo contenido de regalo cortesía de TransfórmatePro. Pincha <a href="{{ route('students.my-gifts') }}">AQUÍ</a> para verlo.</strong>
         	</div>
      	</div>
    @endif

  	<div class="courses-banner">
    	<img src="{{ asset('images/courses_banner.jpg') }}" alt="" class="uk-visible@s">
    	<img src="{{ asset('images/courses_banner_movil.jpg') }}" alt="" class="uk-hidden@s">
    	<div class="courses-banner-text">
         	<h1 class="uk-text-bold title">Más que cursos, son una guía de transformación</h1>
         	<span class="description">Son más de <strong>{{ $totalCursos }}</strong> cursos, en los que te ayudamos a crecer como profesional y persona</span>
        </div>
    </div>

   	<div class="content-courses">
		{{-- Sección de Categorías --}}
		<div class="categories">
			<div uk-slider="autoplay: true; autoplay-interval: 3000;">
				<div class="uk-position-relative">
				    <div class="uk-slider-container uk-light">
				        <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
				        	<li class="category">
				                <a href="{{ route('landing.courses', ['destacados', 'destacados']) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="far fa-star"></i></div>
				                        <div class="category-title">Destacados</div>
				                    </div>
				                </a>
				            </li>
				            <li class="category">
				                <a href="{{ route('landing.courses', ['mas-vendidos', 'vendidos']) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="fas fa-fire"></i></div>
				                        <div class="category-title">Más Vendidos</div>
				                    </div>
				                </a>
				            </li>
				            <li class="category">
				                <a href="{{ route('landing.courses', ['recomendados', 'recomendados']) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="far fa-heart"></i></div>
				                        <div class="category-title">Recomendados</div>
				                    </div>
				                </a>
				            </li>
				            @foreach ($categorias as $categoriaLista)
				                <li class="category">
				                    <a href="{{ route('landing.courses', [$categoriaLista->slug, $categoriaLista->id]) }}">
				                        <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: {{ $categoriaLista->color }};">
				                            <div class="category-icon"><i class="{{ $categoriaLista->icon }}"></i></div>
				                            <div class="category-title">{{ $categoriaLista->title}}</div>
				                        </div>
				                    </a>
				                </li>
				            @endforeach
				            <li class="category">
				                <a href="{{ route('landing.courses', ['t-master-class', 100]) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="fab fa-tumblr"></i></div>
				                        <div class="category-title">T-Master Class</div>
				                    </div>
				                </a>
				            </li>
							<li class="category">
				                <a href="{{ route('landing.courses', ['t-books', 'tbooks']) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #006B9B;">
				                        <div class="category-icon"><i class="fas fa-book"></i></div>
				                        <div class="category-title">T-Libros</div>
				                    </div>
				                </a>
				            </li>
				            <li class="category">
				                <a href="{{ route('landing.courses', ['t-mentorings', 'tmentorings']) }}">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="fas fa-landmark"></i></div>
				                        <div class="category-title">T-Mentorings</div>
				                    </div>
				                </a>
				            </li>
				        </ul>
				    </div>
				</div>
			</div>
		</div>

		<div>
			@if ($cursos->count() > 0)
				<div class="courses-category-selected">{{ $tituloCategoriaSeleccionada }}</div>
	    		<ul class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl" uk-grid>
		            @foreach ($cursos as $curso)
			            <li class="course uk-transition-toggle" tabindex="0">
		                    <div class="uk-card uk-card-small course-card">
		                        <div class="uk-card-media-top image-div">
		                            @if (!is_null($curso->preview))
		                                @if ($categoriaSeleccionada == 100)
		                                    <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}">
			                                    <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @elseif ($categoriaSeleccionada == 'tbooks')
			                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'podcast']) }}">
			                                    <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @elseif ($categoriaSeleccionada == 'tmentorings')
			                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'certification']) }}">
			                                    <img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @else
			                                <a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
			                                    <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">
		                                    </a>
		                                @endif
		                            @else
		                                @if ($categoriaSeleccionada == 100)
		                                    <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
		                                        <img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image">
		                                    </a>
		                                @elseif ($categoriaSeleccionada == 'tbooks')
		                                    <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
			                                    <img src="{{ asset('uploads/images/podcasts/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @elseif ($categoriaSeleccionada == 'tmentorings')
		                                    <a href="{{ route('landing.certifications.show', [$curso->slug, $curso->id]) }}">
			                                 	<img src="{{ asset('uploads/images/certifications/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @else
			                                <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
			                                    <img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">
			                                </a>
			                            @endif
		                            @endif
		                        </div>
		                        <div class="uk-card-body card-body">
		                            @if ($categoriaSeleccionada == 100)
		                                <a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
		                            @elseif ($categoriaSeleccionada == 'tbooks')
		                                <a href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">
		                            @elseif ($categoriaSeleccionada == 'tmentorings')
		                                <a href="{{ route('landing.certifications.show', [$curso->slug, $curso->id]) }}">
		                            @else
		                                <a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
		                            @endif
		                                <div>
		                                    <div class="course-title">{{ $curso->title }}</div>
		                                    @if ($categoriaSeleccionada != 100)
			                                    <div class="course-instructor">Por: {{ $curso->user->names.' '.$curso->user->last_names }}</div>
			                                    <div class="course-category"><strong>{{ $curso->category->title }}</strong></div>
			                                @endif
		                                    <div class="course-subtitle">{{ ucfirst($curso->subtitle) }}</div>

		                                    <br>
		                                    @if ($categoriaSeleccionada == 100)
		                                        <a class="show-more-link" href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">Ver más</a>
		                                    @elseif ($categoriaSeleccionada == 'tbooks')
		                                        <a class="show-more-link" href="{{ route('landing.podcasts.show', [$curso->slug, $curso->id]) }}">Ver más</a>
		                                    @elseif ($categoriaSeleccionada == 'tmentorings')
		                                        <a class="show-more-link" href="{{ route('landing.certifications.show', [$curso->slug, $curso->id]) }}">Ver más</a>
		                                    @else
		                                        <a class="show-more-link" href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">Ver más</a>
		                                    @endif
		                                </div>
		                            </a>
		                            @if ($categoriaSeleccionada != 100)
			                            <div class="card-buttons">
			                                @if (Auth::guest())
			                                    <a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course2">Agregar al carrito</span></a>
			                                @elseif (Auth::user()->role_id == 1)
			                                    @if ( ($categoriaSeleccionada != 'tbooks') && ($categoriaSeleccionada != 'tmentorings') )
			                                        @if (!in_array($curso->id, $misCursos))
					                                    @if (!is_null(Auth::user()->membership_id))
					                                        @if (Auth::user()->membership_courses < 3)
					                                            <a class="link-course" href="{{ route('students.courses.add', [$curso->id, 'membresia']) }}"> <span class="btn-course2">Agregar a Mis Cursos</span></a>
					                                        @else
					                                            <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course2">Agregar al Carrito</span></a>
					                                        @endif
					                                    @else
					                                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course2">Agregar al Carrito</span></a>
					                                    @endif
					                                @else
					                                    <a class="link-course" href="{{ route('students.courses.resume', [$curso->slug, $curso->id]) }}"> <span class="btn-course3">Continuar T-Course</span></a>
					                                @endif
				                                @elseif ($categoriaSeleccionada == 'tbooks')
				                                    @if (!in_array($curso->id, $misLibros))
				                                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'podcast']) }}"> <span class="btn-course2">Agregar al Carrito</span></a>
				                                    @else
				                                        <a class="link-course" href="{{ route('students.podcasts.resume', [$curso->slug, $curso->id]) }}"> <span class="btn-course3">Continuar T-Book</span></a>
				                                    @endif
				                                @elseif ($categoriaSeleccionada == 'tmentorings')
				                                    @if (!in_array($curso->id, $misCertificaciones))
				                                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'certificacion']) }}"> <span class="btn-course2">Agregar al Carrito</span></a>
				                                    @else
				                                        <a class="link-course" href="{{ route('students.certifications.resume', [$curso->slug, $curso->id]) }}"> <span class="btn-course3">Continuar T-Mentoring</span></a>
				                                    @endif
				                                @endif
			                                @endif
			                            </div>
			                        @endif
		                        </div>
		                        <div class="uk-text-center course-price">COP {{ number_format($curso->price, 0, ',', '.') }}</div>
		                    </div>
		                </li>
		            @endforeach
	            </ul>
	        @else
	            <div style="padding-top: 10px; font-size: 21px;">
	                No existen cursos relacionados con esta categoría...
	            </div>
	        @endif
		</div>
   	</div>
@endsection