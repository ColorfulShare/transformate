@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Mis Referidos

	            <div class="uk-align-right" style="color: green;">Cantidad de Referidos: {{ $cantReferidos }}</div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-table-shrink uk-text-center"><b>Avatar</b></th>
	                            <th class="uk-table-shrink uk-text-center"><b>Nombre</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Ubicación</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Miembro Desde</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@if ($cantReferidos > 0)
		                        @foreach ($referidos as $referido)
		                            <tr>
		                                <td class="uk-table-shrink uk-text-center">
		                                	<img class="uk-border-circle" src="{{ asset('uploads/images/users/'.$referido->avatar) }}" style="width: 50%;">
		                                </td>
		                                <td class="uk-width-small uk-text-center">{{ $referido->names }} {{ $referido->last_names }}</td>
		                                <td class="uk-width-small uk-text-center">{{ $referido->state }} ({{ $referido->country}})</td>
		                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$referido->created_at -5 Hours")) }}</td>
		                                </td>
		                            </tr>    
		                        @endforeach
		                    @else 
		                    	<tr>
		                    		<td colspan="6" class="uk-text-center">Usted no posee ningún referido aún...</td>
		                    	</tr>
		                    @endif
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $referidos->links() }}
	       </div>
	    </div>  
	</div>
@endsection