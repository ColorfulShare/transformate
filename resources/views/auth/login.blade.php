<div id="modal-login" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Entrar</h4>
        </div>
        <div class="uk-modal-body" style="padding: 0px 0px;">
            <div class="uk-width-1-1 social-login">
                <a href="{{ url('/login/google') }}" class="login-with-google">
                    <i class="fab fa-google"></i> Iniciar con Gmail
                </a>
            </div>
            <div class="uk-width-1-1 email-login">
                o entra con tu email<br>

                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: mail"></span>
                            <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico" required="">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input" type="password" name="password" placeholder="Contraseña" required="">
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1 uk-text-center">
                            {!! htmlFormSnippet() !!}
                        </div>
                    </div>
                    <div class="uk-child-width-1-1" uk-grid>
                        <div class="uk-text-center">
                            <input type="submit" class="login-button" value="Entrar">
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-center" style="background-color: #f2f2f2;">
            <a href="#modal-recover" uk-toggle>¿Olvidaste tu Contraseña?</a><br>
            ¿No tienes cuenta? <a href="#modal-register" uk-toggle>Crear Cuenta</a>
        </div>
    </div>
</div>