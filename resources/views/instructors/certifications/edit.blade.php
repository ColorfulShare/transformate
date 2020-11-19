@extends('layouts.instructor')

@push('scripts')
	<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
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

		function notificar($tipo_actualizacion){
			if ($tipo_actualizacion == 'cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i> La portada de la certificación ha sido subida con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'preview'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El video resumen de la certificación ha sido subido con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'preview_cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i>La portada del video resumen ha sido subida con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i>La portada de la certificación ha sido eliminada con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_preview'){
				UIkit.notification({message:'<i class="fa fa-check"></i>El video resumen de la certificación ha sido eliminado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_preview_cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i>La portada del video resumen ha sido eliminada con éxito.', status: 'success'});
			}
		}

		function updateFiles($certificacion){
			var route = "https://transformatepro.com/ajax/multimedias-por-contenido/certificacion/"+$certificacion;
           // var route = "http://localhost:8000/ajax/multimedias-por-contenido/certificacion/"+$certificacion;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#content").html(ans);
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
	            var route = "https://transformatepro.com/instructors/t-mentorings/update";
            	//var route = "http://localhost:8000/instructors/t-mentorings/update";
            	var element = $(this).attr('id');
            	if (element == 'cover'){
            		var formData = new FormData(document.getElementById("form_cover"));
            	}else if (element == 'preview'){
            		var formData = new FormData(document.getElementById("form_preview"));
            	}else{
            		var formData = new FormData(document.getElementById("form_preview_cover"));
            	}

            	document.getElementById("cover").disabled = true;
            	document.getElementById("preview").disabled = true;
            	document.getElementById("preview_cover").disabled = true;
            	document.getElementById("btn-submit").disabled = true;
	            $.ajax({
	                xhr: function () {
	                    var xhr = new window.XMLHttpRequest();
	                    /*xhr.upload.addEventListener("progress", doProgress, false);
	                    xhr.addEventListener("progress", doProgress, false);*/
	                    xhr.upload.addEventListener("progress", function(evt){
	                    	if (evt.lengthComputable) {
				            	document.getElementById('progressbar').style.display = 'block';
				                var percentComplete = Math.round((evt.loaded / evt.total)*100);
				                var bar = document.getElementById('js-progressbar');
				                bar.value = percentComplete;
				                document.getElementById('uploadPercentage').innerHTML = percentComplete+' %';
				                if(percentComplete === 100){
				                	document.getElementById('progressbar').style.display = 'none';
				                	document.getElementById('loader').style.display = 'block';
				                }
				            }
	                    });
	                    return xhr;
	                },
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
	            	document.getElementById('progressbar').style.display = 'none';
	            	document.getElementById('loader').style.display = 'none';
	                notificar('cover');
	                updateFiles(document.getElementById("certification_id").value);
	                document.getElementById("cover").disabled = false;
            		document.getElementById("preview").disabled = false;
            		document.getElementById("preview_cover").disabled = false;
            		document.getElementById("btn-submit").disabled = false;
	            });
	        });

	        $('.delete-input').on('click', function(e) {
	            e.preventDefault();
	            var element = $(this).attr('id');
	            if (element == 'delete_cover'){
	            	document.getElementById("file_type").value = 'cover';
            	}else if (element == 'delete_preview'){
            		document.getElementById("file_type").value = 'preview';
            	}else{
            		document.getElementById("file_type").value = 'preview_cover';
            	}

            	var route = "https://transformatepro.com/instructors/t-mentorings/update";
            	//var route = "http://localhost:8000/instructors/t-mentorings/update";
            	var formData = new FormData(document.getElementById("form_delete"));
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
	            	updateFiles(document.getElementById("certification_id").value);
		            notificar(element);
	            });
	        });
		});
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

		@if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

		<div class="uk-card-default">
		    <div class="uk-card-header uk-text-center">
		        <h4>Editar Certificación</h4>
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
				    <li>
				        <a href="#"> Recursos Multimedia</a>
				    </li>              
				</ul>
				

				<ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto uk-margin-top">
					<li class="uk-active">
						<form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.certifications.update') }}" method="POST" enctype="multipart/form-data" id="form_content">
							@csrf
							<input type="hidden" name="certification_id" id="certification_id" value="{{ $certificacion->id }}">
					    	<!--Datos Básicos -->
					    	<br>
					    	<div class="uk-grid">
					    		<div class="uk-width-1-1">
					    			<label class="uk-form-label" for="title"><b>Título:</b></label>
						            <input class="uk-input" id="title" name="title" type="text" placeholder="Título del Curso" value="{{ $certificacion->title }}"> 
					   			</div> 

					   			<div class="uk-width-1-1">
						        	<label class="uk-form-label" for="subtitle"><b>Subtítulo:</b></label>
						        	<input class="uk-input" id="subtitle" name="subtitle" type="text" placeholder="Subtítulo del Curso" maxlength="100" value="{{ $certificacion->subtitle }}"> 
						    	</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
							        <select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();">
							            <option value="" selected disabled>Seleccione una opción...</option>
							            @foreach ($categorias as $categoria)
							                <option value="{{ $categoria->id }}" @if ($certificacion->category_id == $categoria->id) selected @endif>{{ $categoria->title }}</option>
							            @endforeach
							        </select>
								</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
							        <select class="uk-select" id="subcategory_id" name="subcategory_id">
							            <option value="" selected disabled>Seleccione una opción...</option>
							            @foreach ($subcategorias as $subcategoria)
							                <option value="{{ $subcategoria->id }}" @if ($certificacion->subcategory_id == $subcategoria->id) selected @endif>
								                    	{{ $subcategoria->title }}
							                </option>
								        @endforeach
								    </select>
								</div>

							    <div class="uk-width-1-2">
						        	<label class="uk-form-label" for="mentor_route_time"><b>Ruta del Mentor:</b></label>
						            <select class="uk-select" name="mentor_route_time" id="mentor_route_time">
						                <option value="">Seleccione una opción...</option>
						                <option value="0-3 Horas" @if ($certificacion->mentor_route_time == '0-3 Horas') selected @endif>Dedicaré un poco de mi tiempo en la Ruta del Mentor (0-3 horas)</option>
							            <option value="3-6 Horas" @if ($certificacion->mentor_route_time == '3-6 Horas') selected @endif>Trabajaré en mis tiempos libres en la Ruta del Mentor (3-6 Horas)</option>
							            <option value="+6 Horas" @if ($certificacion->mentor_route_time == '+6 Horas') selected @endif>Estoy enfocado en sacar lo más pronto posible la Ruta del Mentor (Más de 6 horas)</option>
							            <option value="+8 Horas" @if ($certificacion->mentor_route_time == '+8 Horas') selected @endif>Cumpliré mi objetivo en la Ruta del Mentor en el menor tiempo posible (Más de 8 horas)</option>
							        </select>
						    	</div> 

						    	<div class="uk-width-1-2">
						        	<label class="uk-form-label" for="price"><b>Precio:</b></label>
							        <input class="uk-input" id="price" name="price" type="number" value="{{ $certificacion->price }}"> 
								</div>

								<div class="uk-width-1-1">
									<label class="uk-form-label" for="price"><b>Etiquetas:</b></label><br>
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
					</li>
							
					<!--Datos Informativos -->
				    <li>
			    			<br>
				    		<label class="uk-form-label" for="objectives"><b>Objetivos <i class="far fa-question-circle" uk-tooltip="¿Qué aprenderá el alumno?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="objectives" id="objectives" rows="5">
							    	{{ $certificacion->objectives }}
							    </textarea>
							</div>
							
							<label class="uk-form-label" for="requirements"><b>Requisitos Previos</b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="requirements" id="requirements" rows="5" placeholder="Ingrese los requisitos previos del curso">
							    	{{ $certificacion->requirements }}
							    </textarea>
							</div>

							<label class="uk-form-label" for="destination"><b>Destino <i class="far fa-question-circle" uk-tooltip="¿A quién va dirigido el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="destination" id="destination" rows="5">
							    	{{ $certificacion->destination }}
							    </textarea>
							</div>
				    </li>

					<!--Datos de Contenido -->
				    <li>
				    		<br>
				    		<label class="uk-form-label" for="review"><b>Reseña</b></label><br>
				    		<div class="uk-margin">
							    <textarea class="ckeditor" name="review" id="review" rows="5" maxlength="150">
							    	{{ $certificacion->review }}
							    </textarea>
							</div>

							<label class="uk-form-label" for="description"><b>Descripción</b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="description" id="description" rows="5">
							    	{{ $certificacion->description }}
							    </textarea>
							</div> 

							<label class="uk-form-label" for="material_content"><b>Material <i class="far fa-question-circle" uk-tooltip="¿Qué incluye el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="material_content" id="material_content" rows="5">
							    	{{ $certificacion->material_content }}
							    </textarea>
							</div>
				    </li>
				    </form>

				    <!-- Recursos Multimedia -->
				    <li>
				    	<br>
				    	<div class="uk-grid">
							<div class="uk-width-1-3">
							    <label class="uk-form-label" for="cover"><b>Portada de la Certificación:</b></label>
							    <form id="form_cover">
							    	<input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
							        <input class="uk-input upload-input" type="file" name="cover" id="cover">
							    </form>
							</div>
								
							<div class="uk-width-1-3">
						        <label class="uk-form-label uk-text-bold" for="preview">Video Resumen:</label>
						        <form id="form_preview">
						        	<input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
						        	<input class="uk-input upload-input" type="file" name="preview" id="preview">
						        </form>
							</div>

							<div class="uk-width-1-3">
						        <label class="uk-form-label uk-text-bold" for="preview">Cover del Video Resumen:</label>
						        <form id="form_preview_cover">
							        <input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
							        <input class="uk-input upload-input" type="file" name="preview_cover" id="preview_cover">
							    </form>
							</div>
						</div>

						<div id="progressbar" class="uk-text-center" style="display: none;">
							<br>
							<progress id="js-progressbar" class="uk-progress" value="0" max="100"></progress>
							<label class="uk-label uk-label-warning" id="uploadPercentage">0%</label>
						</div>
						<div id="loader" class="uk-text-center" style="display: none;">
							<br>
							<label class="uk-label uk-label-success uk-form-width-large">Archivo Cargado (100%)</label><br><br>
							<span uk-spinner="ratio: 1.5"></span><br>
							Por favor espere mientras se termina de configurar el archivo...
							<hr>
						</div>

						<caption><h6>Recursos Multimedias de la Certificación</h6></caption>
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
									@if (!is_null($certificacion->cover))
										<tr>
											<td class="uk-text-center uk-table-shrink">{{ $certificacion->cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_cover"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($certificacion->preview))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $certificacion->preview_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-video img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Video Resumen</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ $certificacion->preview }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($certificacion->preview_cover))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $certificacion->preview_cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada del Video Resumen</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/certifications/preview_covers/'.$certificacion->preview_cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview_cover"></a>
											</td>
										</tr>
									@endif
								</tbody>
							</table>

							<form id="form_delete">
								<input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
								<input type="hidden" name="file_type" id="file_type">
							</form>
						</div>
				    </li>
				</ul>
		    </div>	

		    <div class="uk-card-footer uk-text-right">
		        <button type="submit" class="uk-button uk-button-danger" id="btn-submit">Guardar Cambios</button>
		    </div>
	    </div>
	</div>
@endsection