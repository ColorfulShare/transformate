<div class="modal fade" role="dialog" id="modal-recover">
  	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="container">
  					<div class="row">
					    <div class="col">
					      <img src="{{ asset('template/images/login.png') }}" style="width: 350px; height: 380px;">
					    </div>
					    <div class="col">
					    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          	<span aria-hidden="true">&times;</span>
					        </button><br>
					    	<center>
	                			<img src="{{ asset('template/images/logo3.png') }}" style="width: 250px;"><br><br>
	                			<h5 class="uk-margin-small uk-text-muted uk-text-bold uk-text-nowrap"> El lugar donde puedes aprender lo que sea. </h5>   <hr>

	                			<b>Recuperar Clave</b>  <br><br>

	                			<form method="POST" action="{{ route('landin.recover-password') }}">
	                				{{ csrf_field() }}
	                				Por favor, ingresa tu correo electrónico asociado<br><br>
	                				<div class="input-group mb-2">
        								<div class="input-group-prepend">
								          	<div class="input-group-text"><i class="fa fa-envelope"></i></div>
								        </div>
								        <input type="email" class="form-control" name="email" placeholder="Correo Electrónico" required>
								    </div><hr>
									
									<button type="submit" class="btn btn-primary">Enviar Clave</button>
	                			</form> 
	                		</center>
					    </div>
  					</div>
				</div>
      		</div>
    	</div>
  	</div>
</div>