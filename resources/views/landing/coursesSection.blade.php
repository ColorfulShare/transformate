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
													@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
														@if ($curso->price > 0)
															<div style="margin-top: 10px;">
																<a class="link-course" href="{{ route('landing.shopping-cart.store', [$curso->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
															</div>
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
							                    @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
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
														@if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
															@if ($cursoPC->price > 0)
																<div style="margin-top: 10px;">
																	<a class="link-course" href="{{ route('landing.shopping-cart.store', [$cursoPC->id, 'curso']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Curso</span></a>
																</div>
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
									                @if ( (Auth::guest()) || (Auth::user()->role_id == 1) )
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