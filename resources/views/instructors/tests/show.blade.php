@extends('layouts.instructor')

@section('content')
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
                <a class="uk-button uk-button-primary uk-align-right" href="{{ route('instructors.tests.show-resume', [$evaluacion->slug, $evaluacion->id]) }}"><i class="fa fa-file-alt"></i> Ver Presentaciones</a>
            </div> 

	        <div class="uk-card-body">
	        	<form method="POST" action="{{ route('instructors.tests.update') }}">
					@csrf
	                <input type="hidden" name="test_id" value="{{ $evaluacion->id }}">

	                <div class="uk-grid">
						<div class="uk-width-1-1">
                            Título: <input class="uk-input" type="text" name="title" value="{{ $evaluacion->title }}" required>
						</div>
						<div class="uk-width-1-1">
                            Descripción: <textarea class="uk-textarea" name="description" rows="5" placeholder="Ingrese la descripción de la evaluación...">{{ $evaluacion->description }}</textarea>
						</div>
						<div class="uk-width-1-1 uk-text-center">
							<br>
                            <button type="submit" class="uk-button uk-button-danger">Guardar Cambios</button>
                        </div>
					</div>
				</form>
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
						 	<form method="POST" action="{{ route('instructors.tests.update-question') }}">
								@csrf
					            <input type="hidden" name="question_id" value="{{ $pregunta->id }}">

					            <div class="uk-grid">
				                    <div class="uk-width-1-1">
				                        Título
				                        <input class="uk-input" type="text" name="question" value="{{ $pregunta->question }}" required>
				                    </div>
				                        
				                    <div class="uk-width-1-1">
				                        Respuesta 1
				                        <input class="uk-input" type="text" name="possible_answer_1" value="{{ $pregunta->possible_answer_1 }}" required>
				                    </div>

				                    <div class="uk-width-1-1">
				                        Respuesta 2
				                        <input class="uk-input" type="text" name="possible_answer_2" value="{{ $pregunta->possible_answer_2 }}" required>
				                    </div>

				                    <div class="uk-width-1-1">
				                        Respuesta 3
				                        <input class="uk-input" type="text" name="possible_answer_3" value="{{ $pregunta->possible_answer_3 }}" required>
				                    </div>

				                    <div class="uk-width-1-1">
				                        Respuesta 4
				                        <input class="uk-input" type="text" name="possible_answer_4" value="{{ $pregunta->possible_answer_4 }}" required>
				                    </div>
				                                        
				                    <div class="uk-width-1-1">
				                        Respuesta Correcta
				                        <select class="uk-select" name="correct_answer" required>
				                            <option value="1" @if ($pregunta->correct_answer == 1) selected @endif>Respuesta 1</option>
				                            <option value="2" @if ($pregunta->correct_answer == 2) selected @endif>Respuesta 2</option>
				                            <option value="3" @if ($pregunta->correct_answer == 3) selected @endif>Respuesta 3</option>
				                            <option value="4" @if ($pregunta->correct_answer == 4) selected @endif>Respuesta 4</option>
				                        </select> 
				                    </div>
				                    <div class="uk-width-1-1 uk-text-center">
										<br>
				                        <button type="submit" class="uk-button uk-button-success"><i class="fa fa-check"></i> Guardar Cambios</button>
				                        <a class="uk-button uk-button-danger" href="{{ route('instructors.tests.delete-question', $pregunta->id) }}"><i class="fa fa-times"></i> Eliminar Pregunta</a>
				                    </div>
				                </div>
					        </form>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
    </div>
@endsection