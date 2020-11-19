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
</script>

<div id="modal-register" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body" style="padding: 0px 0px;">
            <div class=" uk-child-width-1-1" uk-grid>
                <div style="padding: 0 0;">
                    <div class="uk-width-1-1 uk-text-center slogan-register">
                        <img src="{{ asset('template/images/logo3.png') }}" style="width: 60%;"><br>
                        <div style="padding-top: 15px; padding-bottom: 20px; font-size: 20px; color: gray; font-weight: bold;"> Construye con los mejores tu ruta de transformación.</div>

                        <a href="{{ url('/login/google') }}" class="login-with-google">
                            <i class="fab fa-google"></i> Regístrate con Gmail
                        </a> 
                    </div>
                    <div class="uk-width-1-1 email-login">
                        o regístrate con tu email<br>

                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input class="uk-input" type="text" name="names" placeholder="Nombres" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input class="uk-input" type="text" name="last_names" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                    <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                    <input class="uk-input" type="password" name="password" placeholder="Contraseña" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1 uk-text-center">
                                    {!! htmlFormSnippet() !!}
                                </div>
                            </div>
                            {{--<div class="uk-margin">
                                ¿Cuál es tu fecha de nacimiento?
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: calendar"></span>
                                    <input class="uk-input" type="date" name="birthdate" id="birthdate" required>
                                </div>
                            </div>
                            <div class="uk-margin" style="display: none;" id="div_edad">
                                <div class="uk-alert uk-alert-danger">Debes tener 16 años o más para registrarte.</div>
                            </div>--}}
                            <div class="uk-margin uk-text-center">
                                <input type="submit" class="login-button" value="Crear Cuenta" id="btn-crear">
                            </div>
                        </form>

                        <div class="uk-child-width-1-1" uk-grid>
                            <div class="uk-text-center">
                                ¿Ya tienes cuenta? <a href="#modal-login" uk-toggle>Entrar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>