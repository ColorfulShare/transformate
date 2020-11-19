@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
		@if (Session::has('msj-erroneo'))
	        <div class="uk-alert-danger" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-erroneo') }}</strong>
	        </div>
	    @endif             
        
        <div class="uk-margin-medium"> 
            <form action="{{ route('admins.orders.store') }}" method="POST"> 
            	@csrf 
            	<div uk-grid> 
                	<div class="uk-width-1-1"> 
                    	<div class="uk-card-small uk-card-default"> 
                        	<div class="uk-card-header uk-text-bold">
                           		<i class="fas fa-wallet uk-margin-small-right"></i> Datos del Pago
                       		 </div>     
                         
	                    	<div class="uk-card-body uk-padding-remove-top"> 
	                    		<div class="uk-grid">  
	                    			<div class="uk-width-1-1">
				                    	Usuario
				                    	<select class="uk-select" name="user_id" required>
			                        		<option value="" selected disabled>Seleccione una opción...</option>
			                        		@foreach ($estudiantes as $estudiante)
			                        			<option value="{{ $estudiante->id }}">{{ $estudiante->names }} {{ $estudiante->last_names }} ({{ $estudiante->email }})</option>
			                        		@endforeach
			                        	</select>
				                    </div>
		                        	
									<div class="uk-width-1-2">
		                            	# de Orden (Mercado Pago)
		                            	<input class="uk-input" type="number" name="payment_id" required>
		                            </div> 
									
		                            <div class="uk-width-1-2"> 
		                            	Monto Pagado                    
		                            	<input class="uk-input" type="text" name="amount" required> 
		                            </div>

		                            <div class="uk-width-1-2"> 
		                            	Código de Instructor Aplicado                   
			                            <select class="uk-select" name="instructor_code_discount">
			                            	<option value="1">Si</option>
			                            	<option value="0">No</option>
			                            </select>
			                       	</div> 

		                            <div class="uk-width-1-2"> 
		                            	Fecha                   
		                            	<input class="uk-input" type="date" name="date" required> 
		                            </div> 
		                        </div>
	                        </div>                         
                     	</div>                             
                	</div>                         
                
	                <div class="uk-width-1-1"> 
	                    <div class="uk-card-small uk-card-default"> 
	                        <div class="uk-card-header uk-text-bold"> 
	                            <i class="fas fa-video uk-margin-small-right"></i> Cursos Comprados
	                        </div>                                
		                    <div class="uk-card-body uk-padding-remove-top">  
		                        <div class="uk-child-width-1-2 uk-grid">
									@foreach ($cursos as $curso)
										<label><input class="uk-checkbox" type="checkbox" value="{{ $curso->id }}" name="cursos[]"> {{ $curso->title }}</label>
									@endforeach
								</div>  
		                    </div>                              
	                 	</div>                             
	               	</div>

	               	<div class="uk-width-1-1 uk-text-right">
						<input class="uk-button uk-button-grey uk-margin" type="submit" value="Generar Orden"> 
		            </div>              
            	</div> 
            </form>
        </div>                 
    </div>
@endsection