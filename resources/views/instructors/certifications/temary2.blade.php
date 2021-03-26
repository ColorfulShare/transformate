@extends('layouts.instructor')

@push('scripts')
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.24.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/node-uuid/1.4.7/uuid.min.js"></script>

	<script>
		function notificar($tipo_actualizacion){
			if ($tipo_actualizacion == 'titulo_leccion'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El título de la lección se ha actualizado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'modulo'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El título del módulo se ha actualizado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'video_leccion'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El archivo ha sido cargado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'nuevo_recurso'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El recurso ha sido cargado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'video_eliminado'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El video de la lección ha sido eliminado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'recurso_eliminado'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El archivo de recurso ha sido eliminado con éxito.', status: 'success'});
			}
		}
		function updateFiles($leccion){
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/ajax/archivos-por-leccion/"+$leccion;
			}else{
				var route = "https://transformatepro.com/ajax/archivos-por-leccion/"+$leccion;
			}
            //var route = "http://localhost:8000/ajax/archivos-por-leccion/"+$leccion;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#lesson_files_"+$leccion).html(ans);
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
			//Capturar submit para modificar el título de un módulo
	        $('.btn-submit-modulo').on('click', function(e) {
	        	e.preventDefault();
            	var element = $(this);
            	var id = (element.attr('id')).split('_');
            	var url = {{ $www }};
            	if (url == 1){
            		var route = "https://www.transformatepro.com/instructors/t-mentorings/update-temary";
            	}else{
            		var route = "https://transformatepro.com/instructors/t-mentorings/update-temary";
            	}
            	//var route = "http://localhost:8000/instructors/t-mentorings/update-temary";
            	var formData = new FormData(document.getElementById("form_module_"+id[1]));
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
	                notificar('modulo');
	            });
	        });
	        //Capturar el submit para modificar el título de una lección
	        $('.btn-submit-leccion').on('click', function(e) {
	        	e.preventDefault();
            	var element = $(this);
            	var id = (element.attr('id')).split('_');
            	var url = {{ $www }};
            	if (url == 1){
            		var route = "https://www.transformatepro.com/instructors/t-mentorings/update-temary";
            	}else{
            		var route = "https://transformatepro.com/instructors/t-mentorings/update-temary";
            	}
            	//var route = "http://localhost:8000/instructors/t-mentorings/update-temary";
            	var formData = new FormData(document.getElementById("form_title_"+id[1]));
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
	                notificar('titulo_leccion');
	            });
	        });
	        //Capturar cuando se suba el video de la lección
	        $('.video-file-submit').on('change', function(e) {
	            e.preventDefault();
	            var element = $(this);
            	var leccion_id = (element.attr('id')).split('_');
            	document.getElementById("file_"+leccion_id[1]).disabled = true;
            	document.getElementById("resource_"+leccion_id[1]).disabled = true;
            	var bucket = new AWS.S3({params: {Bucket: 'transformate-videos'}});
                var uploadFiles = $('#file_'+leccion_id[1])[0];
                var upFile = uploadFiles.files[0];
                if (upFile) {
                	var nombre = upFile.name;
                    var id = uuid.v1();
                    var ext = upFile.name.split(".");
                    var extension = ext[1];
                    var path =  'certifications/lessons/'+id+'.'+extension;
                    var uploadParams = {Key: path, Body: upFile};
                    bucket.upload(uploadParams).on('httpUploadProgress', function(progress) {
                    	document.getElementById('progressbar_'+leccion_id[1]).style.display = 'block';
                        var uploaded = parseInt((progress.loaded * 100) / progress.total);
                        var bar = document.getElementById('js-progressbar-'+leccion_id[1]);
                        bar.value = uploaded;
                        document.getElementById('uploadPercentage_'+leccion_id[1]).innerHTML = uploaded+' %';
                        if(uploaded === 100){
				            document.getElementById('progressbar_'+leccion_id[1]).style.display = 'none';
				            document.getElementById('loader_'+leccion_id[1]).style.display = 'block';
				        }
                    }).send(function(err, data) {
                    	var url = {{ $www }};
		            	if (url == 1){
		            		var route = "https://www.transformatepro.com/instructors/t-mentorings/update-temary";
		            	}else{
		            		var route = "https://transformatepro.com/instructors/t-mentorings/update-temary";
		            	}
                        //var route = "http://localhost:8000/instructors/t-mentorings/update-temary";
                        var parametros = {"leccion": leccion_id[1], "nombre_archivo" : nombre, "extension" : extension, "direccion" : path, 'tipo_actualizacion' : 'video_leccion'};
                        
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                            },
                            url:route,
                            type:'POST',
                            data:  parametros,
                            success:function(ans){
                                if (ans == true){
                                    document.getElementById('progressbar_'+leccion_id[1]).style.display = 'none';
					            	document.getElementById('loader_'+leccion_id[1]).style.display = 'none';
					                notificar('video_leccion');
					                updateFiles(leccion_id[1]);
					                document.getElementById("file_"+leccion_id[1]).disabled = false;
            						document.getElementById("resource_"+leccion_id[1]).disabled = false;
                                }                        
                            }
                        });
                    });
                }
                return false;
	        });
	        //Capturar cuando se suba un archivo de recursos a la lección
	        $('.resource-file-submit').on('change', function(e) {
	            e.preventDefault();
	            var element = $(this);
            	var leccion_id = (element.attr('id')).split('_');
            	
            	document.getElementById("file_"+leccion_id[1]).disabled = true;
            	document.getElementById("resource_"+leccion_id[1]).disabled = true;
            	var bucket = new AWS.S3({params: {Bucket: 'transformate-videos'}});
                var uploadFiles = $('#resource_'+leccion_id[1])[0];
                var upFile = uploadFiles.files[0];
                if (upFile) {
                	var nombre = upFile.name;
                    var id = uuid.v1();
                    var ext = upFile.name.split(".");
                    var extension = ext[1];
                    var path =  'certifications/lessons/resources/'+id+'.'+extension;
                    var uploadParams = {Key: path, Body: upFile};
                    bucket.upload(uploadParams).on('httpUploadProgress', function(progress) {
                    	document.getElementById('progressbar_'+leccion_id[1]).style.display = 'block';
                        var uploaded = parseInt((progress.loaded * 100) / progress.total);
                        var bar = document.getElementById('js-progressbar-'+leccion_id[1]);
                        bar.value = uploaded;
                        document.getElementById('uploadPercentage_'+leccion_id[1]).innerHTML = uploaded+' %';
                        if(uploaded === 100){
				            document.getElementById('progressbar_'+leccion_id[1]).style.display = 'none';
				            document.getElementById('loader_'+leccion_id[1]).style.display = 'block';
				        }
                    }).send(function(err, data) {
                    	var url = {{ $www }};
		            	if (url == 1){
		            		var route = "https://www.transformatepro.com/instructors/t-mentorings/update-temary";
		            	}else{
		            		var route = "https://transformatepro.com/instructors/t-mentorings/update-temary";
		            	}
                        //var route = "http://localhost:8000/instructors/t-mentorings/update-temary";
                        var parametros = {"leccion": leccion_id[1], "nombre_archivo" : nombre, "extension" : extension, "direccion" : path, 'tipo_actualizacion' : 'recurso'};
                        
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                            },
                            url:route,
                            type:'POST',
                            data:  parametros,
                            success:function(ans){
                                if (ans == true){
                                    document.getElementById('progressbar_'+leccion_id[1]).style.display = 'none';
					            	document.getElementById('loader_'+leccion_id[1]).style.display = 'none';
					                notificar('nuevo_recurso');
					                updateFiles(leccion_id[1]);
					                document.getElementById("file_"+leccion_id[1]).disabled = false;
            						document.getElementById("resource_"+leccion_id[1]).disabled = false;
                                }                        
                            }
                        });
                    });
                }
                return false;
	        });
	         //Capturar cuando se quiera eliminar algún archivo (video o recurso)
	        $('.delete-input').on('click', function(e) {
	            e.preventDefault();
	            var element = $(this);
	            var id = (element.attr('id')).split('_');
	            if (id[1] == 'lesson'){
	            	document.getElementById("lesson_id").value = id[2];
            	}else{
	            	document.getElementById("resource_id").value = id[2];
            	}
            	var url = {{ $www }};
            	if (url == 1){
            		var route = "https://www.transformatepro.com/instructors/lessons/delete-resource";
            	}else{
            		var route = "https://transformatepro.com/instructors/lessons/delete-resource";
            	}
            	//var route = "http://localhost:8000/instructors/lessons/delete-resource";
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
	            	updateFiles(res);
	            	if (id[1] == 'lesson'){
		            	notificar('video_eliminado');
		            }else{
		            	notificat('recurso_eliminado');
		            }
	            });
	        });
		});
	</script>
