@extends('layouts.instructor')

@push('scripts')
	<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.24.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/node-uuid/1.4.7/uuid.min.js"></script>
	<script>
		function addLesson($modulo){
			$("#module_id").val($modulo);
			modal = UIkit.modal("#new-lesson");
            modal.show();
		}

		function videoModal($leccion){
			$("#lesson_id").val($leccion);
			modal = UIkit.modal("#upload-video");
            modal.show();
		}

		$(function(){
			AWS.config.update({
	            accessKeyId : 'AKIAJGR3OI2PT2TQV4KA',
	            secretAccessKey : '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'
	        });
	        AWS.config.region = 'us-east-2';
	        var bucket = new AWS.S3({params: {Bucket: 'transformate-videos'}});

			$('.video-file-upload').on('change', function(e) {
	            e.preventDefault();

	            var certificacion = $("#certification_id").val();
	            var leccion = $("#lesson_id").val();
	            var uploadFiles = $('#video')[0];
	            var upFile = uploadFiles.files[0]; 
	           
	            if (upFile) { 
	                var nombre = upFile.name;
	                var ext = upFile.name.split(".");
	                var extension = ext[1];
	                var path =  'certifications/'+certificacion+'/'+leccion+'.'+extension;
	                var uploadParams = {Key: path, Body: upFile};

	                bucket.upload(uploadParams).on('httpUploadProgress', function(progress) {
	                	console.log(progress);
	                    document.getElementById('progressbar').style.display = 'block';
	                    var uploaded = parseInt((progress.loaded * 100) / progress.total);
	                    var bar = document.getElementById('js-progressbar');
	                    bar.value = uploaded;
	                    document.getElementById('uploadPercentage').innerHTML = uploaded+' %';
	                    if(uploaded === 100){
					        document.getElementById('progressbar').style.display = 'none';
					        document.getElementById('loader').style.display = 'block';
					    }
	                }).send(function(err, data) {
	                	console.log(err);
	                	$("#filename").val(nombre);
	                	$("#file_extension").val(extension);
	                	$("#file_path").val(path);
	                	$("#load-video-form").submit();
	                });
	            }
	        });

	        $('.upload-resource').on('click', function(e) {
	        	e.preventDefault();
	        	$("#lesson_id_resource").val($(this).attr('data-id'));
	        	modal = UIkit.modal("#new-resource");
            	modal.show();
	        });

	        $('.resource-file-upload').on('change', function(e) {
	            e.preventDefault();

	            var certificacion = $("#certification_id").val();
	            var leccion = $("#lesson_id_resource").val();
	            var uploadFiles = $('#resource')[0];
	            var upFile = uploadFiles.files[0]; 
	           
	            if (upFile) { 
	                var nombre = upFile.name;
	                var id = uuid.v1();
	                var ext = upFile.name.split(".");
	                var extension = ext[1];
	                var path =  'certifications/'+certificacion+'/resources/'+id+'.'+extension;
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
	                	$("#filename_resource").val(nombre);
	                	$("#file_extension_resource").val(extension);
	                	$("#file_path_resource").val(path);
	                	$("#load-resource-form").submit();
	                });
	            }
	        });

	        $('.show-resources').on('click', function(e) {
	        	e.preventDefault();

	        	var route = $(this).attr('data-route');
 				$.ajax({
	                url:route,
	                type:'GET',
	                success:function(ans){
	                	$("#resources-content-modal").html(ans); 
	                    modal = UIkit.modal("#show-resources");
	                    modal.show();
	                }
	            });
	        });

	        $('.edit-lesson').on('click', function(e) {
	        	e.preventDefault();
	        	$("#lesson_id2").val($(this).attr('data-id'));
	        	$("#lesson_title").val($(this).attr('data-title'));
	        	modal = UIkit.modal("#edit-lesson");
            	modal.show();
	        });

	        $('.edit-module').on('click', function(e) {
	        	e.preventDefault();
	        	$("#module_id2").val($(this).attr('data-id'));
	        	$("#module_title").val($(this).attr('data-title'));
	        	modal = UIkit.modal("#edit-module");
            	modal.show();
	        });

		});
	</script>
@endpush

