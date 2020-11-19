@extends('layouts.student2')

@push('scripts')
    <script>
        function cambiarTab($id){
            if ($id == 1){
                document.getElementById("div_perfil").style.display = 'block';
                document.getElementById("div_avatar").style.display = 'none';
                document.getElementById("div_cuenta").style.display = 'none';
                document.getElementById("div_pagos").style.display = 'none';
                document.getElementById("div_cerrar_cuenta").style.display = 'none';
            }else if ($id == 2){
                document.getElementById("div_perfil").style.display = 'none';
                document.getElementById("div_avatar").style.display = 'block';
                document.getElementById("div_cuenta").style.display = 'none';
                document.getElementById("div_pagos").style.display = 'none';
                document.getElementById("div_cerrar_cuenta").style.display = 'none';
            }else if ($id == 3){
                document.getElementById("div_perfil").style.display = 'none';
                document.getElementById("div_avatar").style.display = 'none';
                document.getElementById("div_cuenta").style.display = 'block';
                document.getElementById("div_pagos").style.display = 'none';
                document.getElementById("div_cerrar_cuenta").style.display = 'none';
            }else if ($id == 4){
                document.getElementById("div_perfil").style.display = 'none';
                document.getElementById("div_avatar").style.display = 'none';
                document.getElementById("div_cuenta").style.display = 'none';
                document.getElementById("div_pagos").style.display = 'block';
                document.getElementById("div_cerrar_cuenta").style.display = 'none';
            }else if ($id == 5){
                document.getElementById("div_perfil").style.display = 'none';
                document.getElementById("div_avatar").style.display = 'none';
                document.getElementById("div_cuenta").style.display = 'none';
                document.getElementById("div_pagos").style.display = 'none';
                document.getElementById("div_cerrar_cuenta").style.display = 'block';
            }
        }

        function verificarCorreo(){
            $correo = document.getElementById("email").value;

            var route = "https://transformatepro.com/ajax/verificar-correo/"+$correo;
            //var route = "http://transformate.test/ajax/verificar-correo/"+$correo;

            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    if (ans == 1){
                        document.getElementById("div_error_correo").innerHTML = 'El correo ingresado ya se encuentra registrado.';
                        document.getElementById("div_error_correo").style.display = 'block';
                        document.getElementById("email2").disabled = true;
                        document.getElementById("btn-guardar").disabled = true;
                    }else{
                        document.getElementById("div_error_correo").style.display = 'none';
                        document.getElementById("email2").disabled = false;
                        document.getElementById("btn-guardar").disabled = false;
                    }
                }
            });
        }

        function verificarCorreo2(){
            if (document.getElementById("email").value == document.getElementById("email2").value){
                document.getElementById("div_error_correo").style.display = 'none';
                document.getElementById("btn-guardar").disabled = false;
            }else{
                document.getElementById("div_error_correo").innerHTML = 'Los correos ingresados no coinciden.';
                document.getElementById("div_error_correo").style.display = 'block';
                document.getElementById("btn-guardar").disabled = true;
            }
        }

        function verificarClaves(){
            if (document.getElementById("clave").value == document.getElementById("clave2").value){
                document.getElementById("div_error_clave").style.display = 'none';
                document.getElementById("btn-cambiar-clave").disabled = false;
            }else{
                document.getElementById("div_error_clave").innerHTML = 'Las contraseñas ingresadas no coinciden.';
                document.getElementById("div_error_clave").style.display = 'block';
                document.getElementById("btn-cambiar-clave").disabled = true;
            }
        }

    </script>
@endpush

@push('styles')
<style>
    .paddin-content{
        padding: 10px;
    }

    .form-input{
        border: 1px solid #e2e2e2;
        border-radius: 0;
    }
    .bg-content{
        background: #f1f1f1;
    }
    .mt-content{
        margin-top: 25px;
    }
</style>
@endpush

