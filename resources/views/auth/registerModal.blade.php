<div class="modal fade" role="dialog" id="modal-register" >
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
					    <div class="col" style=" background: url(https://transformatepro.com/template/images/login.png); background-repeat: no-repeat; background-size: cover; background-position: center center;">
					      {{--  <img src="{{ asset('template/images/login.png') }}" >--}}
					    </div>
					    <div class="col">
					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button><br>
					    	<center>
	                			<img src="{{ asset('template/images/logo3.png') }}" style="width: 250px;"><br><br>
	                			<h5 class="uk-margin-small uk-text-muted uk-text-bold uk-text-nowrap"> Construye con los mejores tu ruta de transformación. </h5>   <hr>
	                	
	                			<a href="{{ url('/login/google') }}" class="btn btn-danger btn-sm"><i class="fab fa-google"></i> Regístrate con Google</a>
	                			{{--  <a href="{{ url('/login/facebook') }}" class="btn btn-primary btn-sm"><i class="fab fa-facebook"></i> Regístrate con Facebook</a>--}}
                				<hr>
                				
                				<h4>Regístrate con tu email</h4> 
					
	                			<form method="POST" action="{{ route('register') }}">
	                				{{ csrf_field() }}
	                				<div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-user"></i></div>
								        </div>
								        <input type="text" class="form-control" name="names" placeholder="Nombres" required>
								    </div>
								    <div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-user"></i></div>
								        </div>
								        <input type="text" class="form-control" name="last_names" placeholder="Apellidos" required>
								    </div>
	                				<div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-envelope"></i></div>
								        </div>
								        <input type="email" class="form-control" name="email" placeholder="Tu Nueva Cuenta de Correo" required>
								    </div>

								    <div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-lock"></i></div>
								        </div>
								        <input type="password" class="form-control" name="password" placeholder="Tu Contraseña" required>
								    </div>

								    <div class="form-group">
									    <label for="birthdate">¿Cuál es tu fecha de nacimiento?</label>
									    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
									</div>
									<div class="form-group" style="display: none;" id="div_edad">
								        <div class="alert alert-danger">Debes tener 16 años o más para registrarte.</div>
								    </div>

									<div class="form-group">
									    <label for="rol">¿Qué serás en Transfórmate?</label>
									    <select class="form-control" name="rol" id="rol" onchange="activarCodigo();">
      										<option value="1">Estudiante</option>
      										<option value="2">Mentor</option>
    									</select>
									</div>
									
									<div class="form-group" style="display: none;" id="div_codigo">
								        <input type="text" class="form-control" name="code" placeholder="Código de Afiliación">
								    </div>
									
									<button type="submit" class="btn btn-primary" id="btn-crear">Crear Cuenta</button>
	                			</form> <hr>
								
								<div class="form-group" style="font-size: 0.7em;">
									Al hacer clic en Crear Cuenta certifico que tengo 16 años o más y acepto las <a href="#">Condiciones de Uso</a>, la <a href="#">Política de Privacidad</a>, la <a href="#">Política de Cookies</a> y recibir novedades y promociones<hr>

									<span class="ti16">¿Ya tienes cuenta? <a href="#" onclick="desactivarModal('register');">Entrar</a></span>
	                			</div>
	                		</center>
					    </div>
  					</div>
				</div>
      		</div>
    	</div>
  	</div>
</div>

<!--Formulario de Comunidad-->
<div class="modal fade" role="dialog" id="form_community" >
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
  					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;Cerrar</span>
					        </button>
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSdbqVF3Mhtanl0QH_dLvPyn4_CaEh2lp8-RtzmrIGeFAcZzDQ/viewform?embedded=true" 
                width="900" height="750" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<!--Formulario de Tienda-->
<div class="modal fade" role="dialog" id="form_tienda" >
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
  					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;Cerrar</span>
					        </button>
                  <iframe src="https://docs.google.com/forms/d/e/1FAIpQLScefhAjtcfEShYl9paPBVbWpXtqwsenFZDrhF0pRW3qma6YVw/viewform?embedded=true" 
                  width="900" height="750" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<!--Formulario de Truta-->
<div class="modal fade" role="dialog" id="form_t_ruta" >
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
  					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;Cerrar</span>
					        </button>
                  <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfDQ-P6sqAr3vHm4QY4PUsUYBEs2C59unhNEUwXCfjDH3oW9A/viewform?embedded=true" 
                  width="900" height="750" frameborder="0" marginheight="0" marginwidth="0">Cargando…</iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>






<script>
	$(function() {
		$('#birthdate').on('change',function(){
			var hoy = new Date();
		    var cumpleanos = new Date(document.getElementById("birthdate").value);
		    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
		    var m = hoy.getMonth() - cumpleanos.getMonth();

		    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
		        edad--;
		    }

		    if (edad < 16){
		    	document.getElementById("div_edad").style.display = 'block';
		    	document.getElementById("btn-crear").disabled = true;
		    }else{
		    	document.getElementById("div_edad").style.display = 'none';
		    	document.getElementById("btn-crear").disabled = false;
		    }
		});
	});
	function activarCodigo(){
		if (document.getElementById("rol").value == "2"){
			document.getElementById("div_codigo").style.display = 'block';
		}else{
			document.getElementById("div_codigo").style.display = 'none';
		}
	}
</script>