@extends('layouts.instructor')

@section('content')
	<div class="uk-container" >
		<div class="uk-card-default">
	        <div class="uk-card-header uk-text-center">
	            Detalles de Ganancia
	        </div>  

	        <div class="uk-card-body" style="margin-left: 60px;">
	        	<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
				    <div>
				    	<strong>Datos de la Comisión</strong><hr>
						Id: <b>{{ $comision->id }}</b><br>
			            Monto: <b>COP$ {{ number_format($comision->amount, 0, ',', '.') }}</b><br>
			            Tipo: <b>{{ $comision->type }}</b><br>
			            Fecha: <b>{{ date('d-m-Y H:i A', strtotime("$comision->created_at -5 Hours")) }}</b><br>
			            @if ($comision->status == 0) 
			            	Status: <span class="uk-label uk-label-danger">Sin Cobrar</span> <br>
			            @else 
			            	Status: <span class="uk-label uk-label-success">Cobrada</span> <br>
			            @endif 
				    </div>

				    <div>
				    	<strong>Datos del Contenido</strong><hr>
						@if (!is_null($comision->purchase_detail_id))
							@if (!is_null($comision->purchase_detail->course_id)) 
								Tipo: <b>Curso </b><br>
								Título: <b>{{ $comision->purchase_detail->course->title }}</b>
							@elseif (!is_null($comision->purchase_detail->certification_id)) 
								Tipo: <b>Certificación </b><br>
								Título: <b>{{ $comision->purchase_detail->certification->title }}</b>
							@elseif (!is_null($comision->purchase_detail->podcast_id))  
								Tipo: <b>Podcast </b><br>
								Título: <b>{{ $comision->purchase_detail->podcast->title }}</b>
							@else
								Tipo: <b>Producto </b><br>
								Título: <b>{{ $comision->purchase_detail->market_product->name }}</b>
							@endif
						@else
							Tipo: <b>Evento</b><br>
							Título: <b>{{ $comision->event_subscription->event->title }}</b>
						@endif
				    </div>

				    <div>
						<strong>Datos del Referido</strong><hr>
						@if (!is_null($comision->purchase_detail_id))
			            	Nombre: <b>{{ $comision->referred->names }} {{ $comision->referred->last_names }}</b><br>
			            	País: <b>{{ $comision->referred->country }}</b><br>
			            	Ciudad: <b>{{ $comision->referred->city }}</b><br>
			            @else
			            	Nombre: <b>{{ $comision->event_subscription->names }}</b><br>
			            	País: <b>{{ $comision->event_subscription->country }}</b><br>
			            @endif
			            
				    </div>
				</div>
				<hr class="uk-divider-icon"> 
				<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
					<div>
						<strong>Datos del Pago</strong><hr>
			            @if (!is_null($comision->purchase_detail_id))
				            Forma de Pago: <b>{{ $comision->purchase_detail->purchase->payment_method }}</b><br>
				            Total Pagado: <b>{{ $comision->purchase_detail->amount }}</b><br>
				            Fecha: <b>{{ $comision->purchase_detail->date }}</b><br>
				        @else
							Forma de Pago: <b>{{ $comision->event_subscription->payment_method }}</b><br>
				            Total Pagado: <b>{{ $comision->event_subscription->payment_amount }}</b><br>
				            Fecha: <b>{{ $comision->event_subscription->payment_date }}</b><br>
				        @endif
				    </div>

				    <div>
						<strong>Datos del Cobro</strong><hr>
						@if ($comision->status == 2)
				            Forma de Pago: <b>{{ $comision->liquidation->payment_method }}</b><br>
				            Total Pagado: <b>{{ $comision->liquidation->amount }}</b><br>
				            Fecha: <b>{{ $comision->liquidation->date }}</b><br>
				        @elseif ($comision->status == 1)
				        	<span class="uk-label uk-label-warning">En Proceso</span> <br>
				        @else
				        	<span class="uk-label uk-label-danger">Sin Cobrar</span> <br>
				        @endif
				    </div>
				</div>
	       </div> 
	   </div>
	</div>
@endsection

 