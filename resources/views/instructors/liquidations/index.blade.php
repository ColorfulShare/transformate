@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
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

        <div uk-grid style="padding: 25px;">
        	<div class="uk-width-1-2 uk-text-center" style="background-color: #41a8c4; color: white; font-size: 24px;">
	            Saldo Disponible T-Courses<br> <span style="font-weight: bold;">COP$ {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
	        </div>
	        <div class="uk-width-1-2 uk-text-center" style="background-color: #6E12FF; color: white; font-size: 24px;">
	            Saldo Disponible T-Events<br> <span style="font-weight: bold;">COP$ {{ number_format(Auth::user()->event_balance, 0, ',', '.') }}</span>
	        </div> 
        </div>

		<div class="uk-card-default" style="margin-top: 10px;">
	        <div class="uk-card-header">
	            Mis Retiros

	            <div class="uk-align-right uk-text-right">
	            	@if ( (Auth::user()->balance > 0) || (Auth::user()->event_balance > 0) )
	            		<a class="uk-button uk-button-success" href="#liquidation-modal" uk-toggle><i class="fas fa-arrow-alt-circle-right"></i> Solicitar Retiro</a>
	            	@endif
	            </div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b>Fecha de Solicitud</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Forma de Pago</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Fecha de Procesamiento</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Estado</b></th>
	                            <th class="uk-width-small uk-text-center">Generada Por</th>
	                            <th class="uk-width-small uk-text-center"><b>Acción</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach ($retiros as $retiro)
	                            <tr>
	                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$retiro->created_at -5 Hours")) }}</td>
	                                <td class="uk-width-small uk-text-center">COP$ {{ number_format($retiro->amount, 0, ',', '.') }}</td>
	                                <td class="uk-width-small uk-text-center">@if ($retiro->status == 0) - @else {{ $retiro->payment_method }} @endif</td>
	                                <td class="uk-text-center">@if ($retiro->status == 0) - @else {{ date('d-m-Y', strtotime("$retiro->processed_at -5 Hours")) }} @endif</td>
	                                <td class="uk-width-small uk-text-center">
	                                    @if ($retiro->status == 0)
	                                        <span class="uk-label uk-label-danger">En Proceso</span>
	                                    @else
	                                        <span class="uk-label uk-label-success">Procesada</span>
	                                    @endif
	                                </td>
	                                <td class="uk-text-center">
	                                    @if (is_null($retiro->admin))
	                                        Mentor
	                                    @else
	                                        Administrador
	                                    @endif
	                                </td>
	                                <td class="uk-width-small uk-text-center"><a class="uk-button uk-button-success" href="{{ route('instructors.liquidations.show', $retiro->id) }}">Detalles</a></td>
	                            </tr>    
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $retiros->links() }}
	       </div>
	    </div>  
	</div>

	<!-- Modal para Solicitar un Retiro-->                    
    <div id="liquidation-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Solicitar Retiro</h4> 
            </div>                    
            <form action="{{ route('instructors.liquidations.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                    	<div class="uk-width-1-1">
                            Billetera a Retirar (*):
                           	<select class="uk-select" name="wallet">
                           		<option value="T-Courses">T-Courses</option>
                           		<option value="T-Events">T-Events</option>
                           	</select>
                        </div>
                        <div class="uk-width-1-1">
                            Monto a Retirar (*):
                            <input class="uk-input" type="text" name="amount" required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Solicitar</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection