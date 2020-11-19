<div class="modal fade" uk-modal role="dialog" id="modal-login">
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
					    <div class="col" style=" background: url(https://transformatepro.com/template/images/login.png); background-repeat: no-repeat; background-size: cover;">
					      {{-- <img src="{{ asset('template/images/login.png') }}" style="width: 350px; height: 380px;"> --}}
					    </div>
					    <div class="col">
					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button><br>
					    	<center>
	                			<img src="{{ asset('template/images/logo3.png') }}" style="width: 250px;"><br><br>
	                			<h5 class="uk-margin-small uk-text-muted uk-text-bold uk-text-nowrap"> Más que Educación Online,<br> una Comunidad para el desarrollo de tu ser </h5>   <hr>

	                			<a href="{{ url('/login/google') }}" class="btn btn-danger btn-sm"><i class="fab fa-google"></i> Iniciar con Google</a>
	                			{{--  <a href="{{ url('/login/facebook') }}" class="btn btn-primary btn-sm"><i class="fab fa-facebook"></i> Iniciar con Facebook</a>--}}
                				<hr>

	                			<b>Inicia Sesión</b>  <br><br>

	                			<form method="POST" action="{{ route('login') }}">
	                				{{ csrf_field() }}
	                				<div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-envelope"></i></div>
								        </div>
								        <input type="email" class="form-control" name="email" placeholder="Correo Electrónico" required>
								    </div>

								    <div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-lock"></i></div>
								        </div>
								        <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
								    </div>
									
									<button type="submit" class="btn btn-primary">Entrar</button>
	                			</form> <hr>

								<span style="font-size: 0.9em;">¿Olvidaste tu contraseña? <a href="#" onclick="desactivarModal('recover');">Recupérala</a></span><br>
								<span style="font-size: 0.9em;">¿No tienes cuenta? <a href="#" onclick="desactivarModal('login');">Regístrate</a></span>
	                		</center>
					    </div>
  					</div>
				</div>
      		</div>
    	</div>
  	</div>
</div>