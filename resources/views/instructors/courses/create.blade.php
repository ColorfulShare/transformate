@extends('layouts.instructor')

@push('scripts')
	<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
	<script>
		function cargarSubcategorias(){
			var categoria = document.getElementById("category_id").value;
			var route = "https://www.transformatepro.com/ajax/cargar-subcategorias/"+categoria;
            //var route = "http://localhost:8000/ajax/cargar-subcategorias/"+categoria;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    document.getElementById("subcategory_id").innerHTML = "";
                    document.getElementById("subcategory_id").innerHTML  += '<option value="" selected disabled>Seleccione una subcategoría</option>';
                    for (var i = 0; i < ans.length; i++){
                        document.getElementById("subcategory_id").innerHTML += '<option value="'+ans[i].id+'">'+ans[i].title+'</option>';
                    }
                }
            });
		}
	</script>
@endpush

@section('content')
	<div class="uk-container">
		@if ($errors->any())
	        <div class="uk-alert-danger" uk-alert>
	            <ul class="uk-list uk-list-divider uk-list-bullet">
	                @foreach ($errors->all() as $error)
	                    <li>{{ $error }}</li>
	                @endforeach
	            </ul>
	            
	        </div>
	    @endif

		<div class="uk-card-default">
			<form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.courses.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
		        <div class="uk-card-header uk-text-center">
		            <h4>Crear Curso</h4>
		        </div> 

		        <div class="uk-card-body">
		        	<ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
			        	<li aria-expanded="true" class="uk-active">
				            <a href="#"> Datos Básicos</a>
				        </li>                 
				        <li>
				            <a href="#"> Datos Informativos</a>
				        </li>                 
				        <li>
				            <a href="#"> Datos de Contenido</a>
				        </li>              
				    </ul>

				    <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto uk-margin-top">
				    	<!--Datos Básicos -->
				    	<li class="uk-active">
				    		<br>
				    		<div class="uk-grid">
					    		<div class="uk-width-1-1">
					    			<label class="uk-form-label" for="title"><b>Título:</b></label>
						            <input class="uk-input" id="title" name="title" type="text" value="{{ old('title') }}" placeholder="Título del Curso"> 
					   			</div> 

					   			<div class="uk-width-1-1">
						        	<label class="uk-form-label" for="subtitle"><b>Subtítulo:</b></label>
						        	<input class="uk-input" id="subtitle" name="subtitle" type="text" value="{{ old('subtitle') }}" placeholder="Subtítulo del Curso" maxlength="100"> 
						    	</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
							        <select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();">
							            <option value="" selected disabled>Seleccione una opción...</option>
							            @foreach ($categorias as $categoria)
							                <option value="{{ $categoria->id }}">{{ $categoria->title }}</option>
							            @endforeach
							        </select>
								</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
							        <select class="uk-select" id="subcategory_id" name="subcategory_id">
							            <option value="" selected disabled>Seleccione una opción...</option>
								    </select>
								</div>

							    <div class="uk-width-1-2">
						        	<label class="uk-form-label" for="mentor_route_time"><b>Ruta del Mentor:</b></label>
						            <select class="uk-select" name="mentor_route_time" id="mentor_route_time">
						                <option value="">Seleccione una opción...</option>
						                <option value="0-3 Horas">Dedicaré un poco de mi tiempo en la Ruta del Mentor (0-3 horas)</option>
							            <option value="3-6 Horas">Trabajaré en mis tiempos libres en la Ruta del Mentor (3-6 Horas)</option>
							            <option value="+6 Horas">Estoy enfocado en sacar lo más pronto posible la Ruta del Mentor (Más de 6 horas)</option>
							            <option value="+8 Horas">Cumpliré mi objetivo en la Ruta del Mentor en el menor tiempo posible (Más de 8 horas)</option>
							        </select>
						    	</div> 

						    	<div class="uk-width-1-2">
						        	<label class="uk-form-label" for="price"><b>Precio:</b></label>
							        <input class="uk-input" id="price" name="price" type="number"> 
								</div>

								<div class="uk-width-1-1">
									<label class="uk-form-label" for="price"><b>Etiquetas:</b></label><br>
								</div>
							    <div class="uk-width-1-1">
						        	<div class="uk-child-width-1-4@m uk-grid">
								    	@foreach ($etiquetas as $etiqueta)
									       	<label><input class="uk-checkbox" type="checkbox" value="{{ $etiqueta->id }}" name="tags[]"> {{ $etiqueta->tag }}</label>
									    @endforeach
								    </div> 
								</div>
					    	</div>
				    	</li>
						
						<!--Datos Informativos -->
				    	<li>
				    		<br>
				    		<label class="uk-form-label" for="objectives"><b>Objetivos <i class="far fa-question-circle" uk-tooltip="¿Qué aprenderá el alumno?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="objectives" id="objectives" rows="5">{{ old('objectives') }}</textarea>
							</div>
							
							<label class="uk-form-label" for="requirements"><b>Requisitos Previos</b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="requirements" id="requirements" rows="5">{{ old('requirements') }}</textarea>
							</div>
							
							<label class="uk-form-label" for="destination"><b>Destino <i class="far fa-question-circle" uk-tooltip="¿A quién va dirigido el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="destination" id="destination" rows="5">{{ old('destination') }}</textarea>
							</div>
				    	</li>

						<!--Datos de Contenido -->
				    	<li>
				    		<br>
				    		<label class="uk-form-label" for="review"><b>Reseña</b></label><br>
				    		<div class="uk-margin">
							    <textarea class="ckeditor" name="review" id="review" rows="5">{{ old('review') }}</textarea>
							</div>
							
							<label class="uk-form-label" for="description"><b>Descripción</b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="description" id="description" rows="5">{{ old('description') }}</textarea>
							</div>
							
							<label class="uk-form-label" for="material_content"><b>Material <i class="far fa-question-circle" uk-tooltip="¿Qué incluye el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="material_content" id="material_content" rows="5">{{ old('material_content') }}</textarea>
							</div>
				    	</li>
				    </ul>
		        </div>	

		        <div class="uk-card-footer uk-text-right">
		        	<button type="submit" class="uk-button uk-button-danger">Crear Curso</button>
		        </div>
	        </form>
	    </div>
	</div>
@endsection