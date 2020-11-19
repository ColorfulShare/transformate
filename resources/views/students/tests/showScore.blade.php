@extends('layouts.student2')

@section('content') 
	<div class="uk-container uk-margin-medium-bottom padding-top"> 
		<h4><i class="fas fa-file-signature"></i> Resumen de Evaluación</h4>
		<div class="uk-card-default">
		    <div class="uk-card-header">
		        <h6>
		            @if (!is_null($evaluacion->module->course_id)) <u>T-Course:</u> {{ $evaluacion->module->course->title }} @else <u>T-Mentoring:</u> {{ $evaluacion->module->certification->title }} @endif <br>
		            <u>Módulo:</u> {{ $evaluacion->module->title }}<br>
		            <u>Evaluación:</u> {{ $evaluacion->title }}<br>
		            <u>Descripción:</u> {!! $evaluacion->description !!}<br>
		            <u>Fecha de Presentación:</u> {{ date('d-m-Y', strtotime("$datosEvaluacion->date -5 Hours")) }}<br>
		            <u>Puntaje Obtenido:</u> {{ $datosEvaluacion->score }}%
		        </h6>
		    </div>  
		                
		    <div class="uk-card-body uk-padding-remove-top">
		        <ul class="uk-tab uk-margin-remove-top uk-background-default" uk-switcher>
					@foreach ($evaluacion->questions as $pregunta)
						<li aria-expanded="true">
							<a href="#">Pregunta N°{{ $pregunta->order }}  @if ($pregunta->correct_answer == $pregunta->selected_answer) <i class="fa fa-check" style="color: green"></i> @else <i class="fa fa-times" style="color: red"></i> @endif</a>
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
							<div class="uk-grid">
		                        <div class="uk-width-1-1">
		                            {{ $pregunta->question }}
		                        </div>
		                                
		                        <div class="uk-margin uk-width-1-1 uk-grid">
						            <label>
						            	<input class="uk-radio" type="radio"  @if ($pregunta->selected_answer == 1) checked @endif disabled>
						            	@if ( ($pregunta->selected_answer == 1) && ($pregunta->correct_answer != 1) ) 
				            				<strike>{{ $pregunta->possible_answer_1 }}</strike> 
				            			@else 
				            				{{ $pregunta->possible_answer_1 }} 
				            			@endif
						            </label>
						        </div>
						       <div class="uk-margin uk-width-1-1 uk-grid">
						            <label>
						            	<input class="uk-radio" type="radio"  @if ($pregunta->selected_answer == 2) checked @endif disabled>
						            	@if ( ($pregunta->selected_answer == 2) && ($pregunta->correct_answer != 2) ) 
				            				<strike>{{ $pregunta->possible_answer_2 }}</strike> 
				            			@else 
				            				{{ $pregunta->possible_answer_2 }} 
				            			@endif
						            </label>
						        </div>
						       	<div class="uk-margin uk-width-1-1 uk-grid">
						            <label>
						            	<input class="uk-radio" type="radio"  @if ($pregunta->selected_answer == 3) checked @endif disabled>
						            	@if ( ($pregunta->selected_answer == 3) && ($pregunta->correct_answer != 3) ) 
				            				<strike>{{ $pregunta->possible_answer_3 }}</strike> 
				            			@else 
				            				{{ $pregunta->possible_answer_3 }} 
				            			@endif
						            </label>
						        </div>
						        <div class="uk-margin uk-width-1-1 uk-grid">
						            <label>
						            	<input class="uk-radio" type="radio"  @if ($pregunta->selected_answer == 4) checked @endif disabled>
						            	@if ( ($pregunta->selected_answer == 4) && ($pregunta->correct_answer != 4) ) 
				            				<strike>{{ $pregunta->possible_answer_4 }}</strike> 
				            			@else 
				            				{{ $pregunta->possible_answer_4 }} 
				            			@endif
						            </label>
						       	</div>
									
								<div class="uk-width-1-1">
									<hr>
								</div>
						            @if ($evaluacion->questions_count > 1)
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