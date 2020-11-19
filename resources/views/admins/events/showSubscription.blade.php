@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
		<div class="uk-card-default">
	        <div class="uk-card-header uk-text-center">
	            Detalles del Suscriptor
	        </div>  

	        <div class="uk-card-body" style="margin-left: 60px;">
	        	<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
				    <div>
				    	<strong>Datos del Suscriptor</strong><hr>
			            Nombre: <b>{{ $suscripcion->names }}</b><br>
			            Correo: <b>{{ $suscripcion->email }}</b><br>
			            Teléfono: <b>{{ $suscripcion->phone }}</b><br>
			            País: <b>{{ $suscripcion->country }}</b><br>
			            Edad: <b>{{ $suscripcion->age }}</b><br>
						Profesión: <b>{{ $suscripcion->profession }}</b><br>
						¿Cómo se enteró del T-Event?: <b>{{ $suscripcion->reason }}</b><br>
			            @if ($suscripcion->gift == 0)
			            	Fecha de Suscripción: <b>{{ date('d-m-Y H:i A', strtotime("$suscripcion->created_at -5 Hours")) }}</b>
			            @else
			            	@if ($suscripcion->gift_status == 1)
			            		Fecha de Suscripción: <b>{{ date('d-m-Y H:i A', strtotime("$suscripcion->updated_at -5 Hours")) }}</b>
			            	@endif
			            @endif<br><br>
			            @if ($suscripcion->gift == 1)
				    		<strong>INSCRIPCIÓN POR T-GIFT</strong><br>
				    		Comprador Original: <b>{{ $suscripcion->gift_buyer }}</b><br>
				    		Código T-GIFT: <b>{{ $suscripcion->gift_code }}</b><br>
				    		Estado de la T-GIFT: <b>@if ($suscripcion->gift_status == 1) Aplicada @else Sin Aplicar @endif
				    	@endif
				    </div>
					
					@if ($suscripcion->event->type == 'pay')
					    <div>
					    	<strong>Datos del Pago</strong><hr>
					    	Estado del Pago: <b>@if ($suscripcion->status == 0) Pendiente @elseif ($suscripcion->status == 1) Aprobado @else Rechazado @endif </b><br>
							Método de Pago: <b>{{ $suscripcion->payment_method }} </b><br>
							ID de Pago: <b>{{ $suscripcion->payment_id }} </b><br>
							Fecha de Pago: @if (!is_null($suscripcion->payment_date)) <b>{{ date('d-m-Y', strtotime($suscripcion->payment_date)) }} </b> @endif<br>
							Código T-Mentor: <b>@if (!is_null($suscripcion->instructor_code)) SI ({{$suscripcion->instructor->names }} {{$suscripcion->instructor->last_names }}) @else NO @endif </b><br>
							Código T-Partner: <b>@if (!is_null($suscripcion->partner_code)) SI ({{$suscripcion->partner->names }} {{$suscripcion->partner->last_names }}) @else NO @endif </b><br>
					    </div>
					@endif
				</div>
	       </div> 
	   </div>
	</div>
@endsection

 