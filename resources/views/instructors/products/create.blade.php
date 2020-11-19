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
			<form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.products.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
		        <div class="uk-card-header uk-text-center">
		            <h4>Nuevo Producto</h4>
		        </div> 

		        <div class="uk-card-body">
				    <div class="uk-grid">
					    <div class="uk-width-1-1">
					    	<label class="uk-form-label" for="title"><b>Nombre:</b></label>
						    <input class="uk-input" name="name" type="text" value="{{ old('name') }}" placeholder="Nombre del Producto" required> 
					    </div> 

						<div class="uk-width-1-3">
							<label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
							<select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();" required>
							    <option value="" selected disabled>Seleccione una opción...</option>
							    @foreach ($categorias as $categoria)
							        <option value="{{ $categoria->id }}">{{ $categoria->title }}</option>
							    @endforeach
							</select>
						</div>

						<div class="uk-width-1-3">
							<label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
							<select class="uk-select" id="subcategory_id" name="subcategory_id" required>
							    <option value="" selected disabled>Seleccione una opción...</option>
							</select>
						</div>

						<div class="uk-width-1-3">
						    <label class="uk-form-label" for="price"><b>Precio:</b></label>
							<input class="uk-input" id="price" name="price" type="number" required> 
						</div>

						<div class="uk-width-1-2">
						    <label class="uk-form-label" for="price"><b>Imagen Referencial:</b></label>
							<input class="uk-input" id="cover" name="cover" type="file" required> 
						</div>

						<div class="uk-width-1-2">
						    <label class="uk-form-label" for="price"><b>Archivo:</b></label>
							<input class="uk-input" id="file" name="file" type="file" required> 
						</div>

						<label class="uk-form-label" for="description"><b>Descripción:</b></label><br>
						<div class="uk-margin uk-width-1-1">
							<textarea class="ckeditor" name="description" id="description" rows="5">{{ old('description') }}</textarea>
						</div>
		        	</div>
		        </div>	

		        <div class="uk-card-footer uk-text-right">
		        	<button type="submit" class="uk-button uk-button-danger">Crear Producto</button>
		        </div>
	        </form>
	    </div>
	</div>
@endsection