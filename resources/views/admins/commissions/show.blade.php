@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
		<div class="uk-card-default">
	        <div class="uk-card-header uk-text-center">
	            Detalles de Comisión
	        </div>  

	        <div class="uk-card-body" style="margin-left: 60px;">
	        	<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
				    <div>
				    	<strong>Datos de la Comisión</strong><hr>
						Id: <b>{{ $comision->id }}</b><br>
			            Monto: <b>{{ $comision->amount }}</b><br>
			            Tipo: <b>{{ $comision->type }}</b><br>
			            Fecha: <b>{{ date('d-m-Y', strtotime("$comision->date -5 Hours")) }}</b><br>
			            @if ($comision->status == 0) 
			            	Status: <span class="uk-label uk-label-danger">Sin Cobrar</span> <br>
			            @else 
			            	Status: <span class="uk-label uk-label-success">Cobrada</span> <br>
			            @endif 
				    </div>

				    <div>
				    	<strong>Datos del Contenido</strong><hr>
						@if (!is_null($comision->purchase_detail->course_id)) 
							Tipo: <b>Curso </b><br>
							Título: <b>{{ $comision->purchase_detail->course->title }}</b><br>
							Mentor: <b>{{ $comision->purchase_detail->course->user->names }} {{ $comision->purchase_detail->course->user->last_names }}</b>
						@elseif (!is_null($comision->purchase_detail->certification_id)) 
							Tipo: <b>Certificación </b><br>
							Título: <b>{{ $comision->purchase_detail->certification->title }}</b><br>
							Mentor: <b>{{ $comision->purchase_detail->certification->user->names }} {{ $comision->purchase_detail->certification->user->last_names }}</b>
						@elseif (!is_null($comision->purchase_detail->podcast_id)) 
							Tipo: <b>Podcast </b><br>
							Título: <b>{{ $comision->purchase_detail->podcast->title }}</b><br>
							Mentor: <b>{{ $comision->purchase_detail->podcast->user->names }} {{ $comision->purchase_detail->podcast->user->last_names }}</b>
						@else
							Tipo: <b>Producto </b><br>
							Título: <b>{{ $comision->purchase_detail->market_product->title }}</b><br>
							Mentor: <b>{{ $comision->purchase_detail->market_product->user->names }} {{ $comision->purchase_detail->market_product->user->last_names }}</b>
						@endif
				    </div>

				    <div>
						<strong>Datos del Cliente</strong><hr>
			            Nombre: <b>{{ $comision->user->names }} {{ $comision->user->last_names }}</b><br>
			            País: <b>{{ $comision->user->country }}</b><br>
			            Ciudad: <b>{{ $comision->user->city }}</b><br>
				    </div>
				</div>
				<hr class="uk-divider-icon"> 
				<div class="uk-grid-divider uk-child-width-expand@s" uk-grid>
					<div>
						<strong>Datos del Pago</strong><hr>
			            Forma de Pago: <b>{{ $comision->purchase_detail->purchase->payment_method }}</b><br>
			            Total Pagado: <b>{{ $comision->purchase_detail->amount }}</b><br>
			            Fecha: <b>{{ date('d-m-Y', strtotime("$comision->purchase_detail->date -5 Hours")) }}</b><br>
				    </div>

				    <div>
						<strong>Datos del Cobro</strong><hr>
						@if ($comision->status == 2)
				            Forma de Pago: <b>{{ $comision->liquidation->payment_method }}</b><br>
				            Total Pagado: <b>{{ $comision->liquidation->amount }}</b><br>
				            Fecha: <b>{{ date('d-m-Y', strtotime("$comision->liquidation->date -5 Hours")) }}</b><br>
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

 