@endpush

@section('content')
	<div class="uk-container">

		@if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif
		<div class="uk-card-default">
		    <div class="uk-card-header uk-text-center">
		        <h4>Temario de la Certificación</h4>
		    </div> 

		    <div class="uk-card-body">
		        <ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
		        	@foreach ($certificacion->modules as $modulo)
			        	<li aria-expanded="true">
				        	<a href="#"> Módulo {{ $modulo->priority_order }}</a>
				    	</li>  
				    @endforeach              
				</ul>

				<ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto">
				    <!-- Módulo I -->
				    @foreach ($certificacion->modules as $modulo)
				    	<li>
				    		<form id="form_module_{{ $modulo->id }}">
								@csrf
								<input type="hidden" name="tipo_actualizacion" value="modulo">
								<input type="hidden" name="module_id" value="{{ $modulo->id }}">

								<div class="uk-grid">
									<div class="uk-width-1-1">
                                		<center><h5>Título del Módulo #{{ $modulo->priority_order }}: </h5></center>
                                		<input class="uk-input" type="text" name="title" value="{{ $modulo->title }}" required>
									</div>
									<div class="uk-width-1-1 uk-text-center">
										<br>
                                		<button class="uk-button uk-button-success btn-submit-modulo" id="modulo_{{ $modulo->id }}">Guardar Cambios</button>
                                	</div>
								</div>
							</form>

                            <div class="uk-grid">
                            	<div class="uk-width-1-1">
                            		<hr>
                            	</div>
                            </div>
                            
	                        <ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
	                            @foreach ($modulo->lessons as $leccion)
								    <li aria-expanded="true">
									    <a href="#"> Lección {{ $leccion->priority_order }}</a>
									</li>  
					    		@endforeach              
							</ul>
								
							<ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto">
								@foreach ($modulo->lessons as $leccion)
									<li>
										<div class="uk-grid">
											<div class="uk-width-1-1">
												<h5><center>Lección N° {{ $leccion->priority_order }}</center></h5>
											</div>
											<div class="uk-width-1-1">
												<form id="form_title_{{ $leccion->id }}">
													<input type="hidden" name="tipo_actualizacion" value="titulo_leccion">
													<input type="hidden" name="lesson" value="{{ $leccion->id }}">
													<div class="uk-margin">
														Título:
											            <input class="uk-input" type="text" name="title" value="{{ $leccion->title }}" placeholder="Título de la Leciión" required>
											        </div> 
											        <center><button class="uk-button uk-button-success btn-submit-leccion" id="leccion_{{ $leccion->id }}">Actualizar Titulo</button></center>
				                                </form><hr>
				                            </div>
                                			<div class="uk-width-1-2">
				                                Video Lección: 
				                                <input class="uk-input video-file-submit" type="file" name="video" id="file_{{ $leccion->id }}">
				                            </div>
				                            <div class="uk-width-1-2">
				                                Archivo de Recursos: 
				                                <input class="uk-input resource-file-submit" type="file" name="resource" id="resource_{{ $leccion->id }}">
				                            </div>
										</div><hr>
										
										<div id="progressbar_{{ $leccion->id }}" class="uk-text-center" style="display: none;">
											<progress id="js-progressbar-{{ $leccion->id }}" class="uk-progress" value="0" max="100"></progress>
											<label class="uk-label uk-label-warning" id="uploadPercentage_{{ $leccion->id }}">0%</label>
										</div>
										<div id="loader_{{ $leccion->id }}" class="uk-text-center" style="display: none;">
											<label class="uk-label uk-label-success uk-form-width-large">Archivo Cargado (100%)</label><br><br>
											<span uk-spinner="ratio: 1.5"></span><br>
											Por favor espere mientras se termina de configurar la lección...
											<hr>
										</div>
										
										<caption><h6>Archivos de la Lección</h6></caption>
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
												<tbody id="lesson_files_{{ $leccion->id }}">
													@php
														$cantRecursos = $leccion->resource_files->count();
													@endphp
													@if (!is_null($leccion->video))
														<tr>
															<td class="uk-text-center uk-table-expand">{{ $leccion->filename }}</td>
															<td class="uk-text-center uk-table-shrink">
																<i class="{{ $leccion->file_icon }} img-icon"></i>
															</td>
															<td class="uk-text-center uk-table-expand">{{ date('d-m-Y H:i A', strtotime("$leccion->updated_at -5 Hours")) }}</td>
															<td class="uk-text-center uk-table-shrink">.{{ $leccion->file_extension }}</td>
															<td class="uk-text-right">
																<div uk-lightbox>
																	<a href="{{ $leccion->video }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
																</div>
															</td>
															<td class="uk-text-left">
																<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_lesson_{{ $leccion->id }}"></a>
															</td>
														</tr>
													@else
														@if ($cantRecursos == 0)
															<tr>
																<td colspan="5" class="uk-text-center">La lección no posee ningún archivo actualmente...</td>
															</tr>
														@endif
													@endif

													@if ($cantRecursos > 0)
														@foreach ($leccion->resource_files as $recurso)
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
																	<a href="#" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: trash;" id="delete_resource_{{ $recurso->id }}"></a>
																</td>
															</tr>
														@endforeach
													@endif
												</tbody>
											</table>
										</div>
									</li>
								@endforeach
							</ul>
				    	</li>
				    @endforeach
				</ul>
		    </div>	
	    </div>
	</div>

	<form id="form_delete">
		<input type="hidden" name="content_type" value="certificacion">
		<input type="hidden" name="lesson_id" id="lesson_id">
		<input type="hidden" name="resource_id" id="resource_id">
	</form>

	<input type="hidden" id="accessKeyId" value="QUtJQUpHUjNPSTJQVDJUUVY0S0E=">
    <input type="hidden" id="secretAccessKey" value="ak8xZTI1SW1yM2NDS2IwYlpmLzRLZncvNWJFSE9wVDR1Q1gvc09acA==">
@endsection