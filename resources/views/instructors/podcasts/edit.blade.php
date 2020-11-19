@extends('layouts.instructor')

@push('scripts')
	<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.24.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/node-uuid/1.4.7/uuid.min.js"></script>

	<script>
		function notificar($tipo_actualizacion){
			if ($tipo_actualizacion == 'cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i> La portada ha sido subida con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'preview'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El audio resumen ha sido subido con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'audio_file'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El audio ha sido subido con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i>La portada ha sido eliminada con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_preview'){
				UIkit.notification({message:'<i class="fa fa-check"></i>El audio resumen ha sido eliminado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_audiofile'){
				UIkit.notification({message:'<i class="fa fa-check"></i>El audio ha sido eliminado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'new_resource'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El recurso descargable ha sido cargado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_resource'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El recurso descargable ha sido eliminado con éxito.', status: 'success'});
			}
		}

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

		function updateFiles($podcast){
			var route = "https://transformatepro.com/ajax/multimedias-por-contenido/podcast/"+$podcast;
            //var route = "http://localhost:8000/ajax/multimedias-por-contenido/podcast/"+$podcast;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#content").html(ans);
                }
            });
		}

		function updateResources($podcast){
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/instructors/t-books/resources/"+$podcast;
			}else{
				var route = "https://transformatepro.com/instructors/t-books/resources/"+$podcast;
			}
            //var route = "http://localhost:8000/instructors/t-books/resources/"+$podcast;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#resources").html(ans);
                }
            });
		}

		$(function(){
			//Obtener Credenciales
			var parametros = {"c1" : $("#accessKeyId").val(),"c2" : $("#secretAccessKey").val()};
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/ajax/load";
			}else{
				var route = "https://transformatepro.com/ajax/load";
			}

			//var route = "http://localhost:8000/ajax/load";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                },
                url: route,
                type:'POST',
                data:  parametros,
                success:function(ans){
                    AWS.config.update({
                        accessKeyId : ans.c1,
                        secretAccessKey : ans.c2
                    });
                    AWS.config.region = ans.c3;
                }
            });

			$('#btn-submit').on('click', function(){
				$("#form_content" ).submit();
			});

			//Capturar cuando se suba un nuevo archivo multimedia
	        $('.upload-input').on('change', function(e) {
	            e.preventDefault();
	            var route = "https://transformatepro.com/instructors/t-books/update";
            	//var route = "http://localhost:8000/instructors/t-books/update";
            	var element = $(this).attr('id');
            	if (element == 'cover'){
            		var formData = new FormData(document.getElementById("form_cover"));
            	}else if (element == 'preview'){
            		var formData = new FormData(document.getElementById("form_preview"));
            	}else if (element == 'audio_file'){
            		var formData = new FormData(document.getElementById("form_audiofile"));
            	}

            	document.getElementById("cover").disabled = true;
            	document.getElementById("preview").disabled = true;
            	document.getElementById("audio_file").disabled = true;
            	document.getElementById("btn-submit").disabled = true;
	            $.ajax({
	                xhr: function () {
	                    var xhr = new window.XMLHttpRequest();
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
	                notificar(element);
	                updateFiles(document.getElementById("podcast_id").value);
	                document.getElementById("cover").disabled = false;
            		document.getElementById("preview").disabled = false;
            		document.getElementById("audio_file").disabled = false;
            		document.getElementById("btn-submit").disabled = false;
	            });
	        });

	        //Capturar cuando se elimine un archivo multimedia
	        $('.delete-input').on('click', function(e) {
	            e.preventDefault();
	            var element = $(this).attr('id');
	            if (element == 'delete_cover'){
	            	document.getElementById("file_type").value = 'cover';
            	}else if (element == 'delete_preview'){
            		document.getElementById("file_type").value = 'preview';
            	}else if (element == 'delete_audiofile'){
            		document.getElementById("file_type").value = 'audio_file';
            	}

            	var route = "https://transformatepro.com/instructors/t-books/update";
            	//var route = "http://localhost:8000/instructors/t-books/update";
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
	            	updateFiles(document.getElementById("podcast_id").value);
		            notificar(element);
	            });
	        });

	        //Capturar cuando se suba un archivo descargable
	        $('.resource-file-submit').on('change', function(e) {
	            e.preventDefault();
            	var podcast_id = document.getElementById("podcast_id").value;

            	document.getElementById("resource").disabled = true;

            	var bucket = new AWS.S3({params: {Bucket: 'transformate-videos'}});
                var uploadFiles = $('#resource')[0];
                var upFile = uploadFiles.files[0];
                if (upFile) {
                	var nombre = upFile.name;
                    var id = uuid.v1();
                    var ext = upFile.name.split(".");
                    var extension = ext[1];
                    var path =  'podcasts/'+podcast_id+'/resources/'+id+'.'+extension;
                    var uploadParams = {Key: path, Body: upFile};

                    bucket.upload(uploadParams).on('httpUploadProgress', function(progress) {
                    	document.getElementById('progressbar2').style.display = 'block';
                        var uploaded = parseInt((progress.loaded * 100) / progress.total);
                        var bar = document.getElementById('js-progressbar2');
                        bar.value = uploaded;
                        document.getElementById('uploadPercentage2').innerHTML = uploaded+' %';
                        if(uploaded === 100){
				            document.getElementById('progressbar2').style.display = 'none';
				            document.getElementById('loader2').style.display = 'block';
				        }
                    }).send(function(err, data) {
                    	var url = {{ $www }};
		            	if (url == 1){
		            		var route = "https://www.transformatepro.com/instructors/t-books/update-temary";
		            	}else{
		            		var route = "https://transformatepro.com/instructors/t-books/update-temary";
		            	}

                        //var route = "http://localhost:8000/instructors/t-books/load-resource";
                        var parametros = {"podcast": podcast_id, "nombre_archivo" : nombre, "extension" : extension, "direccion" : path};
                        
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                            },
                            url:route,
                            type:'POST',
                            data:  parametros,
                            success:function(ans){
                                if (ans == true){
                                    document.getElementById('progressbar2').style.display = 'none';
					            	document.getElementById('loader2').style.display = 'none';
					                notificar('new_resource');
					                updateResources(podcast_id);
            						document.getElementById("resource").disabled = false;
                                }                        
                            }
                        });
                    });
                }

                return false;
	        });

	        //Capturar cuando se elimine un archivo descargable
	        $('.delete-input').on('click', function(e) {
	            e.preventDefault();

	            document.getElementById("resource_id").value = $(this).attr('id');

            	var url = {{ $www }};
		        if (url == 1){
		          	var route = "https://www.transformatepro.com/instructors/t-books/delete-resource";
		        }else{
		            var route = "https://transformatepro.com/instructors/t-books/delete-resource";
		        }

                //var route = "http://localhost:8000/instructors/t-books/delete-resource";

            	var formData = new FormData(document.getElementById("form_resource_delete"));
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
	            	updateResources(document.getElementById("podcast_id").value);
		            notificar('delete_resource');
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
		        <h4>Editar Podcast</h4>
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
				    <li>
				        <a href="#"> Recursos Descargables</a>
				    </li>              
				</ul>
				

				<ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto uk-margin-top">
					<li class="uk-active">
						<form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.podcasts.update') }}" method="POST" enctype="multipart/form-data" id="form_content">
							@csrf
							<input type="hidden" name="podcast_id" id="podcast_id" value="{{ $podcast->id }}">
					    	<!--Datos Básicos -->
					    	<br>
					    	<div class="uk-grid">
					    		<div class="uk-width-1-1">
					    			<label class="uk-form-label" for="title"><b>Título:</b></label>
						            <input class="uk-input" id="title" name="title" type="text" placeholder="Título del Podcast" value="{{ $podcast->title }}"> 
					   			</div> 

					   			<div class="uk-width-1-1">
						        	<label class="uk-form-label" for="subtitle"><b>Subtítulo:</b></label>
						        	<input class="uk-input" id="subtitle" name="subtitle" type="text" placeholder="Subtítulo del Podcast" maxlength="100" value="{{ $podcast->subtitle }}"> 
						    	</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
							        <select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();">
							            <option value="" selected disabled>Seleccione una opción...</option>
							            @foreach ($categorias as $categoria)
							                <option value="{{ $categoria->id }}" @if ($podcast->category_id == $categoria->id) selected @endif>{{ $categoria->title }}</option>
							            @endforeach
							        </select>
								</div>

							    <div class="uk-width-1-2">
							        <label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
							        <select class="uk-select" id="subcategory_id" name="subcategory_id">
							            <option value="" selected disabled>Seleccione una opción...</option>
							            @foreach ($subcategorias as $subcategoria)
							                <option value="{{ $subcategoria->id }}" @if ($podcast->subcategory_id == $subcategoria->id) selected @endif>
								                    	{{ $subcategoria->title }}
							                </option>
								        @endforeach
								    </select>
								</div>

							    <div class="uk-width-1-2">
						        	<label class="uk-form-label" for="mentor_route_time"><b>Ruta del Mentor:</b></label>
						            <select class="uk-select" name="mentor_route_time" id="mentor_route_time">
						                <option value="">Seleccione una opción...</option>
						                <option value="0-3 Horas" @if ($podcast->mentor_route_time == '0-3 Horas') selected @endif>Dedicaré un poco de mi tiempo en la Ruta del Mentor (0-3 horas)</option>
							            <option value="3-6 Horas" @if ($podcast->mentor_route_time == '3-6 Horas') selected @endif>Trabajaré en mis tiempos libres en la Ruta del Mentor (3-6 Horas)</option>
							            <option value="+6 Horas" @if ($podcast->mentor_route_time == '+6 Horas') selected @endif>Estoy enfocado en sacar lo más pronto posible la Ruta del Mentor (Más de 6 horas)</option>
							            <option value="+8 Horas" @if ($podcast->mentor_route_time == '+8 Horas') selected @endif>Cumpliré mi objetivo en la Ruta del Mentor en el menor tiempo posible (Más de 8 horas)</option>
							        </select>
						    	</div> 

						    	<div class="uk-width-1-2">
						        	<label class="uk-form-label" for="price"><b>Precio:</b></label>
							        <input class="uk-input" id="price" name="price" type="number" value="{{ $podcast->price }}"> 
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
				    	<label class="uk-form-label" for="inspired_in"><b>Inspirado en:</b></label>
						<input class="uk-input" id="inspired_in" name="inspired_in" value="{{ $podcast->inspired_in }}" type="text" placeholder="Inspirado en...">
							
						<label class="uk-form-label" for="objectives"><b>Objetivos <i class="far fa-question-circle" uk-tooltip="¿Qué aprenderá el alumno?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="objectives" id="objectives" rows="5">{{ $podcast->objectives }}</textarea>
							</div>

							<label class="uk-form-label" for="destination"><b>Destino <i class="far fa-question-circle" uk-tooltip="¿A quién va dirigido el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="destination" id="destination" rows="5">{{ $podcast->destination }}</textarea>
							</div>

							<label class="uk-form-label" for="importance"><b>Importancia</b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="importance" id="importance" rows="5">{{ $podcast->importance }}</textarea>
							</div>
				    </li>

					<!--Datos de Contenido -->
				    <li>
				    		<br>
				    		<label class="uk-form-label" for="review"><b>Reseña</b></label><br>
				    		<div class="uk-margin">
							    <textarea class="ckeditor" name="review" id="review" rows="5" maxlength="150">
							    	{{ $podcast->review }}
							    </textarea>
							</div>

							<label class="uk-form-label" for="prologue"><b>Prólogo </b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="prologue" id="prologue" rows="5">{{ $podcast->prologue }}</textarea>
							</div>

							<label class="uk-form-label" for="potential_impact"><b>Impacto Potencial </b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="potential_impact" id="potential_impact" rows="5">{{ $podcast->potential_impact }}</textarea>
							</div>

							<label class="uk-form-label" for="material_content"><b>Material <i class="far fa-question-circle" uk-tooltip="¿Qué incluye el curso?"></i></b></label><br>
							<div class="uk-margin">
							    <textarea class="ckeditor" name="material_content" id="material_content" rows="5">
							    	{{ $podcast->material_content }}
							    </textarea>
							</div>
				    </li>
				    </form>

				    <!-- Recursos Multimedia -->
				    <li>
				    	<br>
				    	<div class="uk-grid">
				    		<div class="uk-width-1-1">
						        <label class="uk-form-label uk-text-bold" for="audio_file">Audio del T-Book:</label>
						        <form id="form_audiofile">
						        	<input type="hidden" name="podcast_id" value="{{ $podcast->id }}">
						        	<input class="uk-input upload-input" type="file" name="audio_file" id="audio_file">
						        </form>
							</div>

							<div class="uk-width-1-2">
							    <label class="uk-form-label" for="cover"><b>Portada del T-Book:</b></label>
							    <form id="form_cover">
							    	<input type="hidden" name="podcast_id" value="{{ $podcast->id }}">
							        <input class="uk-input upload-input" type="file" name="cover" id="cover">
							    </form>
							</div>
								
							<div class="uk-width-1-2">
						        <label class="uk-form-label uk-text-bold" for="preview">Audio Resumen:</label>
						        <form id="form_preview">
						        	<input type="hidden" name="podcast_id" value="{{ $podcast->id }}">
						        	<input class="uk-input upload-input" type="file" name="preview" id="preview">
						        </form>
							</div>
						</div><br>

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

						<caption><h6>Recursos Multimedias</h6></caption>
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
									@if (!is_null($podcast->audio_file))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $podcast->audio_filename }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-audio img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Audio del T-Book</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ $podcast->audio_file }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_audiofile"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($podcast->cover))
										<tr>
											<td class="uk-text-center uk-table-shrink">{{ $podcast->cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_cover"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($podcast->preview))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $podcast->preview_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-audio img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Audio Resumen</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ $podcast->preview }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_preview"></a>
											</td>
										</tr>
									@endif
									
								</tbody>
							</table>
						</div>
				    </li>

				    <!-- Recursos Descargables -->
				    <li>
				    	<br>
				    	<div class="uk-grid">
				    		<div class="uk-width-1-1">
						        <label class="uk-form-label uk-text-bold" for="audio_file">Nuevo Recurso</label>
						        <input class="uk-input resource-file-submit" type="file" name="resource" id="resource">
							</div>
						</div><br>

						<div id="progressbar2" class="uk-text-center" style="display: none;">
							<br>
							<progress id="js-progressbar2" class="uk-progress" value="0" max="100"></progress>
							<label class="uk-label uk-label-warning" id="uploadPercentage2">0%</label>
						</div>
						<div id="loader2" class="uk-text-center" style="display: none;">
							<br>
							<label class="uk-label uk-label-success uk-form-width-large">Archivo Cargado (100%)</label><br><br>
							<span uk-spinner="ratio: 1.5"></span><br>
							Por favor espere mientras se termina de configurar el archivo...
							<hr>
						</div>

						<caption><h6>Recursos Descargables</h6></caption>
						<div class="uk-overflow-auto">
							<table class="uk-table uk-table-middle uk-table-responsive">
								<thead style="background-color: #D8D7D7;">
									<tr>
										<th class="uk-text-center uk-table-expand">Nombre</th>
										<th class="uk-text-center uk-table-shrink">Thumbnail</th>
										<th class="uk-text-center uk-table-expand">Fecha de Modificación</th>
										<th class="uk-text-center uk-table-shrink">Tipo</th>
										<th class="uk-text-center uk-table-shrink" colspan="2">Acción</th>
									</tr>
								</thead>
								<tbody id="resources">
									@if ($cantRecursos > 0)
										@foreach ($podcast->resources as $recurso)
											<tr>
												<td class="uk-text-center uk-table-expand">{{ $recurso->filename }}</td>
												<td class="uk-text-center uk-table-shrink">
													<i class="{{ $recurso->file_icon }} img-icon"></i>
												</td>
												<td class="uk-text-center uk-table-expand">{{ date('d-m-Y H:i A', strtotime("$recurso->updated_at -5 Hours")) }}</td>
												<td class="uk-text-center uk-table-shrink">.{{ $recurso->file_extension }}</td>
												<td class="uk-text-right">
													@if ( ($recurso->file_extension == 'png') || ($recurso->file_extension == 'jpg') || ($recurso->file_extension == 'gif') || ($recurso->file_extension == 'svg') || ($recurso->file_extension == 'mp4') )
														<div uk-lightbox>
															<a href="{{ $recurso->link }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
														</div>
													@else
														<a href="{{ $recurso->link }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
													@endif
												</td>
												<td class="uk-text-left">
													<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="{{ $recurso->id }}"></a>
												</td>
											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
				    </li>
				</ul>
		    </div>	

		    <div class="uk-card-footer uk-text-right">
		        <button type="submit" class="uk-button uk-button-danger" id="btn-submit">Guardar Cambios</button>
		    </div>
	    </div>
	</div>
	
	<form id="form_delete">
		<input type="hidden" name="podcast_id" value="{{ $podcast->id }}">
		<input type="hidden" name="file_type" id="file_type">
	</form>

	<form id="form_resource_delete">
		<input type="hidden" name="resource_id" id="resource_id">
	</form>

	<input type="hidden" id="accessKeyId" value="QUtJQUpHUjNPSTJQVDJUUVY0S0E=">
    <input type="hidden" id="secretAccessKey" value="ak8xZTI1SW1yM2NDS2IwYlpmLzRLZncvNWJFSE9wVDR1Q1gvc09acA==">
@endsection