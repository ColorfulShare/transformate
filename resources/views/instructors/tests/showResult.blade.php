@extends('layouts.instructor')

@section('content')  
	<div class="uk-container">
		<div class="uk-card-default">
		   	<div class="uk-card-header uk-text-center">
		        <h4>Detalles de Presentación</h4>
		    </div> 

		    <div class="uk-card-body">
				<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
					<div>
						Datos de la Evaluación<hr>
						Evaluación: <b>{{ $datosEvaluacion->title }}</b><br>
						Módulo: <b>{{ $datosEvaluacion->module->title }}</b><br>
						@if (!is_null($datosEvaluacion->module->course_id)) 
							Curso: <b>{{ $datosEvaluacion->module->course->title }}</b>
						@elseif (!is_null($datosEvaluacion->module->certification_id)) 
							Certificación: <b>{{ $datosEvaluacion->module->certification->title }}</b>
						@endif
					</div>
					<div>
						Datos del Estudiante<hr>
						Nombres: <b>{{ $datosEstudiante->names }} {{ $datosEstudiante->last_names }}</b><br>
						Ubicación: <b>{{ $datosEstudiante->state }} ({{ $datosEstudiante->country }})</b><br>
						Profesión: <b>{{ $datosEstudiante->profession }}</b>
					</div>
					<div>
						Datos de la Presentación<hr>
						Fecha: <b>{{ date('d-m-Y', strtotime("$datosPresentacion->date -5 Hours")) }} </b><br>
						Puntuación: <b>{{ $datosPresentacion->score }}/100</b><br>
						Status:	@if ($datosPresentacion->score >= 50)
		                            <label class="uk-label uk-label-success">Aprobado</label>
		                        @else
		                            <label class="uk-label uk-label-danger">Reprobado</label>
		                        @endif
					</div>
				</div>
			</div>
		</div>
    </div><hr>

    <div class="uk-container">
		<div class="uk-card-default">
		   	<div class="uk-card-header uk-text-center">
		        <h4>Respuestas</h4>
		    </div> 

		    <div class="uk-card-body">
		    	<div uk-grid>
				<div class="uk-width-auto@m">
				    <ul class="uk-tab-left" uk-tab="connect: #component-tab-left; animation: uk-animation-fade">
				        @foreach ($respuestas as $pregunta)
				            <li><a href="#">Pregunta #{{ $pregunta->order }} @if ($pregunta->correct_answer == $pregunta->selected_answer) <i class="fa fa-check" style="color: green"></i> @else <i class="fa fa-times" style="color: red"></i> @endif</a></li>
				        @endforeach
				    </ul>
				</div>
				<div class="uk-width-expand@m">
				    <ul id="component-tab-left" class="uk-switcher">
				        @foreach ($respuestas as $respuesta)
				            <li>
				            	<p class="uk-text-center">{{ $respuesta->question }}</p>
									
								<div class="uk-margin uk-width-1-1 uk-grid">
				            		<label>
				            			<input class="uk-radio" type="radio" @if ($respuesta->selected_answer == 1) checked @endif disabled> 
				            			@if ( ($respuesta->selected_answer == 1) && ($respuesta->correct_answer != 1) ) 
				            				<strike>{{ $respuesta->possible_answer_1 }}</strike> 
				            			@else 
				            				{{ $respuesta->possible_answer_1 }} 
				            			@endif
				            		</label>
				            	</div>
				            	<div class="uk-margin uk-width-1-1 uk-grid">
				            		<label>
				            			<input class="uk-radio" type="radio" @if ($respuesta->selected_answer == 2) checked @endif disabled> 
				            			@if ( ($respuesta->selected_answer == 2) && ($respuesta->correct_answer != 2) ) 
				            				<strike>{{ $respuesta->possible_answer_2 }}</strike> 
				            			@else 
				            				{{ $respuesta->possible_answer_2 }} 
				            			@endif
				            		</label>
				            	</div>
				            	<div class="uk-margin uk-width-1-1 uk-grid">
				            		<label>
				            			<input class="uk-radio" type="radio" @if ($respuesta->selected_answer == 3) checked @endif disabled> 
				            			@if ( ($respuesta->selected_answer == 3) && ($respuesta->correct_answer != 3) ) 
				            				<strike>{{ $respuesta->possible_answer_3 }}</strike> 
				            			@else 
				            				{{ $respuesta->possible_answer_3 }} 
				            			@endif
				            		</label>
				            	</div>
				            	<div class="uk-margin uk-width-1-1 uk-grid">
				            		<label>
				            			<input class="uk-radio" type="radio" @if ($respuesta->selected_answer == 4) checked @endif disabled> 
				            			@if ( ($respuesta->selected_answer == 4) && ($respuesta->correct_answer != 4) ) 
				            				<strike>{{ $respuesta->possible_answer_4 }}</strike> 
				            			@else 
				            				{{ $respuesta->possible_answer_4 }} 
				            			@endif
				            		</label>
				            	</div>
				            </li>
				        @endforeach
				    </ul>
				</div>
			</div>
		    </div>
		</div>
	</div>
@endsection