@extends('layouts.coursesLanding')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@push('scripts')
    <script>
        $(function(){
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
@endpush

@section('content')
    <div class="background-shop">
        <div class="uk-container" style="margin-top: 20px;">
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

            <div class="uk-child-width-1-2" uk-grid>
                <div class="items-number">
                    <i class="fa fa-shopping-cart icon-cart"></i> Carrito 
                    <span>({{ $cantItems }} Items)</span>
                </div>
                <div class="uk-text-right keep-buying-header-div">
                    <a class="keep-buying-header" href="{{ route('landing.shopping-cart.index') }}">Modificar Compra
                        <i class="to-right">→</i>
                    </a>
                </div>
            </div>

            <div class="uk-text-left checkout-total-div">
                Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
            </div>

            <div class="items-content">
                <span class="register-for-continue">Regístrate para disfrutar de tu compra</span>
                <p class="login-click">Si ya estás registrado puedes <a href="#modal-login" uk-toggle>entrar</a></p>
                <hr>

                <div>
                    <a class="button button-register-with-google" href="{{ url('/login/google') }}"><i class="fab fa-google"></i> Regístrate con Gmail</a><br>
                    <div class="uk-text-center">
                        <span class="register-with-email">o regístrate con tu email</span>
                    </div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="checkout" value="1">
                         <div class="uk-margin">
                            <input class="uk-input" type="text" name="names" placeholder="Tu Nombre" required>
                        </div>
                         <div class="uk-margin">
                            <input class="uk-input" type="text" name="last_names" placeholder="Tu Apellido" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="email" name="email" placeholder="Tu correo electrónico" required>
                        </div>
                        <div class="uk-margin">
                            <input class="uk-input" type="password" name="password" placeholder="Tu contraseña" required>
                        </div>
                        {{--<div class="uk-margin">
                            <span class="birthdate">¿Cuál es tu fecha de nacimiento?</span>
                            <input class="uk-input" type="date" name="birthdate" id="birthdate" required>
                        </div>
                        <div class="uk-margin" style="display: none;" id="div_edad">
                            <div class="uk-alert-danger" uk-alert>
                                Debes tener 16 años o más para registrarte.
                            </div>
                        </div>--}}
                        <div class="uk-margin uk-text-right">
                            <button name="button" type="submit" class="button button-checkout" id="btn-crear">Crear cuenta
                            </button>
                        </div>
                        <div class="uk-text-center info">
                            <h6>Al hacer clic en "Crear cuenta" certifico que tengo 16 años o más y acepto las <a class="link-secondary--underlined" target="blank" href="#">Condiciones de Uso</a>, la <a class="link-secondary--underlined" target="blank" href="#">Política de Privacidad</a>, la <a class="link-secondary--underlined" target="blank" href="#">Política de Cookies</a> y recibir novedades y promociones.</h6>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection