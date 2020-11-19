@extends('layouts.admin')

@push('scripts')
	<script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
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

		<div class="uk-card-small uk-card-default"> 
			<form class="uk-form-horizontal uk-margin-large" action="{{ route('admins.master-class.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
		        <div class="uk-card-header uk-text-bold">
                    <i class="fa fa-plus-circle uk-margin-small-right"></i> Nueva Master Class
                </div>  

		        <div class="uk-card-body">
		        	<ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
			        	<li aria-expanded="true" class="uk-active">
				            <a href="#"> Datos Informativos</a>
				        </li>           
				    </ul>

				    <ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto uk-margin-top">
				    	<!--Datos Informativos -->
				    	<li class="uk-active">
				    		<br>
				    		<div class="uk-grid">
					    		<div class="uk-width-1-1">
					    			<label class="uk-form-label" for="title"><b>Título:</b></label>
						            <input class="uk-input" id="title" name="title" type="text" value="{{ old('title') }}"placeholder="Título"> 
					   			</div> 

					   			<div class="uk-width-1-1">
						        	<label class="uk-form-label" for="subtitle"><b>Subtítulo:</b></label>
						        	<input class="uk-input" id="subtitle" name="subtitle" type="text" value="{{ old('subtitle') }}"placeholder="Subtítulo" maxlength="100"> 
						    	</div>

                                <label class="uk-form-label" for="review"><b>Reseña</b></label><br>
                                <div class="uk-margin">
                                    <textarea class="ckeditor" name="review" id="review" rows="5">{{ old('review') }}</textarea>
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
				    </ul>
		        </div>	

		        <div class="uk-card-footer uk-text-right">
		        	<button type="submit" class="uk-button uk-button-danger">Crear T-Master Class</button>
		        </div>
	        </form>
	    </div>
	</div>
@endsection