@section('content')
    <div class="bg-content">
        <div class="uk-container">
            @if (Session::has('msj-exitoso'))
                <div class="uk-alert-success" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-exitoso') }}</strong>
                </div>
            @endif

            @if (Session::has('msj-error'))
                <script>
                    $(function() {
                        document.getElementById("div_error_clave").innerHTML = 'La clave actual ingresada es incorrecta. No se pudo procesar su solicitud.';
                        document.getElementById("div_error_clave").style.display = 'block';
                        document.getElementById("div_perfil").style.display = 'none';
                        document.getElementById("div_avatar").style.display = 'none';
                        document.getElementById("div_cuenta").style.display = 'block';
                        document.getElementById("div_pagos").style.display = 'none';
                        document.getElementById("div_cerrar_cuenta").style.display = 'none';
                    });
                </script>
            @endif

            <div class="uk-child-width-expand@s uk-text-center" uk-grid>

                <div class="uk-width-1-4@m">
                    <div class="mt-content">
                        <div class="uk-card uk-card-default uk-card uk-padding-small  uk-box-shadow-medium" >
                            <div style="padding: 10px;">
                                <img style="border-radius: 50%; height: 250px; width: 250px;" class="uk-width-1-1" src="{{ asset('uploads/images/users/'.Auth::user()->avatar) }}">
                            </div>
                            <div class="uk-h4 uk-margin-remove uk-text-center uk-margin-small-top diop">{{ Auth::user()->names }} {{ Auth::user()->last_names }}</div>

                            <ul class="uk-list uk-list-divider uk-margin-remove-bottom uk-text-center uk-text-small">
                                <li>
                                    <a onclick="cambiarTab(1);"> Mi Perfil</a>
                                </li>
                                <li>
                                    <a onclick="cambiarTab(2);"> Fotografía</a>
                                </li>
                                <li>
                                    <a onclick="cambiarTab(3);"> Cuenta </a>
                                </li>
                                <li>
                                    <a onclick="cambiarTab(4);"> Métodos de Pago</a>
                                </li>
                                <li style="padding-bottom: 12px;">
                                    <a onclick="cambiarTab(5);"> Cerrar Cuenta</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="uk-width-3-4@m">
                        <div class="uk-card-default uk-padding-small uk-box-shadow-medium mt-content" id="div_perfil">
                            <form method="POST" action="{{ route('students.profile.update') }}">
                                @csrf
                                <div class="uk-card-header">
                                    Mi Perfil
                                </div>
                                <div class="paddin-content">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-2">Nombres: <input class="uk-input form-input" type="text" name="names" value="{{ Auth::user()->names }}" required></div>
                                        <div class="uk-width-1-2">Apellidos: <input class="uk-input form-input" type="text" name="last_names" value="{{ Auth::user()->last_names }}" required></div>
                                        <div class="uk-width-1-3">Nombre de Usuario: <input class="uk-input form-input" type="text" name="username" value="{{ Auth::user()->username }}" required></div>   
                                        <div class="uk-width-1-3">Fecha de Nacimiento: <input class="uk-input form-input" type="date" name="birthdate" value="{{ Auth::user()->birthdate }}" required></div>
                                        <div class="uk-width-1-3">Sexo: <select class="uk-select" name="gender" required>
                                            <option value="F">Femenino</option>
                                            <option value="M">Masculino</option>
                                        </select></div>
                                        <div class="uk-width-1-3">Profesión: <input class="uk-input form-input" type="text" name="profession" value="{{ Auth::user()->profession }}" required></div>
                                        <div class="uk-width-1-3">País: <input class="uk-input form-input" type="text" name="country" value="{{ Auth::user()->country }}" required></div>
                                        <div class="uk-width-1-3">Estado: <input class="uk-input form-input" type="text" name="state" value="{{ Auth::user()->state }}" required></div>
                                        <div class="uk-width-1-2">Facebook: <input class="uk-input form-input" type="text" name="facebook" value="{{ Auth::user()->facebook }}" ></div>
                                        <div class="uk-width-1-2">Twitter: <input class="uk-input form-input" type="text" name="twitter" value="{{ Auth::user()->twitter }}" ></div>
                                         <div class="uk-width-1-2">Instagram: <input class="uk-input form-input" type="text" name="instagram" value="{{ Auth::user()->instagram }}" ></div>
                                        <div class="uk-width-1-2">Pinterest: <input class="uk-input form-input" type="text" name="pinterest" value="{{ Auth::user()->pinterest }}" ></div>
                                         <div class="uk-width-1-1">Canal de Youtube: <input class="uk-input form-input" type="url" name="youtube" value="{{ Auth::user()->youtube }}" ></div>
                                        <div class="uk-width-1-1">Acerca de Mi: <textarea class="uk-textarea" rows="3" name="review" required>{{ Auth::user()->review }}</textarea></div>
                                    </div>
                                </div>
                                <div class="uk-card-footer">
                                    <input type="submit" class="uk-button uk-button-success uk-align-center" value="Guardar Cambios">
                                </div>
                            </form>
                        </div>

                        <div class="uk-card-default mt-content" id="div_avatar" style="display: none;">
                            <form method="POST" action="{{ route('students.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="uk-card-header">
                                    Fotografía
                                </div>
                                <div class="uk-card-body">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <center><hr class="uk-divider-icon"><img data-src="{{ asset('uploads/images/users/'.Auth::user()->avatar) }}" style="width: 300px;" uk-img></center>
                                            <hr class="uk-divider-icon"><br>
                                        </div>

                                        <div class="uk-width-1-1">
                                            <center>
                                                <div uk-form-custom="target: true">
                                                    <input type="file" name="avatar" required>
                                                    <input class="uk-input uk-form-width-large" type="text" placeholder="No has seleccionado ningún archivo" disabled>
                                                </div>
                                                <button class="uk-button uk-button-success">Subir Imagen</button>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="uk-card-default mt-content" id="div_cuenta" style="display: none;">
                            <form method="POST" action="{{ route('students.profile.update') }}">
                                @csrf
                                <div class="uk-card-header">
                                    Cuenta
                                </div>

                                <div class="uk-card-body">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <div class="uk-margin">
                                                Correo Electrónico:<br>
                                                <div class="uk-inline">
                                                    <a class="uk-form-icon uk-form-icon-flip" href="#correoModal" uk-icon="icon: file-edit" uk-toggle></a>
                                                    <input class="uk-input uk-form-width-large" type="text" placeholder="Tu correo es {{ Auth::user()->email }}" disabled>
                                                </div>
                                            </div> <hr>
                                        </div>

                                        <div class="uk-width-1-1">
                                            Contraseña: <br>
                                            <input class="uk-input uk-form-width-large" type="password" name="clave_actual" placeholder="Escribe la contraseña actual"><br><br>
                                            <input class="uk-input uk-form-width-large" type="password" name="clave" id="clave" placeholder="Escribe la contraseña nueva" onkeyup="verificarClaves();"><br><br>
                                            <input class="uk-input uk-form-width-large" type="password" id="clave2" placeholder="Escribe la contraseña otra vez" onkeyup="verificarClaves();">

                                            <div class="uk-alert-danger" uk-alert id="div_error_clave" style="display: none;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="uk-card-footer">
                                    <input type="submit" class="uk-button uk-button-success uk-align-center" value="Cambiar Contraseña" id="btn-cambiar-clave" disabled>
                                </div>
                            </form>
                        </div>

                        <div class="uk-card-default mt-content" id="div_pagos" style="display: none;">
                            <form method="POST" action="{{ route('students.profile.update') }}">
                                @csrf
                                <div class="uk-card-header">
                                    Métodos de Pago
                                </div>
                                <div class="uk-card-body">
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            <center>
                                                Configuración de Pago<hr>
                                                <input type="hidden" name="pagos" value="1">
                                                <label><input class="uk-checkbox" type="checkbox" name="save_payment_method" @if (Auth::user()->save_payment_method == 1) checked @endif> Conservar mi información de pago para otras compras</label>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-card-footer">
                                    <input type="submit" class="uk-button uk-button-success uk-align-center" value="Guardar Cambios">
                                </div>
                            </form>
                        </div>

                        <div class="uk-card-default mt-content" id="div_cerrar_cuenta" style="display: none;">
                            <div class="uk-card-header">
                                Cerrar Cuenta
                            </div>
                            <div class="uk-card-body">
                                <div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        <center>
                                            <span style="color: red;"><strong>Advertencia:</strong></span> Si cierras tu cuenta, se cancelará tu suscripción a tus cursos y perderás tus suscripción para siempre.
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-card-footer">
                                <a class="uk-button uk-button-danger uk-align-center uk-form-width-medium" href="#cerrarCuentaModal" uk-toggle>Cerrar Cuenta</a>
                            </div>
                        </div>
                    </div>
            </div>
            <br>
        </div>

        <div id="correoModal" uk-modal>
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>

                <form method="POST" action="{{ route('students.profile.update') }}">
                    @csrf
                    <div class="uk-modal-header">
                        Cambiar Correo Electrónico
                    </div>

                    <div class="uk-modal-body">
                        <div class="uk-alert-danger" uk-alert id="div_error_correo" style="display: none;"></div>

                        <div class="uk-margin">
                            <div class="uk-width-1-1">
                                <center>
                                    <div class="uk-inline">
                                        <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: mail"></span>
                                        <input class="uk-input uk-form-width-large" type="email" name="email" id="email" placeholder="Ingrese su nuevo correo" onkeyup="verificarCorreo();">
                                    </div>
                                </center>
                            </div><br>

                            <div class="uk-width-1-1">
                                <center>
                                    <div class="uk-inline">
                                        <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: mail"></span>
                                        <input class="uk-input uk-form-width-large" type="email" id="email2" placeholder="Confirme su nuevo correo" onkeyup="verificarCorreo2();">
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                    <div class="uk-modal-footer uk-text-right">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                        <button class="uk-button uk-button-primary" type="submit" id="btn-guardar" disabled>Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="cerrarCuentaModal" uk-modal>
            <div class="uk-modal-dialog">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <div class="uk-modal-header">
                    Cerrar Cuenta Permanentemente
                </div>

                <div class="uk-modal-body">
                    <center>
                        ¿Está seguro que desea cerrar su cuenta de forma permanente?<br>

                        <span style="color: red;">Advertencia: </span> Esta operación es irreversible.
                    </center>

                </div>

                <div class="uk-modal-footer uk-text-right">
                    <form method="POST" action="{{ route('students.profile.update') }}">
                        @csrf
                        <input type="hidden" name="status" value="0">
                        <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                        <input type="submit" class="uk-button uk-button-danger" value="Cerrar Cuenta"/>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
