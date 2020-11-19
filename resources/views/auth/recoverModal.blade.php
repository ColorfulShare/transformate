<div id="modal-recover" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Recuperar Contraseña</h4>
        </div>
        <div class="uk-modal-body">
        	<form method="POST" action="{{ route('landing.recover-password') }}">
	            {{ csrf_field() }}
	            <div class="uk-text-center recover-text">Por favor, ingresa tu correo electrónico asociado</div>

	            <div class="uk-margin">
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico" required>
                    </div>
                </div>
                <div class="uk-margin uk-text-center">
                    <input type="submit" class="login-button" value="Enviar Clave Temporal">
                </div>
	        </form>
        </div>
    </div>
</div>