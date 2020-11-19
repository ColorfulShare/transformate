@extends('layouts.admin')

@push('scripts')
	<script src="{{ asset('vendors/ckeditor/ckeditor.js') }}"></script>
	<script>
		function cargarSubcategorias(){
			var categoria = document.getElementById("category_id").value;
			var route = "https://transformatepro.com/ajax/cargar-subcategorias/"+categoria;
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
	<div class="admin-content-inner"> 
		@if (Session::has('msj-exitoso'))
	        <div class="uk-alert-success" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-exitoso') }}</strong>
	        </div>
	    @endif

        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Detalles de la Certifiación
            </div>   
            <form action="{{ route('admins.certifications.update') }}" method="POST">
            	@csrf
            	<input type="hidden" name="certification_id" value="{{ $certificacion->id }}">           
		        <div class="uk-card-body"> 
		            <div class="uk-grid">
		            	<div class="uk-width-1-1 uk-text-center">
		            		<img src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" style="width: 400px;">
		            	</div>
	                    <div class="uk-width-1-1">
	                    	Título: 
	                    	<input class="uk-input" type="text" name="title" value="{{ $certificacion->title }}" required>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Subtítulo: 
	                    	<input class="uk-input" type="text" name="subtitle" value="{{ $certificacion->subtitle }}" maxlength="100" required>
	                    </div>
	                    <div class="uk-width-1-2">
	                    	Categoría: 
	                    	<select class="uk-select" name="category_id" id="category_id" onchange="cargarSubcategorias();" required>
	                    		@foreach ($categorias as $categoria)
	                                <option value="{{ $categoria->id }}" @if ($categoria->id == $certificacion->category_id) selected @endif>{{ $categoria->title }}</option>
	                            @endforeach
	                        </select>
	                    </div>
	                     <div class="uk-width-1-2">
	                    	Subcategoría: 
	                    	<select class="uk-select" name="subcategory_id" id="subcategory_id" required>
	                    		@foreach ($subcategorias as $subcategoria)
	                                <option value="{{ $subcategoria->id }}" @if ($subcategoria->id == $certificacion->subcategory_id) selected @endif>{{ $subcategoria->title }}</option>
	                            @endforeach
	                        </select>
	                    </div>
	                    <div class="uk-width-1-3">
	                    	Instructor: 
	                    	<input class="uk-input" type="text" value="{{ $certificacion->user->names }} {{ $certificacion->user->last_names }}" disabled>
	                    </div>
	                    <div class="uk-width-1-3">
	                    	Ruta del Instructor: 
	                    	<select class="uk-select" name="mentor_route_time" required>
							    <option value="0-3 Horas" @if ($certificacion->mentor_route_time == '0-3 Horas') selected @endif>0-3 horas</option>
							    <option value="3-6 Horas" @if ($certificacion->mentor_route_time == '3-6 Horas') selected @endif>3-6 Horas</option>
							    <option value="+6 Horas" @if ($certificacion->mentor_route_time == '+6 Horas') selected @endif>+6 Horas</option>
							    <option value="+8 Horas" @if ($certificacion->mentor_route_time == '+8 Horas') selected @endif>+8 Horas</option>
							</select>
	                    </div> 
	                    <div class="uk-width-1-3">
	                    	Precio (COP):
	                    	<input class="uk-input" name="price" type="number" value="{{ $certificacion->price }}" required> 
	                    </div>     
	                    <div class="uk-width-1-1">
	                    	Reseña:
	                    	<textarea class="ckeditor" name="review" rows="5" placeholder="Reseña del Curso" required>{{ $certificacion->review }}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Descripción:
	                    	<textarea class="ckeditor" name="description" rows="5" placeholder="Descripción del Curso" required>{{ $certificacion->description}}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Objetivos:
	                    	<textarea class="ckeditor" name="objectives" rows="5" placeholder="Objetivos del Curso" required>{{ $certificacion->objectives }}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Requisitos Previos:
	                    	<textarea class="ckeditor" name="requirements" rows="5" placeholder="Requisitos Previos del Curso" required>{{ $certificacion->requirements }}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Destino:
	                    	<textarea class="ckeditor" name="destination" rows="5" placeholder="A quién va diridigo el curso" required>{{ $certificacion->destination }}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
	                    	Material Incluido:
	                    	<textarea class="ckeditor" name="material_content" rows="5" placeholder="Material incluido en el curso" required>{{ $certificacion->material_content }}</textarea>
	                    </div>
	                    <div class="uk-width-1-1">
							Etiquetas:
						</div>
						<div class="uk-width-1-1">
						    <div class="uk-child-width-1-4@m uk-grid">
								@foreach ($etiquetas as $etiqueta)
								    @php
								    	$check = 0;
								    	if (in_array($etiqueta->id, $etiquetasActivas)){
								    		$check = 1;
									    }
									@endphp
									<label><input class="uk-checkbox" type="checkbox" value="{{ $etiqueta->id }}" name="tags[]" @if ($check == 1) checked @endif> {{ $etiqueta->tag }}</label>
								@endforeach
							</div> 
						</div>
	                </div>                 
		        </div>  
		        <div class="uk-card-footer uk-text-right">
		        	@if (Auth::user()->profile->certifications == 2)
		        		<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios">
		        	@else
		        		<input class="uk-button uk-button-default uk-margin" value="Guardar Cambios" disabled>
		        	@endif
		        </div> 
		    </form>           
        </div>                                 
    </div>
@endsection