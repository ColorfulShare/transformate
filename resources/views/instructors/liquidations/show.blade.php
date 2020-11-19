@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Detalles de Liquidación

	            <div class="uk-align-right" style="color: green;">Liquidación #{{ $liquidacion }}</div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b># Comisión</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Fecha de Compra</b></th>
	                            <th class="uk-text-center"><b>Contenido</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto de Comisión</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto de Compra</b></th>
	                            <th class="uk-text-center"><b>Tipo de Compra</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach ($comisiones as $comision)
	                            <tr>
	                            	<td class="uk-text-center">{{ $comision->id }}</td>
	                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$comision->date -5 Hours")) }}</td>
	                                <td class="uk-text-center">
	                                	@if (!is_null($comision->purchase_detail))
		                                	@if (!is_null($comision->purchase_detail->course_id))
		                                		{{ $comision->purchase_detail->course->title }} (T-Course)
		                                	@elseif (!is_null($comision->purchase_detail->podcast_id))
		                                		{{ $comision->purchase_detail->podcast->title }} (T-Book)
		                                	@endif
		                                @else
		                                	{{ $comision->event_subscription->event->title }} (T-Event)
		                                @endif
	                                </td>
	                                <td class="uk-text-center">COP$ {{ number_format($comision->amount, 0, ',', '.') }}</td>
	                                <td class="uk-text-center">
	                                	@if (!is_null($comision->purchase_detail))
	                                		COP$ {{ number_format($comision->purchase_detail->amount, 0, ',', '.') }}
	                                	@else
	                                		COP$ {{ number_format($comision->event_subscription->payment_amount, 0, ',', '.') }}
	                                	@endif
	                                </td>
	                                <td class="uk-text-center">{{ $comision->type }}</td>
	                            </tr>    
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $comisiones->links() }}
	       </div>
	    </div>  
	</div>
@endsection