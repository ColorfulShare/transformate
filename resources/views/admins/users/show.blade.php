@extends('layouts.admin')

@push('scripts')
	<script>
		function verificarCorreo(){
            $correo = document.getElementById("email").value; 

            var route = "https://transformatepro.com/ajax/verificar-correo/"+$correo;
           // var route = "http://localhost:8000/ajax/verificar-correo/"+$correo;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    if (ans == 1){
                        document.getElementById("div_error_correo").innerHTML = 'El correo ingresado ya se encuentra registrado.';
                        document.getElementById("div_error_correo").style.display = 'block';
                        document.getElementById("email2").disabled = true;
                        document.getElementById("btn-correo").disabled = true;
                    }else{
                        document.getElementById("div_error_correo").style.display = 'none';
                        document.getElementById("email2").disabled = false;
                        document.getElementById("email2").value = "";
                        document.getElementById("btn-correo").disabled = false;
                    }
                }
            });
        }

        function verificarCorreo2(){
            if (document.getElementById("email").value == document.getElementById("email2").value){
                document.getElementById("div_error_correo").style.display = 'none';
                document.getElementById("btn-correo").disabled = false;
            }else{
                document.getElementById("div_error_correo").innerHTML = 'Los correos ingresados no coinciden.';
                document.getElementById("div_error_correo").style.display = 'block';
                document.getElementById("btn-correo").disabled = true;
            }
        }

        function verificarClaves(){
            if (document.getElementById("password").value == document.getElementById("password2").value){
                document.getElementById("div_error_clave").style.display = 'none';
                document.getElementById("btn-clave").disabled = false;
            }else{
                document.getElementById("div_error_clave").innerHTML = 'Las contraseñas ingresadas no coinciden.';
                document.getElementById("div_error_clave").style.display = 'block';
                document.getElementById("btn-clave").disabled = true;
            }
        }

        function addSponsor(){
            var modal = UIkit.modal("#sponsor-modal");
            modal.show(); 
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

	    @if (Session::has('msj-erroneo'))
	        <div class="uk-alert-danger" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-erroneo') }}</strong>
	        </div>
	    @endif


        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Detalles de Usuario
            </div>              
	        <div class="uk-card-body"> 
	            <div uk-grid> 
	                <div class="uk-width-1-3@m uk-text-center"> 
	                        <img src="{{ asset('uploads/images/users/'.$usuario->avatar) }}" class="uk-border-circle" style="width: 300px;">
  
	                </div>                             
	                <div class="uk-width-2-3@m"> 
	                    <form action="{{ route('admins.users.update') }}" method="POST" class="uk-grid-small" uk-grid> 
	                    	@csrf
	                    	<input type="hidden" name="user_id" value="{{ $usuario->id }}">
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Nombres</div>        
	                            <input class="uk-input" type="text" name="names" placeholder="Nombres" value="{{ $usuario->names }}"> 
	                        </div>                                     
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Apellidos</div>
	                            <input class="uk-input" type="text"  name="last_names" placeholder="Apellidos" value="{{ $usuario->last_names }}"> 
	                        </div>                  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Fecha de Nacimiento</div>        
	                            <input class="uk-input" type="date" name="birthdate" value="{{ $usuario->birthdate }}"> 
	                        </div>  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Género</div>        
	                            <select class="uk-input" name="gender"> 
	                                <option value="" @if (is_null($usuario->gender)) selected @endif disabled>Seleccione una opción...</option>
	                                <option value="F" @if ($usuario->gender == 'F') selected @endif>Femenino</option>
	                				<option value="M" @if ($usuario->gender == 'M') selected @endif>Masculino</option>
	                            </select>
	                        </div> 
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">País</div>        
	                            <input class="uk-input" type="text" name="country" placeholder="País" value="{{ $usuario->country }}"> 
	                        </div>                                     
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Estado</div>
	                            <input class="uk-input" type="text"  name="state" placeholder="Ciudad" value="{{ $usuario->state }}"> 
	                        </div>   
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Nombre de Usuario</div>
	                            <input class="uk-input" type="text" name="username" placeholder="Nombre de Usuario" value="{{ $usuario->username }}"> 
	                        </div>                                                    
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Teléfono</div>
	                            <input class="uk-input" type="text" name="phone" placeholder="Teléfono" value="{{ $usuario->phone }}"> 
	                        </div>    
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Profesión</div>
	                            <input class="uk-input" type="text" name="profession" placeholder="Profesión" value="{{ $usuario->profession }}"> 
	                        </div>    
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Membresía</div>
	                            <input class="uk-input" type="text" name="profession" placeholder="Profesión" @if (is_null($usuario->membership_id)) value="Sin Membresía" @else value="{{ $usuario->membership->name }}" @endif disabled> 
	                        </div> 
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Facebook</div>
	                            <input class="uk-input" type="text" name="facebook" placeholder="Facebook" value="{{ $usuario->facebook }}"> 
	                        </div>  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Twitter</div>
	                            <input class="uk-input" type="text" name="twitter" placeholder="Twitter" value="{{ $usuario->twitter }}"> 
	                        </div>  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Instagram</div>
	                            <input class="uk-input" type="text" name="instagram" placeholder="Instagram" value="{{ $usuario->instagram }}"> 
	                        </div>  
	                        <div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Pinterest</div>
	                            <input class="uk-input" type="text" name="pinterest" placeholder="Pinterest" value="{{ $usuario->pinterest }}"> 
	                        </div>  
	                        <div class="uk-width-1-1"> 
	                            <div class="uk-form-label">Canal de Youtube</div>
	                            <input class="uk-input" type="url" name="youtube" placeholder="Canal de Youtube" value="{{ $usuario->youtube }}"> 
	                        </div> 
	                          
	                       	<div class="uk-width-1-2"> 
	                            <div class="uk-form-label">Código de Afiliación</div>
	                            <input class="uk-input" type="text" value="{{ $usuario->afiliate_code }}" disabled> 
	                        </div>     
	                        @if ( ($usuario->role_id == 2) || ($usuario->role_id == 4) )
		                        <div class="uk-width-1-2"> 
			                        <div class="uk-form-label">Mentor y/o Partner que lo refirió</div>
			                        <input class="uk-input" type="text" @if (is_null($usuario->sponsor_id)) value="No Posee" @else value="{{ $usuario->sponsor->names }} {{ $usuario->sponsor->last_names }} (Id = {{ $usuario->sponsor_id }})" @endif disabled> 
			                        @if (is_null($usuario->sponsor_id))
			                        	<a href="#sponsor-modal" uk-toggle>Asignar Patrocinador</a>
			                        @endif
			                    </div> 
			                @endif
		                    @if ($usuario->role_id == 3)
		                    	<div class="uk-width-1-1">
		                            Perfil Administrativo (*):
		                            <select class="uk-select" name="profile_id" required>
		                                @foreach ($perfiles as $perfil)
		                                    <option value="{{ $perfil->id }}" @if ($usuario->profile_id == $perfil->id) selected @endif>{{ $perfil->name }}</option>
		                                @endforeach
		                            </select>
		                        </div>
		                    @endif
	                        <div class="uk-width-1-1"> 
	                            <div class="uk-form-label">Información Personal</div>
	                            <textarea class="uk-textarea" rows="5" name="review" placeholder="Información Personal">{{ $usuario->review }}</textarea>
	                        </div>
							<div class="uk-width-1-1 uk-text-right">
								@if (Auth::user()->profile->users == 2)
									<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
								@else
									<input class="uk-button uk-button-default uk-margin"  value="Guardar Cambios" disabled>
								@endif 
							</div>
	                    </form>                                 
	                </div>                             
	            </div>                         
	        </div>              
        </div>                 
        
        <div class="uk-margin-medium"> 
            <div uk-grid> 
                <div class="uk-width-1-2@m"> 
                    <div class="uk-card-small uk-card-default"> 
                        <div class="uk-card-header uk-text-bold">
                            <i class="fas fa-envelope uk-margin-small-right"></i> Cambiar Correo Electrónico
                        </div>     
                        <form action="{{ route('admins.users.update') }}" method="POST"> 
	                    	@csrf
	                    	<input type="hidden" name="user_id" value="{{ $usuario->id }}">   

	                    	<div class="uk-card-body uk-padding-remove-top">   
	                    		<div class="uk-alert-danger" uk-alert id="div_error_correo" style="display: none;"></div> 
	                        	<div class="uk-form-label">Correo Actual</div>
	                        	<input class="uk-input" type="text" value="{{ $usuario->email }}" disabled> 

	                            <div class="uk-form-label">Nuevo Correo</div> 
	                            <input class="uk-input" type="email" name="email" id="email" onkeyup="verificarCorreo();" required> 

	                            <div class="uk-form-label"> Confirme el nuevo correo</div>                    
	                            <input class="uk-input" type="email" id="email2" onkeyup="verificarCorreo2();" required> 
	                        </div>  
	                        <div class="uk-card-footer uk-text-right">
	                        	@if (Auth::user()->profile->users == 2)
									<input class="uk-button uk-button-grey uk-margin" type="submit" id="btn-correo" value="Cambiar Correo"> 
								@else
									<input class="uk-button uk-button-default uk-margin" value="Cambiar Correo" disabled>
								@endif 
	                        </div>    
	                    </form>                           
                     </div>                             
                </div>                         
                
                <div class="uk-width-1-2@m"> 
                    <div class="uk-card-small uk-card-default"> 
                        <div class="uk-card-header uk-text-bold"> 
                            <i class="fas fa-lock uk-margin-small-right"></i> Cambiar Contraseña 
                        </div>      
                        <form action="{{ route('admins.users.update') }}" method="POST"> 
	                    	@csrf
	                    	<input type="hidden" name="user_id" value="{{ $usuario->id }}">                           
	                        <div class="uk-card-body uk-padding-remove-top"> 
	                        	<div class="uk-alert-danger" uk-alert id="div_error_clave" style="display: none;"></div> 
	                            <div class="uk-form-label"> Nueva Contraseña</div>
	                            <input class="uk-input" type="password" name="password" id="password" onkeyup="verificarClaves();" required> 
	                                    
	                            <div class="uk-form-label"> Confirme la nueva contraseña</div>
	                            <input class="uk-input" type="password" id="password2" onkeyup="verificarClaves();" required> 
	                        </div>   
	                        <div class="uk-card-footer uk-text-right">
	                        	@if (Auth::user()->profile->users == 2)
									<input class="uk-button uk-button-grey uk-margin" type="submit" id="btn-clave" value="Cambiar Contraseña"> 
								@else
									<input class="uk-button uk-button-default uk-margin" value="Cambiar Contraseña" disabled>
								@endif 
	                        </div> 
	                    </form>                               
                    </div>                             
                </div>                         
            </div> 
        </div>                 
    </div>

    <!-- Modal para Asignar Patrocinador -->                     
    <div id="sponsor-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4>Asignar Patrocinador</h4> 
            </div>                    
            <form action="{{ route('admins.users.add-sponsor') }}" method="POST">  
                @csrf
                <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                    	<div class="uk-width-1-1"> 
			                <div class="uk-form-label">Ingrese el correo del Mentor o Partner que lo refirió</div>
			                <input class="uk-input" type="email" name="sponsor_email" required> 
			            </div> 
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Asignar</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection