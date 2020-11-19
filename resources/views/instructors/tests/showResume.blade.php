@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Resumen de Presentaciones</b>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b>Fecha</b></th>
	                            <th class="uk-table-small uk-text-center"><b>Alumno</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Puntuación</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Estatus</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Acción</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@if ($evaluacion->students_count > 0)
		                        @foreach ($presentaciones as $estudiante)
		                            <tr>
		                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$estudiante->pivot->date -5 Hours")) }}</td>
		                                <td class="uk-width-small uk-text-center">{{ $estudiante->names }} {{ $estudiante->last_names }}</td>
		                                <td class="uk-width-small uk-text-center">{{ $estudiante->pivot->score }}/100</td>
		                                <td class="uk-width-small uk-text-center">
		                                	@if ($estudiante->pivot->score >= 50)
		                                		<label class="uk-label uk-label-success">Aprobado</label>
		                                	@else
		                                		<label class="uk-label uk-label-danger">Reprobado</label>
		                                	@endif
		                                </td>
		                                <td class="uk-width-small uk-text-center">
		                                	<a class="uk-button uk-button-primary" href="{{ route('instructors.tests.show-result', [$evaluacion->slug, $estudiante->slug, $estudiante->pivot->id] ) }}">Ver Detalles</a>
		                                </td>
		                            </tr>    
		                        @endforeach
		                    @else
		                    	<tr>
		                    		<td colspan="5" class="uk-text-center">No existen presentaciones para esta evaluación aún...</td>
		                    	</tr>
		                    @endif
	                    </tbody>
	                </table>

	                {{ $presentaciones->links() }}
	            </div>
	       </div> 
	    </div>  
	</div>
@endsection