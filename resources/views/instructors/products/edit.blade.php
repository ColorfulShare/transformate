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

		$(function(){
			$('#btn-submit').on('click', function(){
				$("#form_content" ).submit();
			});

			//Capturar cuando se suba un nuevo archivo
	        $('.upload-input').on('change', function(e) {
	            e.preventDefault();
	            var url = {{ $www }};
            	if (url == 1){
	            	var route = "https://www.transformatepro.com/instructors/products/update";
	            }else{
	            	var route = "https://transformatepro.com/instructors/products/update";
	            }
            	//var route = "http://localhost:8000/instructors/products/update";
            	var element = $(this).attr('id');
            	if (element == 'cover'){
            		var formData = new FormData(document.getElementById("form_cover"));
            	}else if (element == 'file'){
            		var formData = new FormData(document.getElementById("form_file"));
            	}

            	document.getElementById("cover").disabled = true;
            	document.getElementById("file").disabled = true;
            	document.getElementById("btn-submit").disabled = true;
	            $.ajax({
	                headers: {
	                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
	                },
	                url: route,
	                type: "post",
	                dataType: "html",
	                data: formData,
	                cache: false,
	                contentType: false,
	                processData: false
	            }).done(function(res){
	                notificar(element);
	                updateFiles(document.getElementById("product_id").value);
	                document.getElementById("cover").disabled = false;
            		document.getElementById("file").disabled = false;
            		document.getElementById("btn-submit").disabled = false;
	            });
	        });
		});

		function notificar($tipo_actualizacion){
			if ($tipo_actualizacion == 'cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i> La portada del producto ha sido modificada con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'file'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El archivo del producto ha sido modificado con éxito.', status: 'success'});
			}
		}

		function updateFiles($producto){
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/ajax/multimedias-por-producto/"+$producto;
			}else{
				var route = "https://transformatepro.com/ajax/multimedias-por-producto/"+$producto;
			}
            //var route = "http://localhost:8000/ajax/multimedias-por-producto/"+$producto;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#content").html(ans);
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
		    <div class="uk-card-header uk-text-center">
		        <h4>Nuevo Producto</h4>
		    </div> 

		    <div class="uk-card-body">
		    	<form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.products.update') }}" method="POST" enctype="multipart/form-data" id="form_content">
					@csrf
					<input type="hidden" name="product_id" id="product_id" value="{{ $producto->id }}">
					<div class="uk-grid">
						<div class="uk-width-1-1">
						    <label class="uk-form-label" for="title"><b>Nombre:</b></label>
							<input class="uk-input" name="name" type="text" value="{{ $producto->name }}" required> 
						</div> 

						<div class="uk-width-1-3">
							<label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
							<select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();" required>
								<option value="" selected disabled>Seleccione una opción...</option>
								@foreach ($categorias as $categoria)
								    <option value="{{ $categoria->id }}" @if ($producto->category_id == $categoria->id) selected @endif>{{ $categoria->title }}</option>
								@endforeach
							</select>
						</div>

						<div class="uk-width-1-3">
							<label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
							<select class="uk-select" id="subcategory_id" name="subcategory_id" required>
								<option value="" selected disabled>Seleccione una opción...</option>
								@foreach ($subcategorias as $subcategoria)
								    <option value="{{ $subcategoria->id }}" @if ($producto->subcategory_id == $subcategoria->id) selected @endif>
									    {{ $subcategoria->title }}
								    </option>
								@endforeach
							</select>
						</div>

						<div class="uk-width-1-3">
							<label class="uk-form-label" for="price"><b>Precio:</b></label>
							<input class="uk-input" id="price" name="price" type="number" value="{{ $producto->price }}" required> 
						</div>

						<label class="uk-form-label" for="description"><b>Descripción:</b></label><br>
						<div class="uk-margin uk-width-1-1">
							<textarea class="ckeditor" name="description" id="description" rows="5">{{ $producto->description }}</textarea>
						</div>
					</form>

					<div class="uk-width-1-2">
						<label class="uk-form-label" for="price"><b>Imagen Referencial:</b></label>
						<form id="form_cover">
							<input type="hidden" name="product_id" value="{{ $producto->id }}">
							<input class="uk-input upload-input" type="file" name="cover" id="cover">
						</form>
					</div>

					<div class="uk-width-1-2">
						<label class="uk-form-label" for="price"><b>Archivo:</b></label>
						<form id="form_file">
							<input type="hidden" name="product_id" value="{{ $producto->id }}">
							<input class="uk-input upload-input" type="file" name="file" id="file">
						</form> 
					</div>
				</div>

		        <caption><h6>Recursos Multimedias del Producto</h6></caption>
				<div class="uk-overflow-auto">
					<table class="uk-table uk-table-middle uk-table-responsive">
						<thead style="background-color: #D8D7D7;">
							<tr>
								<th class="uk-text-center uk-width-medium">Nombre</th>
								<th class="uk-text-center uk-width-small">Thumbnail</th>
								<th class="uk-text-center uk-width-medium">Tipo</th>
								<th class="uk-text-center uk-width-auto" colspan="2">Acción</th>
							</tr>
						</thead>
						<tbody id="content">
							@if (!is_null($producto->cover))
								<tr>
									<td class="uk-text-center uk-table-shrink">{{ $producto->cover_name }}</td>
									<td class="uk-text-center uk-table-shrink">
										<i class="fa fa-file-image img-icon"></i>
									</td>
									<td class="uk-text-center uk-table-shrink">Portada</td>
									<td class="uk-text-center">
										<div uk-lightbox>
											<a href="{{ asset('uploads/images/products/'.$producto->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
										</div>
									</td>
								</tr>
							@endif
							@if (!is_null($producto->file))
								<tr>
									<td class="uk-text-center uk-table-expand">{{ $producto->filename }}</td>
									<td class="uk-text-center uk-table-shrink">
										<i class="{{ $producto->file_icon }} img-icon"></i>
									</td>
									<td class="uk-text-center uk-table-shrink">Archivo del Producto</td>
									<td class="uk-text-center">
										<a href="{{ asset('uploads/products/'.$producto->file) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
									</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
		    </div>	

		    <div class="uk-card-footer uk-text-right">
		       	<button type="submit" class="uk-button uk-button-danger" id="btn-submit">Guardar Cambios</button>
		    </div>
	    </div>
	</div>
@endsection