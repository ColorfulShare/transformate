@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
		@if (Session::has('msj-exitoso'))
	        <div class="uk-alert-success" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-exitoso') }}</strong>
	        </div>
	    @endif

        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Cuenta Bancaria
            </div>              
	        <div class="uk-card-body"> 
	            <div uk-grid> 
	                <div class="uk-width-1-3@m uk-text-center"> 
	                    <img src="{{ asset('template/images/iconobanco.png') }}" style="width: 80%;">
	                </div>                             
	                <div class="uk-width-2-3@m"> 
	                    <form action="{{ route('admins.bank.update') }}" method="POST" class="uk-grid-small" uk-grid> 
	                    	@csrf
	                    	<input type="hidden" name="id" value="{{ $cuenta->id }}">
	                        <div class="uk-width-1-1"> 
	                            <div class="uk-form-label">Nombre del Banco</div>        
	                            <input class="uk-input" type="text" name="bank" placeholder="Nombre del Banco" value="{{ $cuenta->bank }}" required> 
	                        </div>                                     
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Razón Social</div>
	                            <input class="uk-input" type="text"  name="business_name" placeholder="Razón Social" value="{{ $cuenta->business_name }}" required> 
	                        </div>                  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">N° de Identificación</div>        
	                            <input class="uk-input" type="text" name="identification" value="{{ $cuenta->identification }}"> 
	                        </div>  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Tipo de Cuenta</div>        
	                            <select class="uk-input" name="account_type" required> 
	                                <option value="Ahorro" @if ($cuenta->account_type == 'Ahorro') selected @endif>Ahorro</option>
	                				<option value="Corriente" @if ($cuenta->account_type == 'Corriente') selected @endif>Corriente</option>
	                            </select>
	                        </div> 
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">N° de Cuenta</div>        
	                            <input class="uk-input" type="text" name="account_number" placeholder="Número de Cuenta" value="{{ $cuenta->account_number }}" required> 
	                        </div>     <hr>
							<div class="uk-width-1-1 uk-text-right">
								@if (Auth::user()->profile->banks == 2)
									<input class="uk-button uk-button-grey uk-margin" type="submit" class="button" value="Guardar Cambios"> 
								@else
									<input class="uk-button uk-button-default uk-margin" class="button" value="Guardar Cambios" disabled>
								@endif 
							</div>
	                    </form>                                 
	                </div>                             
	            </div>                         
	        </div>              
        </div>          
    </div>
@endsection