@extends('layouts.landing')

@push('scripts')
   	<script>
   		$(function(){    
	        $('#video-pro').bind('contextmenu',function() { return false; });
	    });
      	function change_category($categoria){
         	document.getElementById("cursos").style.display = 'none';
         	document.getElementById("wait").style.display = 'block';

         	var url = {{ $www }};
         	if (url == 1){
            	var path = "https://www.transformatepro.com/ajax/change-category/"+$categoria;
         	}else{
            	var path = "https://transformatepro.com/ajax/change-category/"+$categoria;
         	}

         	//var path = "http://localhost:8000/ajax/change-category/"+$categoria;
                        
         	$.ajax({
	            type:"GET",
	            url:path,
	            success:function(ans){
	               	$("#cursos").html(ans);     
	               	if (document.getElementById("tema").value == 'dark'){
	                  	$(".card-background-ligth").each(function(index) {
	                     	$("#"+$(this).attr('id')).removeClass("card-background-ligth");
	                     	$("#"+$(this).attr('id')).addClass("card-background-dark");
	                  	});
	                  	$(".color-ligth2").each(function(index) {
	                     	$("#"+$(this).attr('id')).removeClass("color-ligth2");
	                     	$("#"+$(this).attr('id')).addClass("color-dark2");
	                  	});
	               	}    
	               	document.getElementById("wait").style.display = 'none';
	               	document.getElementById("cursos").style.display = 'block';              
	            }
	        });
      	}
   </script>
@endpush

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

  	<div class="t-mentor" style="padding: 0px 5% 0px 5%;">
		<video src="{{ asset('template/videos/video_curso_pro_mayo.webm') }}" loop autoplay muted id="video-pro"></video>
		<div class="course-pro">
         	<div class="uk-visible@s">
            	<div class="uk-text-bold title">Curso PRO del Mes</div>
            	<div class="description">Un café con tu Ángel de la guarda <br> <span style="font-size: 18px; font-style: italic;">Conecta con tu ángel de la guarda, conoce el plan de tu alma y vive la vida.</span></div>
         	</div>
         	<div class="uk-hidden@s">
            	<div class="uk-text-bold title">Curso PRO del Mes</div>
         	</div>
         
         	<div class="t-mentor-button-div uk-visible@s">
            	<div uk-grid >
               		<div class="uk-width-1-2 course-button-white-div" style="text-align: right;">
                  		<a class="courses-button-white" href="{{ route('landing.courses.show', ['un-cafe-con-tu-angel-de-la-guarda', 63]) }}"><i class="fa fa-plus"></i>  Más Información</a>
               		</div>
               		<div class="uk-width-1-2 register-button-blue-div" style="text-align: left;">
                  		<a class="register-button-blue" href="{{ route('landing.shopping-cart.store', [63, 'curso']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</a>
               		</div>
            	</div>
         	</div>
      	</div>
   	</div>

   	{{-- Sección Botones --}}
   	<div class="course-pro-buttons background-ligth uk-hidden@s" id="transformatepro-section">
      	<div uk-grid>
         	<div class="uk-width-1-2" style="padding-right: 10px;">
            	<a class="register-button" href="{{ route('landing.courses.show', ['un-cafe-con-tu-angel-de-la-guarda', 63]) }}"><i class="fa fa-plus"></i>  Más Información</a>
         	</div>
         	<div class="uk-width-1-2" style="padding-left: 10px;">
         		@if (Auth::guest())
            		<a class="courses-button" href="#modal-login" uk-toggle><i class="fas fa-cart-plus"></i>  Comprar T-Curso</a>
            	@elseif (Auth::user()->role_id == 1)
            		<a class="courses-button" href="{{ route('landing.shopping-cart.store', [63, 'curso']) }}"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</a>
            	@endif
         	</div>
      	</div>
   	</div>

   	<div class="content background-ligth2" id="main-content" style="padding: 0px 5% 20px 5%;">
		{{-- Sección de Categorías --}}
		<div class="categories">
			<div uk-slider="autoplay: true; autoplay-interval: 3000;">
				<div class="uk-position-relative">
				    <div class="uk-slider-container uk-light">
				        <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
							<li class="category">
				                <a onclick="change_category(100);">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #305089;">
				                        <div class="category-icon"><i class="fab fa-tumblr"></i></div>
				                        <div class="category-title">T-Master Class</div>
				                    </div>
				                </a>
				            </li>
							<li class="category">
				                <a onclick="change_category(0);">
				                    <div class="uk-card uk-card-default uk-card-small uk-text-center category-card" style="background-color: #006B9B;">
				                        <div class="category-icon"><i class="fas fa-book"></i></div>
				                        <div class="category-title">T-Libros</div>
				                    </div>
				                </a>
				            </li>
				            @foreach ($categorias as $categoria)
				                <li class="category">
				                    <a onclick="change_category({{$categoria->id}});">
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
			{{-- Versión Móvil  --}}
	   		<div class="uk-hidden@s">
			    <div class="courses">
			    	@if ( ($cantCursos == 0) && ($cantLibros == 0) )
			    		<div class="uk-text-center color-ligth2" style="font-size: 18px;">
	   						No existe contenido relacionado con la categoría o área seleccionada...
	   					</div>
	   				@else
	   					{{-- Cursos --}}
				    	@if ($cantCursos > 0)
						    @foreach ($cursos as $curso)
						        <div class="uk-card uk-card-small card-background-ligth" id="curso-destacado-{{$curso->id}}">
						            <div class="uk-card-media-top image-div">
						                @if (!is_null($curso->preview))
		  									@if ($categoriaSeleccionada == 100)
											  	<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}">
							                    	<img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image">  
							                    	<div class="uk-overlay uk-position-center">
							                        	<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
							                    	</div>
							                	</a>
											@else
												<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}">
													<img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image">  
													<div class="uk-overlay uk-position-center">
														<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$curso->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
													</div>
												</a>
											@endif
						                @else
											@if ($categoriaSeleccionada == 100)
												<a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
													<img src="{{ asset('uploads/images/master-class/'.$curso->cover) }}" class="content-image"> 
												</a>
											@else
												<a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
													<img src="{{ asset('uploads/images/courses/'.$curso->cover) }}" class="content-image"> 
												</a>
											@endif
						                @endif
										@if ($categoriaSeleccionada != 100)
						                	<div class="image-category-div">{{ $curso->category->title }}</div>  
										@endif
						            </div>
						            <div class="uk-card-body card-body" style="padding-top: 2%;">
										@if ($categoriaSeleccionada == 100)
							            	<a href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}">
										@else
											<a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
										@endif
								            <div style="min-height: 100px;">
								                <div class="course-title color-ligth2" id="course-title-{{$curso->id}}">{{ $curso->title }}</div>
								                @if ($categoriaSeleccionada != 100)
													<div class="course-instructor color-ligth2" id="course-instructor-{{$curso->id}}">{{ $curso->user->names.' '.$curso->user->last_names }}</div>
								                @endif
												<div class="course-subtitle color-ligth2" id="course-subtitle-{{$curso->id}}">{{ strtolower($curso->subtitle) }}</div>
								            </div>                    
						                </a>    
						                <div class="uk-text-center" style="padding-top: 15px;">
							                <div class="uk-child-width-1-1" uk-grid> 
							                    <div>
													@if ($categoriaSeleccionada == 100)
							                        	<a class="link-course" href="{{ route('landing.master-class.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
													@else
														<a class="link-course" href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
		  											@endif
							                    </div>
												@if ($categoriaSeleccionada != 100)
													@if (Auth::guest())
														<div style="margin-top: 10px;">
															<a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
														</div>
													@elseif (Auth::user()->role_id == 1)
		  												@if (!is_null(Auth::user()->membership_id))
		  													@if (Auth::user()->membership_courses < 3)
																<div style="margin-top: 10px;">
																	<a class="link-course" href="{{ route('students.courses.add', [$curso->id, 'membresia']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Agregar a Mis Cursos</span></a>
																</div>
															@else
																<div style="margin-top: 10px;">
																	<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
																</div>
															@endif
														@else
															@if ($curso->price > 0)
																<div style="margin-top: 10px;">
																	<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
																</div>
															@endif
														@endif
													@endif
												@endif
							                </div>
						               	</div>
						            </div>
						        </div><br>
						    @endforeach
					   	@endif

					   	{{-- Libros --}}
				    	@if ($cantLibros > 0)
						    @foreach ($libros as $libro)
						        <div class="uk-card uk-card-small card-background-ligth" id="libro-destacado-{{$libro->id}}">
						            <div class="uk-card-media-top image-div">
						                @if (!is_null($libro->preview))
							               	<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libro->id, 'podcast']) }}">
							                    <img src="{{ asset('uploads/images/podcasts/'.$libro->cover) }}" class="content-image">  
							                    <div class="uk-overlay uk-position-center">
							                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libro->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
							                    </div>
							                </a>
						                @else
							                <a href="{{ route('landing.podcasts.show', [$libro->slug, $libro->id]) }}">
						                        <img src="{{ asset('uploads/images/podcasts/'.$libro->cover) }}" class="content-image"> 
						                    </a>
						                @endif
						                <div class="image-category-div">{{ $libro->category->title }}</div>  
						            </div>
						            <div class="uk-card-body card-body" style="padding-top: 2%;">
							            <a href="{{ route('landing.podcasts.show', [$libro->slug, $libro->id]) }}">
								            <div style="min-height: 100px;">
								                <div class="course-title color-ligth2" id="book-title-{{$libro->id}}">{{ $libro->title }}</div>
								                <div class="course-instructor color-ligth2" id="book-instructor-{{$libro->id}}">{{ $libro->user->names.' '.$libro->user->last_names }}</div>
								                <div class="course-subtitle color-ligth2" id="book-subtitle-{{$libro->id}}">{{ strtolower($libro->subtitle) }}</div>
								            </div>                    
						                </a>    
						                <div class="uk-text-center" style="padding-top: 15px;">
							                <div class="uk-child-width-1-1" uk-grid> 
							                    <div>
							                        <a class="link-course" href="{{ route('landing.podcasts.show', [$libro->slug, $libro->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
							                    </div>
							                    @if (Auth::guest())
													<div style="margin-top: 10px;">
														<a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
													</div>
							                    @elseif (Auth::user()->role_id == 1)
								                    <div style="margin-top: 10px;">
								                        <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libro->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
								                    </div>
								                @endif
							                </div>
						               	</div>
						            </div>
						        </div><br>
						    @endforeach
					   	@endif
					@endif
			    </div>
			</div>

			{{-- Versión PC --}}
   			<div class="uk-visible@s" style="padding: 0 20px;">
	   			@if ( ($cantCursos == 0) && ($cantLibros == 0) )
	   				<div class="uk-text-center" style="padding-top: 30px; font-size: 22px;">
	   					No existe contenido relacionado con la categoría o área seleccionada...
	   				</div>
	   			@else
	   				{{-- Cursos --}}
		   			@if ($cantCursos > 0)
		   				<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
			   				@foreach ($cursos as $cursoPC)
			   					<div>
				   					<div class="uk-card uk-card-small card-background-ligth" id="curso-pc-{{$cursoPC->id}}">
							            <div class="uk-card-media-top image-div">
							               	@if (!is_null($cursoPC->preview))
											   	@if ($categoriaSeleccionada == 100)
													<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoPC->id, 'clase']) }}">
														<img src="{{ asset('uploads/images/master-class/'.$cursoPC->cover) }}" class="content-image">  
														<div class="uk-overlay uk-position-center">
															<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoPC->id, 'clase']) }}"><i class="fas fa-play icon-play-card"></i></a>
														</div>
													</a>
												@else
													<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoPC->id, 'curso']) }}">
														<img src="{{ asset('uploads/images/courses/'.$cursoPC->cover) }}" class="content-image">  
														<div class="uk-overlay uk-position-center">
															<a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$cursoPC->id, 'curso']) }}"><i class="fas fa-play icon-play-card"></i></a>
														</div>
													</a>
												@endif
							                @else
												@if ($categoriaSeleccionada == 100)
													<a href="{{ route('landing.master-class.show', [$cursoPC->slug, $cursoPC->id]) }}">
														<img src="{{ asset('uploads/images/master-class/'.$cursoPC->cover) }}" class="content-image"> 
													</a>
												@else
													<a href="{{ route('landing.courses.show', [$cursoPC->slug, $cursoPC->id]) }}">
														<img src="{{ asset('uploads/images/courses/'.$cursoPC->cover) }}" class="content-image"> 
													</a>
												@endif
							                @endif
											@if ($categoriaSeleccionada != 100)
							                	<div class="image-category-div">{{ $cursoPC->category->title }}</div>  
											@endif
							            </div>
							            <div class="uk-card-body card-body" style="padding-top: 2%;">
											@if ($categoriaSeleccionada == 100)
									        	<a href="{{ route('landing.master-class.show', [$cursoPC->slug, $cursoPC->id]) }}">
											@else
												<a href="{{ route('landing.courses.show', [$cursoPC->slug, $cursoPC->id]) }}">
											@endif
										        <div style="min-height: 100px;">
										            <div class="course-title color-ligth2" id="course-pc-title-{{$cursoPC->id}}">{{ $cursoPC->title }}</div>
										            @if ($categoriaSeleccionada != 100) 
														<div class="course-instructor color-ligth2" id="course-pc-instructor-{{$cursoPC->id}}">{{ $cursoPC->user->names.' '.$cursoPC->user->last_names }}</div>
										            @endif
													<div class="course-subtitle color-ligth2" id="course-pc-subtitle-{{$cursoPC->id}}">{{ strtolower($cursoPC->subtitle) }}</div>
										        </div>                    
								            </a>    
							                <div class="uk-text-center" style="padding-top: 15px;">
								                <div class="uk-child-width-1-1" uk-grid>
									                <div>
														@if ($categoriaSeleccionada == 100)
									                    	<a class="link-course" href="{{ route('landing.master-class.show', [$cursoPC->slug, $cursoPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
														@else
															<a class="link-course" href="{{ route('landing.courses.show', [$cursoPC->slug, $cursoPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
														@endif
									                </div>
													@if ($categoriaSeleccionada != 100)
														@if (Auth::guest())
															<div style="margin-top: 10px;">
																<a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
															</div>
														@elseif (Auth::user()->role_id == 1)
															@if (!is_null(Auth::user()->membership_id))
																@if (Auth::user()->membership_courses < 3)
																	<div style="margin-top: 10px;">
																		<a class="link-course" href="{{ route('students.courses.add', [$cursoPC->id, 'membresia']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Agregar a Mis Cursos</span></a>
																	</div>
																@else
																	<div style="margin-top: 10px;">
																		<a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
																	</div>
																@endif
															@else
																@if ($cursoPC->price > 0)
																	<div style="margin-top: 10px;">
																		<a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
																	</div>
																@endif
															@endif
														@endif
													@endif
								                </div>
							                </div>
							            </div>
							        </div>
							    </div>
			   				@endforeach
			   			</div>	
		   			@endif

		   			{{-- Libros --}}
		   			@if ($cantLibros > 0)
		   				@if ($categoriaSeleccionada != 0)
		   					<div class="t-courses-category color-ligth2" id="t-books" style="padding: 30px 0px 15px 10px;">T-Libros</div>
		   				@endif
		   				<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
	   						@foreach ($libros as $libroPC)
			   					<div>
				   					<div class="uk-card uk-card-small card-background-ligth" id="libro-pc-{{$libroPC->id}}">
					                	<div class="uk-card-media-top image-div">
					                    	@if (!is_null($libroPC->preview))
						                       	<a class="view-preview" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroPC->id, 'podcast']) }}">
								                    <img src="{{ asset('uploads/images/podcasts/'.$libroPC->cover) }}" class="content-image">  
								                    <div class="uk-overlay uk-position-center">
								                        <a class="view-preview link-play-card" uk-toggle="target: #modal-preview" data-viewPreview="{{ route('ajax.load-preview', [$libroPC->id, 'podcast']) }}"><i class="fas fa-play icon-play-card"></i></a>
							                        </div>
						                        </a>
					                   		@else
							                    <a href="{{ route('landing.podcasts.show', [$libroPC->slug, $libroPC->id]) }}">
								                    <img src="{{ asset('uploads/images/podcasts/'.$libroPC->cover) }}" class="content-image"> 
								                </a>
							                @endif
							               	<div class="image-category-div">{{ $libroPC->category->title }}</div>  
						             	</div>
					                	<div class="uk-card-body card-body" style="padding-top: 2%;">
							                <a href="{{ route('landing.podcasts.show', [$libroPC->slug, $libroPC->id]) }}">
								                <div style="min-height: 100px;">
										            <div class="course-title color-ligth2" id="book-pc-title-{{$libroPC->id}}">{{ $libroPC->title }}</div>
										            <div class="course-instructor color-ligth2" id="book-pc-instructor-{{$libroPC->id}}">{{ $libroPC->user->names.' '.$libroPC->user->last_names }}</div>
										            <div class="course-subtitle color-ligth2" id="book-pc-subtitle-{{$libroPC->id}}">{{ strtolower($libroPC->subtitle) }}</div>
										        </div>                    
								            </a>    
							                <div class="uk-text-center" style="padding-top: 15px;">
								                <div class="uk-child-width-1-1" uk-grid>
									                <div>
									                    <a class="link-course" href="{{ route('landing.podcasts.show', [$libroPC->slug, $libroPC->id]) }}"> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
									                </div>
									                @if (Auth::guest())
														<div style="margin-top: 10px;">
															<a class="link-course" href="#modal-login" uk-toggle> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
														</div>
									                @elseif (Auth::user()->role_id == 1)
										                <div style="margin-top: 10px;">
										                    <a class="link-course" href="{{ route('landing.shopping-cart.store', [$libroPC->id, 'podcast']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Libro</span></a>
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
		   		@endif
	   		</div>
		</div>
   	</div>
@endsection