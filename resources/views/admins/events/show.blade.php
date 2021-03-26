@extends('layouts.admin')

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.15.0/full-all/ckeditor.js"></script>

    <script>
    	function checkOption(){
    		if (document.getElementById("type").value == 'pay'){
    			document.getElementById("price_div").style.display = 'block';
    		}else{
    			document.getElementById("price_div").style.display = 'none';
    		}
    	}

    	function changeFile($archivo){
    		document.getElementById("file_type").value = $archivo;
    		var modal = UIkit.modal("#load-file-modal");
            modal.show(); 
    	}

		var nextinput = 0;
        function addBenefit(){
            nextinput++;
            campo = '<input class="uk-input" type="text" name="benefits[]">';
            $("#benefits-div").append(campo);
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
                <i class="fas fa-glass-cheers uk-margin-small-right"></i> Detalles
            </div>              
	        <div class="uk-card-body"> 
	        	<form action="{{ route('admins.events.update') }}" method="POST" class="uk-grid-small" uk-grid id="form-principal"> 
	                @csrf
	                <input type="hidden" name="event_id" value="{{ $evento->id }}">
	                <div class="uk-width-1-1"> 
	                    <div class="uk-form-label">Título (*)</div>        
	                    <input class="uk-input" type="text" name="title" value="{{ $evento->title }}" required> 
	                </div>    
					<div class="uk-width-1-3">
						<div class="uk-form-label">Mentor (*)</div> 
                        <select class="uk-select" name="user_id" id="user_id" required>
                            <option value="free">Seleccione un mentor...</option>
                            @foreach ($mentores as $mentor)
                                <option value="{{ $mentor->id }}" @if ($mentor->id == $evento->user_id) selected @endif>@if (!is_null($mentor->names)) {{ $mentor->names }} {{ $mentor->last_names }} @else {{ $mentor->email }} @endif</option>
                            @endforeach
                        </select>
                    </div>                                 
	                <div class="uk-width-1-3"> 
	                    <div class="uk-form-label">Fecha (*)</div>        
	                    <input class="uk-input" type="date" name="date" value="{{ $evento->date }}" required> 
	                </div>
	                <div class="uk-width-1-3">
                        <div class="uk-form-label">Horario (*):</div>
                        <input class="uk-input" type="text" name="time" value="{{ $evento->time }}" required>
                    </div>
	                <div class="uk-width-1-3"> 
	                    <div class="uk-form-label">Tipo (*)</div>        
	                    <select class="uk-select" name="type" id="type" onchange="checkOption();">
	                    	<option value="free" @if ($evento->type == 'free') selected @endif>Gratuito</option>
	                    	<option value="pay" @if ($evento->type == 'pay') selected @endif>Pago</option>
	                    </select> 
	                </div>                                     
	                <div class="uk-width-1-3" id="price_div" @if ($evento->type == 'free') style="display: none;" @endif> 
	                    <div class="uk-form-label">Precio (*)</div>        
	                    <input class="uk-input" type="number" name="price" value="{{ $evento->price }}"> 
	                </div>
	                <div class="uk-width-1-3"> 
	                    <div class="uk-form-label">Estado (*)</div>        
	                    <select class="uk-select" name="status">
	                    	<option value="1" @if ($evento->status == 1) selected @endif>Disponible</option>
	                    	<option value="0" @if ($evento->status == 0) selected @endif>No Disponible</option>
	                    </select> 
	                </div>
	                <div class="uk-width-1-1 uk-margin-small-bottom">
                        Breve Leyenda (*):
                        <input class="uk-input" type="text" name="legend" maxlength="200" value="{{ $evento->legend }}" required>
                    </div> 
	                <div class="uk-width-1-1"> 
	                    <div class="uk-form-label">Descripción</div>    
	                    <textarea name="description" rows="10">{{ $evento->description }}</textarea>
                        <script>
		                    CKEDITOR.replace('description'); 
		               	</script>
					</div> 
					<div class="uk-width-1-1"> 
	                    <div class="uk-form-label">Beneficios</div>    
	                    <textarea name="benefits" rows="10">{{ $evento->benefits }}</textarea>
                        <script>
		                    CKEDITOR.replace('benefits'); 
		               	</script>
					</div> 
                    <div class="uk-width-1-1 uk-text-right">
						@if (Auth::user()->profile->events == 2)
							<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
						@else
							<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
						@endif 
					</div>
	            </form>
	        </div>
	    </div>
		<br>

		<div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold" uk-grid>
				<div class="uk-width-1-1 uk-text-left"><i class="fa fa-user uk-margin-small-right"></i> Sección Mentores</div>
            </div>              
	        <div class="uk-card-body"> 
	        	<form action="{{ route('admins.events.update') }}" method="POST" enctype="multipart/form-data" class="uk-grid-small" uk-grid> 
	                @csrf
	                <input type="hidden" name="event_id" value="{{ $evento->id }}">
					<div class="uk-width-1-1 uk-margin-small-bottom">
                        Texto:
                        <textarea name="mentor_section" rows="10">{{ $evento->mentor_section }}</textarea>
                        <script>
			                CKEDITOR.replace('mentor_section');   
			            </script>
                    </div><br>  
					<div class="uk-width-1-1 uk-margin-small-bottom">
						Título:
						<input class="uk-input" type="text" name="mentor_section_title" value="{{ $evento->mentor_section_title }}">
					</div>
					<div class="uk-width-1-1 uk-margin-small-bottom uk-text-right">
						<button class="uk-button uk-button-primary" type="button" uk-toggle="target: #new-instructor-image"><i class="fa fa-plus-circle"></i> Nueva Imagen</button>
						<table class="uk-table uk-table-middle uk-table-responsive">
							<thead style="background-color: #D8D7D7;">
								<tr>
									<th class="uk-text-center">Imagenes de Mentores</th>
									<th class="uk-text-center" colspan="2">Acción</th>
								</tr>
							</thead>
							<tbody>
								@if (!is_null($evento->mentor_section_img))
									<tr>
										<td class="uk-text-center">{{ $evento->mentor_section_img }}</td>
										<td class="uk-text-center" colspan="2">
											<div uk-lightbox>
												<a href="{{ asset('uploads/events/images/'.$evento->mentor_section_img) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
											</div>
										</td>
									</tr>
								@endif
								@foreach ($evento->images as $image)
									@if ($image->instructor_section == 1)
										<tr>
											<td class="uk-text-center">{{ $image->image }}</td>
											<td class="uk-text-right">
												<div uk-lightbox>
													<a href="{{ asset('uploads/events/images/'.$image->image) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
												</div>
											</td>
											<td class="uk-text-left">
												<a href="{{ route('admins.events.delete-image', $image->id) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar"></a>
											</td>
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="uk-width-1-1 uk-text-right">
						@if (Auth::user()->profile->events == 2)
							<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
						@else
							<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
						@endif 
					</div>
				</form><hr>
			</div>
		</div><br>

		<div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold" uk-grid>
				<div class="uk-width-1-1 uk-text-left"><i class="fa fa-asterisk uk-margin-small-right"></i> Sección Créditos</div>
            </div>              
	        <div class="uk-card-body"> 
	        	<form action="{{ route('admins.events.update') }}" method="POST" enctype="multipart/form-data" class="uk-grid-small" uk-grid> 
	                @csrf
	                <input type="hidden" name="event_id" value="{{ $evento->id }}">
					<div class="uk-width-1-1 uk-margin-small-bottom">
                        Texto:
                        <textarea name="credits" rows="10">{{ $evento->credits }}</textarea>
                        <script>
			                CKEDITOR.replace('credits');   
			            </script>
                    </div><br>  
					<div class="uk-width-1-2 uk-margin-small-bottom">
						Título:
						<input class="uk-input" type="text" name="credits_title" value="{{ $evento->credits_title }}">
					</div>
					<div class="uk-width-1-2 uk-margin-small-bottom">
						Imagen:
						<input class="uk-input" type="file" name="credits_img">
						<div uk-lightbox><a href="{{ asset('uploads/events/images/'.$evento->credits_img) }}">Ver Imagen Actual</a></div>
					</div> 
					<div class="uk-width-1-1 uk-text-right">
						@if (Auth::user()->profile->events == 2)
							<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
						@else
							<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
						@endif 
					</div>
				</form><hr>
			</div>
		</div><br>
		
		<div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold" uk-grid>
				<div class="uk-width-1-2 uk-text-left"><i class="fas fa-glass-cheers uk-margin-small-right"></i> Testimonios</div>
				<div class="uk-width-1-2 uk-text-right">
					<button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#testimony-modal" uk-tooltip="title: Crear Testimonio; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
						<i class="fas fa-plus-circle"></i> Nuevo Testimonio
					</button>
				</div>
            </div>              
	        <div class="uk-card-body"> 
				@php $cont = 2; @endphp
				@foreach ($evento->testimonies as $testimonio)
					@php $cont++; @endphp
	        		<form action="{{ route('admins.events.update') }}" method="POST" enctype="multipart/form-data" class="uk-grid-small" uk-grid> 
	                	@csrf
	                	<input type="hidden" name="testimony_id" value="{{ $testimonio->id }}">
						<div class="uk-width-1-1 uk-margin-small-bottom">
                        	Testimonio:
                        	<textarea name="text-{{$testimonio->id}}" rows="10">{{ $testimonio->text }}</textarea>
                        	<script>
			                    CKEDITOR.replace('text-{{$testimonio->id}}');   
			               	</script>
                    	</div><br>  
						<div class="uk-width-1-2 uk-margin-small-bottom">
							Autor del Testimonio:
							<input class="uk-input" type="text" name="autor" value="{{ $testimonio->autor }}">
						</div>
						<div class="uk-width-1-2 uk-margin-small-bottom">
							Imagen del Testimonio:
							<input class="uk-input" type="file" name="image">
							<div uk-lightbox><a href="{{ asset('uploads/events/testimonies/'.$testimonio->image) }}">Ver Imagen Actual</a></div>
							
						</div> 
						<div class="uk-width-1-1 uk-text-right">
							@if (Auth::user()->profile->events == 2)
								<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
							@else
								<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
							@endif 
						</div>
					</form><hr>
				@endforeach
			</div>
		</div><br>

		<div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold" uk-grid>
				<div class="uk-width-1-2 uk-text-left"><i class="fa fa-gift uk-margin-small-right"></i> Preventa</div>
				@if ($evento->presale == 1)
					<div class="uk-width-1-2 uk-text-right"><a href="{{ route('admins.events.delete-presale', $evento->id) }}" class="uk-button uk-button-grey uk-margin">Quitar Preventa</a></div>
				@endif
			</div>              
	        <div class="uk-card-body"> 
	        	<form action="{{ route('admins.events.add-presale') }}" method="POST" class="uk-grid-small" uk-grid> 
	                @csrf
	                <input type="hidden" name="event_id" value="{{ $evento->id }}">
					<div class="uk-width-1-3 uk-margin-small-bottom">
						Precio Preventa:
						<input class="uk-input" type="text" name="presale_price" value="{{ $evento->presale_price }}" required>
					</div>
					<div class="uk-width-1-3 uk-margin-small-bottom">
						Fecha Límite:
						<input class="uk-input" type="date" name="presale_date" @if ($evento->presale == 1) value="{{ date('Y-m-d', strtotime($evento->presale_datetime)) }}" @endif required>
					</div> 
					<div class="uk-width-1-3 uk-margin-small-bottom">
						Hora Límite:
						<input class="uk-input" type="time" name="presale_time" @if ($evento->presale == 1) value="{{ date('H:i', strtotime($evento->presale_datetime)) }}" @endif required>
					</div>
					<div class="uk-width-1-1 uk-text-right">
						@if (Auth::user()->profile->events == 2)
							<input class="uk-button uk-button-success uk-margin" type="submit" value="Guardar Cambios"> 
						@else
							<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
						@endif 
					</div>
				</form>
			</div>
		</div><br>

	    <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="far fa-file-image uk-margin-small-right"></i> Archivos Multimedia
            </div>              
	        <div class="uk-card-body"> 
	        	<table class="uk-table uk-table-middle uk-table-responsive">
					<thead style="background-color: #D8D7D7;">
						<tr>
							<th class="uk-text-center">Archivo</th>
							<th class="uk-text-center">Tipo</th>
							<th class="uk-text-center" colspan="2">Acción</th>
						</tr>
					</thead>
					<tbody id="content">
						<tr>
							<td class="uk-text-center">@if (!is_null($evento->image)) {{ $evento->image }} @else Sin Archivo @endif</td>
							<td class="uk-text-center">Portada Versión Escritorio</td>
							<td class="uk-text-right">
								<div uk-lightbox>
									<a href="{{ asset('uploads/events/images/'.$evento->image) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
								</div>
							</td>
							<td class="uk-text-left">
								<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile('pc');"></a>
							</td>
						</tr>
						<tr>
							<td class="uk-text-center">@if (!is_null($evento->image_movil)) {{ $evento->image_movil }} @else Sin Archivo @endif</td>
							<td class="uk-text-center">Portada Versión Móvil</td>
							<td class="uk-text-right">
								<div uk-lightbox>
									<a href="{{ asset('uploads/events/images/'.$evento->image_movil) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
								</div>
							</td>
							<td class="uk-text-left">
								<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile('movil');"></a>
							</td>
						</tr>
						<tr>
							<td class="uk-text-center">@if (!is_null($evento->video)) {{ $evento->video }} @else Sin Archivo @endif</td>
							<td class="uk-text-center">Video Trailer</td>
							<td class="uk-text-right">
								<a href="{{ asset('uploads/events/videos/'.$evento->video) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
							</td>
							<td class="uk-text-left">
								<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile('video');"></a>
							</td>
						</tr>
						<tr>
							<td class="uk-text-center">@if (!is_null($evento->benefits_img)) {{ $evento->benefits_img }} @else Sin Archivo @endif</td>
							<td class="uk-text-center">Imagen de Beneficios</td>
							<td class="uk-text-right">
								<div uk-lightbox>
									<a href="{{ asset('uploads/events/benefits/'.$evento->benefits_img) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
								</div>
							</td>
							<td class="uk-text-left">
								<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile('benefits');"></a>
							</td>
						</tr>
						<tr>
							<td class="uk-text-center">@if (!is_null($evento->informative_pdf)) {{ $evento->informative_pdf }} @else Sin Archivo @endif</td>
							<td class="uk-text-center">PDF Informativo</td>
							<td class="uk-text-right">
								<a href="{{ asset('uploads/events/documents/'.$evento->informative_pdf) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;" target="_blank"></a>
							</td>
							<td class="uk-text-left">
								<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile('pdf');"></a>
							</td>
						</tr>
						@foreach ($imagenes as $imagen)
							<tr>
								<td class="uk-text-center">{{ $imagen->image }}</td>
								<td class="uk-text-center">@if ($imagen->movil == 0) Imagen para Slider Versión Escritorio @else Imagen para Slider Versión Móvil @endif</td>
								<td class="uk-text-right">
									<div uk-lightbox>
										<a href="{{ asset('images/events/'.$imagen->image) }}" class="uk-icon-button uk-button-primary" uk-icon="icon: search;"></a>
									</div>
								</td>
								<td class="uk-text-left">
									<a href="javascript:;" class="uk-icon-button uk-button-danger delete-input" uk-icon="icon: move;" uk-tooltip="Cambiar" onclick="changeFile({{$imagen->id}});"></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
	        </div>
	    </div>
	</div>

	<!-- Modal para Cambiar un archivo -->                     
    <div id="load-file-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Cambiar Archivo Multimedia </h4> 
            </div>                    
            <form action="{{ route('admins.events.change-file') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <input type="hidden" name="event_id" value="{{ $evento->id }}">
                <input type="hidden" name="file_type" id="file_type">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Archivo:
                            <input class="uk-input" type="file" name="image" required>
                        </div>             
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Subir Archivo</button>
                </div>     
            </form>                        
        </div>                         
	</div>
	
	 <!-- Modal para Crear Testimonio -->                     
	 <div id="testimony-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Crear Nuevo Testimonio </h4> 
            </div>                    
            <form action="{{ route('admins.events.add-testimony') }}" method="POST" enctype="multipart/form-data" id="form-ultimo">   
				@csrf
				<input type="hidden" name="event_id" value="{{ $evento->id }}">
				<input type="hidden" name="event_slug" value="{{ $evento->slug }}">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Testimonio:
                            <textarea  class="uk-textarea" name="text" rows="5"></textarea>
                        </div><br>  
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Autor del Testimonio:
                            <input class="uk-input" type="text" name="autor" required>
                        </div> 
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Imagen del Autor del Testimonio:
                            <input class="uk-input" type="file" name="image" required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Testimonio</button>
                </div>     
            </form>                        
        </div>                         
	</div>
	
	<!-- Modal para cargar nueva imagen de mentores -->                     
    <div id="new-instructor-image" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Nueva Imagen de Mentor </h4> 
            </div>                    
            <form action="{{ route('admins.events.update') }}" method="POST" enctype="multipart/form-data">  
				@csrf
				<input type="hidden" name="mentor_image" value="1">
				<input type="hidden" name="event_id" value="{{ $evento->id }}">
				<input type="hidden" name="event_slug" value="{{ $evento->slug }}">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Imagen:
                            <input class="uk-input" type="file" name="image" required>
                        </div>             
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Cargar Imagen</button>
                </div>     
            </form>                        
        </div>                         
	</div>
@endsection