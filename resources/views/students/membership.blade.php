@extends('layouts.student2')

@push('styles')
    <style>
        .hide{
            display: none;
        }
    </style>
@endpush

@section('content')
	<div class="uk-container uk-margin-medium-top" style="padding-top: 30px; padding-bottom: 30px;">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        @if (Session::has('msj-erroneo'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        @endif
	    <div class="uk-card-default">
			<div class="uk-card-header">
				<h3 class="uk-heading-line uk-text-center"><span>Mi Membresía Actual</span></h3>
			</div>        
			<div class="uk-card-body">
				<div class="uk-grid-divider" uk-grid>
					<div class="uk-width-1-3 uk-text-center">
						<img src="{{ asset('template/images/ico8.png/') }}" style="width: 250px;">
					</div>
					<div class="uk-width-1-3">
						<h4>Datos de la Membresía</h4>
						<h5>{{ Auth::user()->membership->name }}</h5>
						Tiempo de Duración: <b>{{ Auth::user()->membership->type }}</b><br>
                        Fecha de Vencimiento: <span @if ($renovacion == true) style="color: red;" @endif><b>{{ date('d-m-Y', strtotime(Auth::user()->membership_expiration)) }}</b></span> <br>
                        Estatus: @if ($membresiaVencida == true) 
                                    <label class="uk-label uk-label-danger">Vencida</label> 
                                 @else 
                                    @if ($renovacion == true) 
                                        <label class="uk-label uk-label-warning">Próxima a Vencerse</label>
                                    @else 
                                        <label class="uk-label uk-label-success">Activa</label> 
                                    @endif
                                @endif
					</div>
					<div class="uk-width-1-3">
                        @if (!is_null($datosPago))
    						<h4>Datos del Pago</h4>
                            Fecha de Compra: <b>{{ date('d-m-Y H:i A', strtotime("$datosPago->created_at -5 Hours")) }}</b><br> 
                            Forma de Pago: <b>{{ $datosPago->payment_method }}</b><br>
                            Id de Transacción: <b>{{ $datosPago->payment_id }}</b><br>
                            Total Pagado: <b>COP$ {{ number_format($datosPago->amount, 0, ',', '.') }}</b><br>
                        @else
                            <h4>Datos del Regalo</h4>
                            Fecha de Aplicación: <b>{{ date('d-m-Y H:i A', strtotime("$datosRegalo->updated_at -5 Hours")) }}</b><br> 
                            Código de Aplicación: <b>{{ $datosRegalo->gift_code }}</b>
                        @endif
					</div>
				</div>
			</div>                         
		</div>
    </div>
@endsection