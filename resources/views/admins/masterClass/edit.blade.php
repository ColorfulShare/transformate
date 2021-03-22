@extends('layouts.admin')

@push('scripts')
	<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.24.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/node-uuid/1.4.7/uuid.min.js"></script>

	<script>
		function notificar($tipo_actualizacion){
			if ($tipo_actualizacion == 'cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i> La portada ha sido subida con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'video_file'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El video ha sido subido con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_cover'){
				UIkit.notification({message:'<i class="fa fa-check"></i>La portada ha sido eliminada con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_videofile'){
				UIkit.notification({message:'<i class="fa fa-check"></i>El video ha sido eliminado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'new_resource'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El recurso descargable ha sido cargado con éxito.', status: 'success'});
			}else if ($tipo_actualizacion == 'delete_resource'){
				UIkit.notification({message:'<i class="fa fa-check"></i> El recurso descargable ha sido eliminado con éxito.', status: 'success'});
			}
		}

		function updateFiles($clase){
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/ajax/multimedias-por-contenido/master-class/"+$clase;
			}else{
				var route = "https://transformatepro.com/ajax/multimedias-por-contenido/master-class/"+$clase;
			}
            //var route = "http://localhost:8000/ajax/multimedias-por-contenido/master-class/"+$clase;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#content").html(ans);
                }
            });
		}

		function updateResources($clase){
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/admins/t-master-class/resources/"+$clase;
			}else{
				var route = "https://transformatepro.com/admins/t-master-class/resources/"+$clase;
			}
            //var route = "http://localhost:8000/admins/t-master-class/resources/"+$clase;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#resources").html(ans);
                }
            });
		}

		$(function(){
			$('#btn-submit').on('click', function(){
				$("#form_content" ).submit();
			});

			//Capturar cuando se suba un nuevo archivo multimedia
	        $('.upload-input').on('change', function(e) {
	            e.preventDefault();

				var url = {{ $www }};
				if (url == 1){
					var route = "https://www.transformatepro.com/admins/t-master-class/update";
				}else{
					var route = "https://transformatepro.com/admins/t-master-class/update";
				}
            	//var route = "http://localhost:8000/admins/t-master-class/update";
            	var element = $(this).attr('id');
            	if (element == 'cover'){
            		var formData = new FormData(document.getElementById("form_cover"));
            	}else if (element == 'video_file'){
            		var formData = new FormData(document.getElementById("form_videofile"));
            	}

            	document.getElementById("cover").disabled = true;
            	document.getElementById("video_file").disabled = true;
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
	                updateFiles(document.getElementById("master_class_id").value);
	                document.getElementById("cover").disabled = false;
            		document.getElementById("video_file").disabled = false;
            		document.getElementById("btn-submit").disabled = false;
	            });
	        });

			//Capturar cuando se suba un nuevo archivo descargable
	        $('.resource-file-submit').on('change', function(e) {
	            e.preventDefault();

				var url = {{ $www }};
				if (url == 1){
					var route = "https://www.transformatepro.com/admins/t-master-class/load-resource";
				}else{
					var route = "https://transformatepro.com/admins/t-master-class/load-resource";
				}
            	//var route = "http://localhost:8000/admins/t-master-class/load-resource";
				
				var formData = new FormData(document.getElementById("form_resource"));
				
				document.getElementById("resource").disabled = true;
            	document.getElementById("btn-submit").disabled = true;

	            $.ajax({
	                xhr: function () {
	                    var xhr = new window.XMLHttpRequest();
	                    xhr.upload.addEventListener("progress", function(evt){
	                    	if (evt.lengthComputable) {
				            	document.getElementById('progressbar2').style.display = 'block';
				                var percentComplete = Math.round((evt.loaded / evt.total)*100);
				                var bar = document.getElementById('js-progressbar2');
				                bar.value = percentComplete;
				                document.getElementById('uploadPercentage2').innerHTML = percentComplete+' %';
				                if(percentComplete === 100){
				                	document.getElementById('progressbar2').style.display = 'none';
				                	document.getElementById('loader2').style.display = 'block';
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
	            	document.getElementById('progressbar2').style.display = 'none';
					document.getElementById('loader2').style.display = 'none';
					notificar('new_resource');
					updateResources(document.getElementById("master_class_id").value);
            		document.getElementById("resource").disabled = false;
					document.getElementById("btn-submit").disabled = false;
	            });
	        });

	        /*Capturar cuando se suba un archivo descargable
	        $('.resource-file-submit').on('change', function(e) {
	            e.preventDefault();
            	var clase_id = document.getElementById("master_class_id").value;

            	document.getElementById("resource").disabled = true;

            	var bucket = new AWS.S3({params: {Bucket: 'transformate-videos'}});
                var uploadFiles = $('#resource')[0];
                var upFile = uploadFiles.files[0];
                if (upFile) {
                	var nombre = upFile.name;
                    var id = uuid.v1();
                    var ext = upFile.name.split(".");
                    var extension = ext[1];
                    var path =  'master-class/'+clase_id+'/resources/'+id+'.'+extension;
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
		            		var route = "https://www.transformatepro.com/admins/t-master-class/load-resource";
		            	}else{
		            		var route = "https://transformatepro.com/admins/t-master-class/load-resource";
		            	}
                        //var route = "http://localhost:8000/admins/t-master-class/load-resource";

                        var parametros = {"master_class_id": clase_id, "nombre_archivo" : nombre, "extension" : extension, "direccion" : path};
                        
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
					                updateResources(clase_id);
            						document.getElementById("resource").disabled = false;
                                }                        
                            }
                        });
                    });
                }

                return false;
	        });*/

	        //Capturar cuando se elimine un archivo descargable
	        $('.delete-input').on('click', function(e) {
	            e.preventDefault();

	            document.getElementById("resource_id").value = $(this).attr('id');

            	var url = {{ $www }};
		        if (url == 1){
		          	var route = "https://www.transformatepro.com/admins/t-master-class/delete-resource";
		        }else{
		            var route = "https://transformatepro.com/admins/t-master-class/delete-resource";
		        }
                //var route = "http://localhost:8000/admins/t-master-class/delete-resource";

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
	            	updateResources(document.getElementById("master_class_id").value);
		            notificar('delete_resource');
	            });
	        });
		});
	</script>
@endpush

@section('content')
    <div class="admin-content-inner">
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
            <div class="uk-card-header uk-text-bold">
                <i class="fa fa-edit uk-margin-small-right"></i> Editar Master Class
            </div>   

		    <div class="uk-card-body">
		        <ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
			        <li aria-expanded="true" class="uk-active">
				        <a href="#"> Datos Informativos</a>
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
						<form class="uk-form-horizontal uk-margin-large" action="{{ route('admins.master-class.update') }}" method="POST" enctype="multipart/form-data" id="form_content">
							@csrf
							<input type="hidden" name="master_class_id" id="master_class_id" value="{{ $clase->id }}">
					    	<!--Datos Informativos -->
					    	<br>
					    	<div class="uk-grid">
					    		<div class="uk-width-1-1">
					    			<label class="uk-form-label" for="title"><b>Título:</b></label>
						            <input class="uk-input" id="title" name="title" type="text" value="{{ $clase->title }}"> 
					   			</div> 

					   			<div class="uk-width-1-1">
						        	<label class="uk-form-label" for="subtitle"><b>Subtítulo:</b></label>
						        	<input class="uk-input" id="subtitle" name="subtitle" type="text" maxlength="100" value="{{ $clase->subtitle }}"> 
						    	</div>

                                <label class="uk-form-label" for="review"><b>Reseña</b></label><br>
                                <div class="uk-margin">
                                    <textarea class="ckeditor" name="review" id="review" rows="5">
                                        {{ $clase->review }}
                                    </textarea>
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
				    </form>

				    <!-- Recursos Multimedia -->
				    <li>
				    	<br>
				    	<div class="uk-grid">
				    		<div class="uk-width-1-1">
						        <label class="uk-form-label uk-text-bold" for="video_file">Video de la T-Master Class:</label>
						        <form id="form_videofile">
						        	<input type="hidden" name="master_class_id" value="{{ $clase->id }}">
						        	<input class="uk-input upload-input" type="file" name="video_file" id="video_file">
						        </form>
							</div>

							<div class="uk-width-1-2">
							    <label class="uk-form-label" for="cover"><b>Portada de la T-Master Class:</b></label>
							    <form id="form_cover">
							    	<input type="hidden" name="master_class_id" value="{{ $clase->id }}">
							        <input class="uk-input upload-input" type="file" name="cover" id="cover">
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
										<th class="uk-text-center uk-width-auto">Acción</th>
									</tr>
								</thead>
								<tbody id="content">
									@if (!is_null($clase->video_file))
										<tr>
											<td class="uk-text-center uk-table-expand">{{ $clase->video_filename }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-video img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Video de la T-Master Class</td>
											<td class="uk-text-center">
												<a href="{{ $clase->video_file }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
											</td>
										</tr>
									@endif
									@if (!is_null($clase->cover))
										<tr>
											<td class="uk-text-center uk-table-shrink">{{ $clase->cover_name }}</td>
											<td class="uk-text-center uk-table-shrink">
												<i class="fa fa-file-image img-icon"></i>
											</td>
											<td class="uk-text-center uk-table-shrink">Portada</td>
											<td class="uk-text-center">
												<div uk-lightbox>
													<a href="{{ asset('uploads/images/master-class/'.$clase->cover) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
												</div>
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
								<form id="form_resource">
									<label class="uk-form-label uk-text-bold" for="audio_file">Nuevo Recurso</label>
									<input class="uk-input resource-file-submit" type="file" name="resource" id="resource">
									<input type="hidden" name="master_class_id" value="{{ $clase->id }}">
								</form>
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
										@foreach ($clase->resources as $recurso)
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
		<input type="hidden" name="master_class_id" value="{{ $clase->id }}">
		<input type="hidden" name="file_type" id="file_type">
	</form>

	<form id="form_resource_delete">
		<input type="hidden" name="resource_id" id="resource_id">
	</form>

	<input type="hidden" id="accessKeyId" value="QUtJQUpHUjNPSTJQVDJUUVY0S0E=">
    <input type="hidden" id="secretAccessKey" value="ak8xZTI1SW1yM2NDS2IwYlpmLzRLZncvNWJFSE9wVDR1Q1gvc09acA==">
@endsection