@extends('layouts.admin')

@push('scripts')
	<script>
		$(function() {
			$("#selectall").on("click", function() {  
			  	$(".case").prop("checked", this.checked);  
			});  

			// if all checkbox are selected, check the selectall checkbox and viceversa  
			$(".case").on("click", function() { 
			  	if ($(".case").length == $(".case:checked").length) {  
			    	$("#selectall").prop("checked", true);  
			  	} else {  
			    	$("#selectall").prop("checked", false);  
			  	}  
			});
		});

		function copiar() {
		  	// Crea un campo de texto "oculto"
		  	var aux = document.createElement("input");
  			// Asigna el contenido del elemento especificado al valor del campo
  			aux.setAttribute("value", document.getElementById("code").value);
  			// Añade el campo a la página
  			document.body.appendChild(aux);
			// Selecciona el contenido del campo
			aux.select();
			// Copia el texto seleccionado
			document.execCommand("copy");
			// Elimina el campo de la página
			document.body.removeChild(aux);
		}
	</script>
@endpush

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
                <i class="fas fa-tag uk-margin-small-right"></i> Detalles de Cupón
            </div>              
	        <div class="uk-card-body"> 
	            <div uk-grid>                      
	                <div> 
	                    <form action="{{ route('admins.coupons.update') }}" method="POST" class="uk-grid-small" uk-grid> 
	                    	@csrf
	                    	<input type="hidden" name="coupon_id" value="{{ $cupon->id }}">
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Nombre: (*)</div>        
	                            <input class="uk-input" type="text" name="name" placeholder="Nombre del Cupón" value="{{ $cupon->name }}"> 
	                        </div>    
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Código </div>
	                            <div class="uk-inline">
                                    <a class="uk-form-icon uk-form-icon-flip" uk-tooltip="Copiar Código" onclick="copiar();"><i class="fa fa-copy"></i></a>
                                    <input class="uk-input uk-form-width-large" type="text" id="code"  value="{{ $cupon->code }}" disabled>
                               </div> 
	                        </div>                   
	                        <div class="uk-width-1-4"> 
	                            <div class="uk-form-label">Descuento (*)</div>
	                            <input class="uk-input" type="number" name="discount" min="1" max="100" value="{{ $cupon->discount }}"> 
	                        </div>                  
	                        <div class="uk-width-1-4"> 
	                            <div class="uk-form-label">Límite de Aplicaciones (*)</div>        
	                            <input class="uk-input" type="number" name="limit" value="{{ $cupon->limit }}"> 
	                        </div>  
	                        <div class="uk-width-1-4"> 
	                            <div class="uk-form-label">Cantidad de Aplicaciones</div>        
	                            <input class="uk-input" type="number" name="applciations" value="{{ $cupon->applications }}" disabled> 
	                        </div>
	                         <div class="uk-width-1-4"> 
	                            <div class="uk-form-label">Estatus (*)</div>
	                           <select class="uk-select" name="status">
	                           		<option value="0" @if ($cupon->status == 0) selected @endif>Inactivo</option>
	                           		<option value="1" @if ($cupon->status == 1) selected @endif>Activo</option>
	                           </select>
	                        </div>
							<div class="uk-width-1-1">
		                        Categorías Aplicables (*):<br>
		                        <div class="uk-child-width-1-4" uk-grid>
		                            <div>
									    <label><input class="uk-checkbox" type="checkbox" name="all_categories" id="selectall" value="Todas"> <i class="fas fa-globe"></i> Todas</label>
									</div>
									@foreach ($categorias as $categoria)
									    <div>
									    	<label><input class="uk-checkbox case" type="checkbox" name="categories[]" value="{{ $categoria->id }}" @if (in_array($categoria->id, $categoriasDisponibles)) checked @endif> <i class="{{ $categoria->icon }}"></i> {{ $categoria->title }}</label>
									    </div>
									@endforeach
								</div>
		                    </div>  
							<div class="uk-width-1-1 uk-text-right">
								@if (Auth::user()->profile->coupons == 2)
									<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
								@else
									<input class="uk-button uk-button-default uk-margin" value="Guardar Cambios" disabled> 
								@endif
							</div>
	                    </form>                                 
	                </div>                             
	            </div>                         
	        </div>              
        </div>      
    </div>
@endsection