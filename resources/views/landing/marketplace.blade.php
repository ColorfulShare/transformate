@extends('layouts.landing')

@push('scripts')
   	<script>
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
	
	<div class="header-background-ligth" id="t-events">
        <div class="uk-position-relative uk-visible-toggle uk-light uk-visible@s" tabindex="-1" uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
            <ul class="uk-slider-items uk-grid">
               	@foreach ($productosDestacados as $productoDestacado)
                  	<li class="uk-width-1-1">
                     	<div class="uk-panel">
                        	<a href=""><img src="{{ asset('uploads/images/products/'.$productoDestacado->cover) }}" alt="{{ $productoDestacado->name }}"></a>
                    	</div>
                  	</li>
               @endforeach
            </ul>
            <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
            <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
        </div>
    </div>

   	<div class="content background-ligth2" id="main-content">
		{{-- Sección de Categorías --}}
		<div class="categories">
			<div uk-slider="autoplay: true; autoplay-interval: 3000;">
				<div class="uk-position-relative">
				    <div class="uk-slider-container uk-light">
				        <ul class="uk-slider-items uk-child-width-1-4 uk-child-width-1-4@s uk-child-width-1-6@m uk-grid">
				            @foreach ($categoriasMarket as $categoria)
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

		<div id="productos">
			{{-- Versión Móvil  --}}
	   		<div class="uk-hidden@s">
			    <div class="courses">
			    	@if ($productos->count() == 0) 
			    		<div class="uk-text-center color-ligth2" style="font-size: 18px;">
	   						No existen productos relacionados con la categoría seleccionada...
	   					</div>
	   				@else
	   					{{-- Productos --}}
						@foreach ($productos as $producto)
							<div class="uk-card uk-card-small card-background-ligth" id="producto-{{$producto->id}}">
						        <div class="uk-card-media-top image-div">
									<a href="{{ route('landing.courses.show', [$curso->slug, $curso->id]) }}">
										<img src="{{ asset('uploads/images/products/'.$producto->cover) }}" class="content-image"> 
									</a>
						            <div class="image-category-div">{{ $producto->category->title }}</div>  
						        </div>
						        <div class="uk-card-body card-body" style="padding-top: 2%;">
									<a href="">
										<div style="min-height: 100px;">
								            <div class="course-title color-ligth2" id="product-name-{{$producto->id}}">{{ $producto->name }}</div>
											<div class="course-instructor color-ligth2" id="product-owner-{{$producto->id}}">{{ $producto->user->names.' '.$producto->user->last_names }}</div>
								        </div>                    
						            </a>    
						            <div class="uk-text-center" style="padding-top: 15px;">
							            <div class="uk-child-width-1-1" uk-grid> 
							                <div>
												<a class="link-course" href=""> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
							                </div>
											<div style="margin-top: 10px;">
												<a class="link-course" href="{{ route('landing.shopping-cart.store', [$producto->id, 'producto']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Product</span></a>
											</div>
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
	   			@if ($productos->count() == 0)
	   				<div class="uk-text-center" style="padding-top: 30px; font-size: 22px;">
	   					No existen productos relacionado con la categoría o seleccionada...
	   				</div>
	   			@else
	   				{{-- Productos --}}
		   			<div class="uk-child-width-1-4@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-3" uk-grid>
			   			@foreach ($productos as $productoPC)
			   				<div>
				   				<div class="uk-card uk-card-small card-background-ligth" id="producto-pc-{{$productoPC->id}}">
							        <div class="uk-card-media-top image-div">
										<a href="">
											<img src="{{ asset('uploads/images/products/'.$productoPC->cover) }}" class="content-image"> 
										</a>
							            <div class="image-category-div">{{ $productoPC->category->title }}</div>
							        </div>
							        <div class="uk-card-body card-body" style="padding-top: 2%;">
										<a href="">
										    <div style="min-height: 100px;">
										        <div class="course-title color-ligth2" id="product-pc-name-{{$productoPC->id}}">{{ $productoPC->name }}</div>
												<div class="course-instructor color-ligth2" id="product-pc-owner-{{$productoPC->id}}">{{ $productoPC->user->names.' '.$productoPC->user->last_names }}</div>
										    </div>                    
								        </a>    
							            <div class="uk-text-center" style="padding-top: 15px;">
								            <div class="uk-child-width-1-1" uk-grid>
									            <div>
													<a class="link-course" href=""> <span class="btn-show"><i class="fa fa-plus"></i>  Más Información</span></a>
									            </div>
												<div style="margin-top: 10px;">
													<a class="link-course" href="{{ route('landing.shopping-cart.store', [$productoPC->id, 'producto']) }}"> <span class="btn-course"><i class="fas fa-cart-plus"></i>  Comprar T-Producto</span></a>
												</div>
								            </div>
							            </div>
							        </div>
							    </div>
							</div>
			   			@endforeach
			   		</div>	
		   		@endif
	   		</div>
		</div>
   	</div>
@endsection