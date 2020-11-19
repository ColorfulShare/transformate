@extends('layouts.partner')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Mis Ganancias

	            <div class="uk-align-right" style="color: green;">Saldo Disponible: {{ Auth::user()->balance }}$</div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b>Fecha</b></th>
	                            <th class="uk-table-shrink uk-text-center"><b>Referido</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Tipo de Compra</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Estado</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Acción</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach ($ganancias as $ganancia)
	                            <tr>
	                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$ganancia->created_at -5 Hours")) }}</td>
	                                <td class="uk-text-center">{{ $ganancia->referred->names }} {{ $ganancia->referred->last_names }}</td>
	                                <td class="uk-text-center">COP$ {{ number_format($ganancia->amount, 0, ',', '.') }} </td> 
	                                <td class="uk-text-center">@if (!is_null($ganancia->purchase_detail_id)) Compra Interna @else Compra T-Events @endif</td>
	                                <td class="uk-text-center">
	                                    @if ($ganancia->status == 0)
	                                        <span class="uk-label uk-label-danger">Sin Cobrar</span>
	                                    @elseif ($ganancia->status == 1)
	                                        <span class="uk-label uk-label-warning">En Proceso</span>
	                                    @else
	                                        <span class="uk-label uk-label-success">Cobrada</span>
	                                    @endif
	                                </td>
	                                <td class="uk-width-small uk-text-center"><a href="{{ route('partners.commissions.show', $ganancia->id) }}" class="uk-icon-button uk-button-primary btn-icon" uk-tooltip="Ver Detalles"><i class="fa fa-search"></i></a></td>
	                            </tr>    
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $ganancias->links() }}
	       </div>
	    </div>  
	</div>
@endsection