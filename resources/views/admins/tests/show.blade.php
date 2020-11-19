@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
	    <div class="uk-container uk-margin-medium-top"> 
	        @if (Session::has('msj-exitoso'))
	            <div class="uk-alert-success" uk-alert>
	                <a class="uk-alert-close" uk-close></a>
	                <strong>{{ Session::get('msj-exitoso') }}</strong>
	            </div>
	        @endif

	        <div class="uk-card-default"> 
				<div class="uk-card-header">
	                <h4 class="uk-align-left">Detalles de Evaluación</h4>
	            </div> 

		        <div class="uk-card-body">
		            <div class="uk-grid">
						<div class="uk-width-1-1">
	                       <b>Título:</b> {{ $evaluacion->title }}
						</div>
						<div class="uk-width-1-1">
	                       <b>Descripción:</b> {!! $evaluacion->description !!}
						</div>
					</div>
					<hr>

					<ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-tab>
	                    @foreach ($evaluacion->questions as $pregunta)
							<li aria-expanded="true">
								<a href="#"> Pregunta {{ $pregunta->order }}</a>
							</li>  
					    @endforeach              
					</ul>

					<ul class="uk-switcher uk-margin uk-padding-small uk-container-small uk-margin-auto uk-margin-top">
						@foreach ($evaluacion->questions as $pregunta)
							<li>
							 	<h4><center>Pregunta N° {{ $pregunta->order }}</center></h4>
						        <div class="uk-grid">
					                <div class="uk-width-1-1">
					                    Título
					                    <input class="uk-input" type="text" value="{{ $pregunta->question }}" disabled>
					                </div>
					                        
					                <div class="uk-width-1-1">
					                    Respuesta 1
					                    <input class="uk-input" type="text" value="{{ $pregunta->possible_answer_1 }}" disabled>
					                </div>

					                <div class="uk-width-1-1">
					                    Respuesta 2
					                    <input class="uk-input" type="text" value="{{ $pregunta->possible_answer_2 }}" disabled>
					                </div>

					                <div class="uk-width-1-1">
					                    Respuesta 3
					                    <input class="uk-input" type="text" value="{{ $pregunta->possible_answer_3 }}" disabled>
					                </div>

					                <div class="uk-width-1-1">
					                    Respuesta 4
					                    <input class="uk-input" type="text" value="{{ $pregunta->possible_answer_4 }}" disabled>
					                </div>
					                                        
					                <div class="uk-width-1-1">
					                    Respuesta Correcta
					                    <input class="uk-input" type="text" value="{{ $pregunta->correct_answer }}" disabled>
					                </div>
					            </div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
	    </div>
	</div>
@endsection