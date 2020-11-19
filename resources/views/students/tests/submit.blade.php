@extends('layouts.student2')

@push('scripts')
	<script>
		function marcarPregunta($pregunta){
			if (document.getElementById("check-"+$pregunta).value == 0){
				$preguntasResp = parseInt(document.getElementById("preguntas_respondidas").value);
				document.getElementById("preguntas_respondidas").value = $preguntasResp + 1;
				document.getElementById("check-"+$pregunta).value = 1;
				document.getElementById("li-"+$pregunta).innerHTML = '<a href="#">Pregunta #'+$pregunta+' <i class="fa fa-check" style="color: green"></i></a>';
			}
			
			if (document.getElementById("preguntas_respondidas").value >= document.getElementById("cant_preguntas").value){
				document.getElementById("btn-enviar").disabled = false;
			}else{
				document.getElementById("btn-enviar").disabled = true;
			}
		}
	</script>
@endpush
@section('content') 
	<div class="uk-container uk-margin-medium-bottom padding-top"> 
		<h4><i class="fas fa-file-signature"></i> Presentar Evaluación</h4>
		<div class="uk-card-default">
			<input type="hidden" id="preguntas_respondidas" value="0">
	       	<form action="{{ route('students.tests.save') }}" method="POST">
	            @csrf
	            <input type="hidden" name="test_id" value="{{ $evaluacion->id }}">
	        	<input type="hidden" name="cant_preguntas" id="cant_preguntas" value="{{ $evaluacion->questions_count }}">
		        <div class="uk-card-header">
		            <h6>
		                @if (!is_null($evaluacion->module->course_id)) <u>T-Course:</u> {{ $evaluacion->module->course->title }} @else <u>T-Mentoring:</u> {{ $evaluacion->module->certification->title }} @endif <br>
		                <u>Módulo:</u> {{ $evaluacion->module->title }}<br>
		                <u>Evaluación:</u> {{ $evaluacion->title }}<br>
		                <u>Descripción:</u> {!! $evaluacion->description !!}
		            </h6>
		        </div>  
		                
		        <div class="uk-card-body uk-padding-remove-top">
		            <ul class="uk-tab uk-margin-remove-top uk-background-default" uk-switcher>
					    @foreach ($evaluacion->questions as $pregunta)
						    <li aria-expanded="true" id="li-{{ $pregunta->order }}">
							    <a href="#">Pregunta N°{{ $pregunta->order }}</a>
							</li>  
						@endforeach            
					</ul>
					<ul class="uk-switcher uk-margin uk-margin-medium-left">
						@php
							$i = 0;
						@endphp
						@foreach ($evaluacion->questions as $pregunta)
							@php
								$i++;
							@endphp
							<li>
								<input type="hidden" id="check-{{ $pregunta->order }}" value="0">
					            <input type="hidden" name="pregunta-{{ $pregunta->order }}" value="{{ $pregunta->id }}">
					            <input type="hidden" name="respuesta-{{ $pregunta->order }}" value="{{ $pregunta->correct_answer }}">
								<div class="uk-grid">
		                            <div class="uk-width-1-1">
		                                {{ $pregunta->question }}
		                            </div>
		                                
		                            <div class="uk-margin uk-width-1-1 uk-grid">
						            	<label><input class="uk-radio" type="radio" name="seleccion-{{ $pregunta->order }}" value="1" onclick="marcarPregunta({{ $pregunta->order }});" required> {{ $pregunta->possible_answer_1 }}</label>
						            </div>
						            <div class="uk-margin uk-width-1-1 uk-grid">
						            	<label><input class="uk-radio" type="radio" name="seleccion-{{ $pregunta->order }}" value="2" onclick="marcarPregunta({{ $pregunta->order }});" required> {{ $pregunta->possible_answer_2 }}</label>
						            </div>
						            <div class="uk-margin uk-width-1-1 uk-grid">
						            	<label><input class="uk-radio" type="radio" name="seleccion-{{ $pregunta->order }}" value="3" onclick="marcarPregunta({{ $pregunta->order }});" required> {{ $pregunta->possible_answer_3 }}</label>
						            </div>
						            <div class="uk-margin uk-width-1-1 uk-grid">
						            	<label><input class="uk-radio" type="radio" name="seleccion-{{ $pregunta->order }}" value="4" onclick="marcarPregunta({{ $pregunta->order }});" required> {{ $pregunta->possible_answer_4 }}</label>
						            </div>
									
									<div class="uk-width-1-1">
										<hr>
									</div>
						            @if ($evaluacion->questions_count == 1)
                                        <div class="uk-width-1-1">
                                            <br>
                                            <input type="submit" class="uk-button uk-button-danger uk-align-right" id="btn-enviar" value="Enviar Evaluación" disabled>
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

                                            @if ($i != $evaluacion->questions_count)
                                                <div class="uk-width-1-2">
                                                    <br>
                                                    <a href="#" class="uk-button uk-button-primary uk-align-right" uk-switcher-item="next">Siguiente Pregunta</a>
                                                </div>
                                            @else
                                                <div class="uk-width-1-2">
                                                    <br>
                                                    <input type="submit" class="uk-button uk-button-danger uk-align-right" id="btn-enviar" value="Enviar Evaluación" disabled>
                                                </div>
                                            @endif
                                        @endif
                                    @endif
		                        </div>
							</li>
						@endforeach
					</ul>
		        </div>
	        </form>
	    </div>
	</div>
@endsection