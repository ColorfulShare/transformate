@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Historial de Compras

	            <div class="uk-align-right" style="color: green;">Cantidad de Compras: {{ $cantCompras }}</div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b>Fecha</b></th>
	                            <th class="uk-table-shrink uk-text-center"><b>Alumno</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Código</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Cupón de Descuento</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Acción</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach ($compras as $compra)
	                            <tr>
	                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$compra->created_at -5 Hours")) }}</td>
	                                <td class="uk-width-small uk-text-center">{{ $compra->purchase->user->names }} {{ $compra->purchase->user->last_names}} ({{ $compra->purchase->user->country }})</td>
	                                <td class="uk-width-small uk-text-center">COP$ {{ $compra->amount }}</td>
	                                <td class="uk-width-small uk-text-center">
	                                    @if ($compra->instructor_code == 0)
	                                        <span class="uk-label uk-label-danger">No</span>
	                                    @else
	                                        <span class="uk-label uk-label-success">Si</span>
	                                    @endif
	                                </td>
	                                <td class="uk-width-small uk-text-center">
	                                    @if (is_null($compra->purchase->coupon_id))
	                                        <span class="uk-label uk-label-danger">No</span>
	                                    @else
	                                        <span class="uk-label uk-label-success">Si ({{ $compra->purchase->coupon->discount}}%)</span> 
	                                    @endif
	                                </td>
	                                <td class="uk-width-small uk-text-center"><a class="uk-button uk-button-success" href="{{ route('instructors.commissions.show', $compra->commission->id ) }}">Ver Comisión</a></td>
	                            </tr>    
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $compras->links() }}
	       </div>
	    </div>  
	</div>
@endsection