@section('content')
	<input type="hidden" id="accessKeyId" value="QUtJQUpHUjNPSTJQVDJUUVY0S0E=">
    <input type="hidden" id="secretAccessKey" value="ak8xZTI1SW1yM2NDS2IwYlpmLzRLZncvNWJFSE9wVDR1Q1gvc09acA==">
	<div class="uk-container">
		@if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        @if (Session::has('msj-erroneo'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        @endif
		<div class="uk-card-default">
			<div class="uk-card-header uk-text-center">
		        <h4>Temario de la Mentoría</h4>
		        <input type="hidden" id="certification_id" value="{{ $certificacion->id }}">
		    </div> 

		    <div class="uk-card-body">
		    	<div class="uk-text-center">
		    		<a class="uk-button uk-button-success" href="#new-module" uk-toggle><i class="fa fa-plus-circle"></i> Agregar Módulo</a>
		    	</div>
		    	<ul uk-accordion>
		    		@foreach ($certificacion->modules as $modulo)
					    <li>
					        <a class="uk-accordion-title uk-text-bold" href="#">Módulo #{{ $modulo->priority_order }}.- {{ $modulo->title }}</a>
					        <div class="uk-accordion-content">
								<div class="uk-text-center">
									<a href="javascript:;" data-id="{{ $modulo->id }}" data-title="{{ $modulo->title }}" class="uk-button uk-button-primary edit-module"><i class="fa fa-edit"></i>  Editar Módulo</a>
									<a class="uk-button uk-button-danger" href="{{ route('instructors.certifications.temary.delete-module', $modulo->id) }}"><i class="fa fa-trash"></i> Eliminar Módulo</a>
								</div>
					            <table class="uk-table uk-table-striped">
								    <thead>
								        <tr>
								            <th class="uk-text-center"># Lección</th>
								            <th class="uk-text-center">Título</th>
								            <th class="uk-text-center">Video</th>
								            <th class="uk-text-center">Acción</th>
								        </tr>
								    </thead>
								    <tbody>
								    	@foreach ($modulo->lessons as $leccion)
									        <tr>
									            <td class="uk-text-center">{{ $leccion->priority_order }}</td>
									            <td class="uk-text-center">{{ $leccion->title }}</td>
									            <td class="uk-text-center">
									            	@if (!is_null($leccion->video))
									            		{{ $leccion->filename }}
									            	@else
									            		Sin Video
									            	@endif
									            </td>
									            <td class="uk-text-center">
									            	<a href="javascript:;" onclick="videoModal({{$leccion->id}});" class="uk-icon-button uk-button-primary" uk-icon="icon: video-camera;" uk-tooltip="Cargar Video"></a>
									            	<a href="javascript:;" data-id="{{ $leccion->id }}" data-title="{{ $leccion->title }}" class="uk-icon-button uk-button-success edit-lesson" uk-icon="icon: pencil;" uk-tooltip="Editar Lección"></a>
									            	<a href="javascript:;" data-id="{{ $leccion->id }}" class="uk-icon-button uk-button-primary upload-resource" uk-icon="icon: link;" uk-tooltip="Agregar Recurso"></a>
									            	@if ($leccion->resource_files->count() > 0)
									            		<a href="javascript:;" data-route="{{ route('instructors.certifications.temary.show-resources', $leccion->id) }}" class="uk-icon-button uk-button-success show-resources" uk-icon="icon: list;" uk-tooltip="Ver Lista de Recursos"></a>
									            	@endif
									            	<a href="{{ route('instructors.certifications.temary.delete-lesson', $leccion->id) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar Lección"></a>
									            </td>
									        </tr>
									    @endforeach
									    <tr>
									    	<td class="uk-text-center" colspan="4"><a class="uk-button uk-button-primary" href="javascript:;" onclick="addLesson({{$modulo->id}});"><i class="fa fa-plus-circle"></i> Agregar Lección</a></td>
									    </tr>
								    </tbody>
								</table>
					        </div>
					    </li>
					@endforeach
				</ul>
		    </div>
		</div>
	</div>

	<div id="new-module" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Agregar Módulo</h2>
	        </div>
	        <form action="{{ route('instructors.certifications.temary.add-module') }}" method="POST">
	        	@csrf
	        	<input type="hidden" name="content_type" value="certificacion">
	        	<input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
	        	<input type="hidden" name="certification_slug" value="{{ $certificacion->slug }}">
		        <div class="uk-modal-body">
		            <div class="uk-grid">
						<div class="uk-width-1-1">
						    <label class="uk-form-label" for="title"><b>Título:</b></label>
							<input class="uk-input" name="title" type="text" required> 
						</div> 
					</div>  		
		        </div>
		        <div class="uk-modal-footer uk-text-right">
		            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
		            <button class="uk-button uk-button-primary" type="submit">Crear Módulo</button>
		        </div>
		    </form>
	    </div>
	</div>

	<div id="new-lesson" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Agregar Lección</h2>
	        </div>
	        <form action="{{ route('instructors.certifications.temary.add-lesson') }}" method="POST">
	        	@csrf
	        	<input type="hidden" name="module_id" id="module_id">
		        <div class="uk-modal-body">
		            <div class="uk-grid">
						<div class="uk-width-1-1">
						    <label class="uk-form-label" for="title"><b>Título:</b></label>
							<input class="uk-input" id="title" name="title" type="text" required> 
						</div> 
					</div>  		
		        </div>
		        <div class="uk-modal-footer uk-text-right">
		            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
		            <button class="uk-button uk-button-primary" type="submit">Crear Lección</button>
		        </div>
		    </form>
	    </div>
	</div>

	<div id="upload-video" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Cargar Video</h2>
	        </div>
		    <div class="uk-modal-body">
		        <div class="uk-grid">
					<div class="uk-width-1-1">
						<label class="uk-form-label" for="title"><b>Video: </b></label>
						<input class="uk-input video-file-upload" id="video" name="video" type="file" required> 
					</div> 
					<div class="uk-width-1-1" style="padding-top: 15px;">
						<div id="progressbar" class="uk-text-center" style="display: none;">
							<progress id="js-progressbar" class="uk-progress" value="0" max="100"></progress>
							<label class="uk-label uk-label-warning" id="uploadPercentage">0%</label>
						</div>
						<div id="loader" class="uk-text-center" style="display: none;">
							<label class="uk-label uk-label-success uk-form-width-large">Archivo Cargado (100%)</label><br><br>
							<span uk-spinner="ratio: 1.5"></span><br>
							Por favor espere mientras se termina de configurar la lección...
							<hr>
						</div>
					</div>  		
		    	</div>
		    </div>
		    <div class="uk-modal-footer uk-text-right">
		        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
		    </div>
	    </div>
	</div>

	<div id="edit-lesson" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Modificar Lección</h2>
	        </div>
	        <form action="{{ route('instructors.certifications.temary.update-lesson') }}" method="POST">
	        	@csrf
	        	<input type="hidden" name="lesson_id" id="lesson_id2">
		        <div class="uk-modal-body">
		            <div class="uk-grid">
						<div class="uk-width-1-1">
						    <label class="uk-form-label" for="title"><b>Título:</b></label>
							<input class="uk-input" id="lesson_title" name="title" type="text" required> 
						</div> 
					</div>  		
		        </div>
		        <div class="uk-modal-footer uk-text-right">
		            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
		            <button class="uk-button uk-button-primary" type="submit">Modificar Lección</button>
		        </div>
		    </form>
	    </div>
	</div>

	<div id="new-resource" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Cargar Recurso</h2>
	        </div>
		    <div class="uk-modal-body">
		        <div class="uk-grid">
					<div class="uk-width-1-1">
						<label class="uk-form-label" for="title"><b>Archivo de Recurso: </b></label>
						<input class="uk-input resource-file-upload" id="resource" name="resource" type="file" required> 
					</div> 
					<div class="uk-width-1-1" style="padding-top: 15px;">
						<div id="progressbar2" class="uk-text-center" style="display: none;">
							<progress id="js-progressbar2" class="uk-progress" value="0" max="100"></progress>
							<label class="uk-label uk-label-warning" id="uploadPercentage2">0%</label>
						</div>
						<div id="loader2" class="uk-text-center" style="display: none;">
							<label class="uk-label uk-label-success uk-form-width-large">Archivo Cargado (100%)</label><br><br>
							<span uk-spinner="ratio: 1.5"></span><br>
							Por favor espere mientras se termina de configurar el recurso...
							<hr>
						</div>
					</div>  		
		    	</div>
		    </div>
		    <div class="uk-modal-footer uk-text-right">
		        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
		    </div>
	    </div>
	</div>

	<div id="show-resources" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Ver Recursos</h2>
	        </div>
		    <div class="uk-modal-body" id="resources-content-modal">
		       
		    </div>
		    <div class="uk-modal-footer uk-text-right">
		        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
		    </div>
	    </div>
	</div>

	<div id="edit-module" uk-modal>
	    <div class="uk-modal-dialog">
	        <button class="uk-modal-close-default" type="button" uk-close></button>
	        <div class="uk-modal-header">
	            <h2 class="uk-modal-title">Modificar Módulo</h2>
	        </div>
	        <form action="{{ route('instructors.certifications.temary.update-module') }}" method="POST">
	        	@csrf
	        	<input type="hidden" name="module_id" id="module_id2">
		        <div class="uk-modal-body">
		            <div class="uk-grid">
						<div class="uk-width-1-1">
						    <label class="uk-form-label" for="title"><b>Título:</b></label>
							<input class="uk-input" id="module_title" name="title" type="text" required> 
						</div> 
					</div>  		
		        </div>
		        <div class="uk-modal-footer uk-text-right">
		            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
		            <button class="uk-button uk-button-primary" type="submit">Modificar Módulo</button>
		        </div>
		    </form>
	    </div>
	</div>

	<form action="{{ route('instructors.certifications.temary.load-video') }}" method="POST" enctype="multipart/form-data" id="load-video-form">
		@csrf
		<input type="hidden" name="lesson_id" id="lesson_id">
		<input type="hidden" name="filename" id="filename">
		<input type="hidden" name="file_extension" id="file_extension">
		<input type="hidden" name="file_path" id="file_path">
	</form>

	<form action="{{ route('instructors.certifications.temary.load-resource') }}" method="POST" enctype="multipart/form-data" id="load-resource-form">
		@csrf
		<input type="hidden" name="lesson_id" id="lesson_id_resource">
		<input type="hidden" name="filename" id="filename_resource">
		<input type="hidden" name="file_extension" id="file_extension_resource">
		<input type="hidden" name="file_path" id="file_path_resource">
	</form>
@endsection