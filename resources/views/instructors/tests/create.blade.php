@extends('layouts.instructor')

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    <div class="uk-container uk-margin-medium-top"> 
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif
        
        <h4><i class="fas fa-file-signature"></i> Nueva Evaluación</h4>
	    <div class="uk-card-default">
            <form method="POST" action="{{ route('instructors.tests.store') }}">
                @csrf
                <input type="hidden" name="module_id" value="{{ $datosModulo->id }}">
                <input type="hidden" name="cant_preguntas" value="{{ $cantPreguntas }}">

                <div class="uk-card-header">
                    <h6>
                        @if (!is_null($datosModulo->course_id)) <u>T-Course:</u> {{ $datosModulo->course->title }} @else <u>T-Mentoring:</u> {{ $datosModulo->certification->title }} @endif <br> <br>
                        <u>Módulo:</u> {{ $datosModulo->title }} (#{{ $datosModulo->priority_order }})
                    </h6>
                </div>  
                
                <div class="uk-card-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Título de la Evaluación
                            <input class="uk-input" type="text" name="title" required>
                        </div>
                        <div class="uk-width-1-1">
                            Descripción de la Evaluación
                            <textarea class="ckeditor" name="description"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '.ckeditor' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                    </div><br>

                    <ul class="uk-tab uk-margin-remove-top uk-background-default uk-sticky uk-margin-left uk-margin-right" uk-sticky="animation: uk-animation-slide-top; bottom: #sticky-on-scroll-up ; media: @s;" uk-switcher>
			        	@for ($i = 1; $i <= $cantPreguntas; $i++)
				        	<li aria-expanded="true">
					        	<a href="#">Pregunta N°{{ $i }}</a>
					    	</li>  
					    @endfor            
					</ul>
					<ul class="uk-switcher uk-margin uk-container-small uk-margin-auto">
						@for ($i = 1; $i <= $cantPreguntas; $i++)
							<li>
								<div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        Título de la Pregunta
                                        <input class="uk-input" type="text" name="pregunta-{{ $i }}" required>
                                    </div>
                                    <div class="uk-width-1-1">
                                        Respuesta 1
                                        <input class="uk-input" type="text" name="respuesta-1-{{ $i }}" required>
                                    </div>
                                    <div class="uk-width-1-1">
                                        Respuesta 2
                                        <input class="uk-input" type="text" name="respuesta-2-{{ $i }}" required>
                                    </div>
                                    <div class="uk-width-1-1">
                                        Respuesta 3
                                        <input class="uk-input" type="text" name="respuesta-3-{{ $i }}" required>
                                    </div>
                                    <div class="uk-width-1-1">
                                        Respuesta 4
                                        <input class="uk-input" type="text" name="respuesta-4-{{ $i }}" required>
                                    </div>
                                    <div class="uk-width-1-1">
                                        Respuesta Correcta
                                        <select class="uk-select" name="respuesta_correcta_{{ $i }}" required>
                                            <option value="1">Respuesta 1</option>
                                            <option value="2">Respuesta 2</option>
                                            <option value="3">Respuesta 3</option>
                                            <option value="4">Respuesta 4</option>
                                        </select> 
                                    </div>
                                    
                                    @if ($cantPreguntas == 1)
                                        <div class="uk-width-1-1">
                                            <br>
                                            <input type="submit" class="uk-button uk-button-danger uk-align-right" value="Guardar Evaluación">
                                        </div>
                                    @else
                                        @if ($i == 1)
                                            <div class="uk-width-1-1">
                                                <br>
                                                <a href="#" class="uk-button uk-button-primary uk-align-right" uk-switcher-item="next">Siguiente Pregunta</a>
                                            </div>
                                        @elseif ($i != 1)
                                            <div class="uk-width-1-2">
                                                <br>
                                                <a href="#" class="uk-button uk-button-primary uk-align-left" uk-switcher-item="previous">Pregunta Anterior</a>
                                            </div>

                                            @if ($i != $cantPreguntas)
                                                <div class="uk-width-1-2">
                                                    <br>
                                                    <a href="#" class="uk-button uk-button-primary uk-align-right" uk-switcher-item="next">Siguiente Pregunta</a>
                                                </div>
                                            @else
                                                <div class="uk-width-1-2">
                                                    <br>
                                                    <input type="submit" class="uk-button uk-button-danger uk-align-right" value="Guardar Evaluación">
                                                </div>
                                            @endif
                                        @endif
                                    @endif
                                </div>
							</li>
						@endfor
					</ul>
                </div>
            </form>
       </div>
    </div>
@endsection