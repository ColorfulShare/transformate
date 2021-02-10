@extends('layouts.landing')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@push('scripts')
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script src="{{ asset('js/mercadoPago.js') }}"></script>

    <script>
        $(function(){
            $('#applyCoupon').on('click', function(){
                $('#div_coupon').removeClass('uk-hidden');
            });
        });
        function metodo_pago($opcion){
            if ($opcion == 'tdc'){
                $(".radio-tdc").prop("checked", true);
                $(".radio-pse").prop("checked", false);
                $(".radio-efecty").prop("checked", false);
                $(".radio-paypal").prop("checked", false);
            }else if ($opcion == 'pse'){
                $(".radio-tdc").prop("checked", false);
                $(".radio-pse").prop("checked", true);
                $(".radio-efecty").prop("checked", false);
                $(".radio-paypal").prop("checked", false);
            }else if ($opcion == 'efecty'){
                $(".radio-tdc").prop("checked", false);
                $(".radio-pse").prop("checked", false);
                $(".radio-efecty").prop("checked", true);
                $(".radio-paypal").prop("checked", false);
            }else if ($opcion == 'paypal'){
                $(".radio-tdc").prop("checked", false);
                $(".radio-pse").prop("checked", false);
                $(".radio-efecty").prop("checked", false);
                $(".radio-paypal").prop("checked", true);
            }
            
        }
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
                    <a class="keep-buying-header" href="{{ route('students.shopping-cart.index') }}">Modificar Compra
                        <i class="to-right">→</i>
                    </a>
                </div>
            </div>

            <div class="uk-text-left checkout-total-div">
                Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
            </div>

            <div class="items-content">
                <span class="register-for-continue">Confirma tu compra</span>
                <p class="login-click">Ya puedes realizar tu compra</p>
                <hr>

                <ul class="payment-methods" uk-accordion>
                    @if ($totalItems > 0)
                        {{-- MercadoPago Tarjetas --}}
                        <li>
                            <a class="uk-accordion-title accordion-mp" href="#" onclick="metodo_pago('tdc');"><label><input class="uk-radio radio-tdc" type="radio"> Pagar con tarjeta</label></a>
                            <div class="uk-accordion-content">
                                <form accept-charset="UTF-8" action="{{ route('students.shopping-cart.mercado-pago-checkout') }}" method="POST" id="pay" name="pay">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $totalItems }}"/>
                                    <input type="hidden" name="original_amount" value="{{ $totalAnterior }}"/>
                                    <input type="hidden" name="paymentMethodId" />
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1 required">
                                            Nombre en la Tarjeta: 
                                            <input class="uk-input input-shop" type="text" id="cardholderName" data-checkout="cardholderName">
                                        </div>
                                        <div class="uk-width-1-1 uk-width-1-2@m required">
                                            Tipo de Documento: 
                                            <select class="uk-select input-shop" id="docType" data-checkout="docType"></select>
                                        </div>
                                        <div class="uk-width-1-1 uk-width-1-2@m required">
                                            Número de Documento: 
                                            <input class="uk-input input-shop" type="text" id="docNumber" data-checkout="docNumber">
                                        </div>
                                        <div class="uk-width-1-1 required">
                                            Número de Tarjeta: 
                                            <input class="uk-input input-shop" type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete='off'>
                                        </div>
                                        <div class="uk-width-1-2 uk-width-1-3@m required">
                                            Fecha de Expiración
                                            <select class="uk-select input-shop" id="cardExpirationMonth" data-checkout="cardExpirationMonth" required>
                                                <option value="Mes" selected disabled>Mes</option>
                                                <option value="1">01 - Enero</option>
                                                <option value="2">02 - Febrero</option>
                                                <option value="3">03 - Marzo</option>
                                                <option value="4">04 - Abril</option>
                                                <option value="5">05 - Mayo</option>
                                                <option value="6">06 - Junio</option>
                                                <option value="7">07 - Julio</option>
                                                <option value="8">08 - Agosto</option>
                                                <option value="9">09 - Septiembre</option>
                                                <option value="10">10 - Octubre</option>
                                                <option value="11">11 - Noviembre</option>
                                                <option value="12">12 - Diciembre</option>
                                            </select>
                                        </div>
                                        <div class="uk-width-1-2 uk-width-1-3@m required">
                                            <br>
                                            <select class="uk-select input-shop" id="cardExpirationYear" data-checkout="cardExpirationYear" required>
                                                <option value="Año" selected disabled>Año</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                                <option value="2029">2029</option>
                                                <option value="2030">2030</option>
                                            </select>
                                        </div>
                                         <div class="uk-width-1-3 required">
                                            CVV: 
                                            <input class="uk-input input-shop" type="number" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete='off'>
                                        </div>
                                        <div class="uk-width-1-1">
                                            <br>
                                            <button type="submit" class="button button-checkout button-block"><i class="fa fa-shopping-cart"></i> Completar Compra</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        {{-- PSE --}}
                        <li>
                            <a class="uk-accordion-title accordion-bank" href="#" onclick="metodo_pago('pse');"><label><input class="uk-radio radio-pse" type="radio"> Pagar con PSE</label></a>
                            <div class="uk-accordion-content">
                                <form action="{{ route('students.checkout.bank-transfer-checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $totalItems }}">
                                    <input type="hidden" name="original_amount" value="{{ $totalAnterior }}"/>
                                    <input type="hidden" name="instructor_code_discount" value="{{ $descuentoCodigo}}">
                                    {{--@if (!is_null($cuponAplicado))
                                        <input type="hidden" name="coupon_id" value="{{ $cuponAplicado->id }}">
                                    @endif--}}
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            Pago seguro en línea a través de cuenta corriente o ahorros. Recuerda tener habilitada tu cuenta corriente/ahorros para realizar compras vía internet.
                                        </div>
                                        <div class="uk-width-1-1 required">
                                            Banco PSE: 
                                            <select class="uk-select input-shop" name="bank_id" required>
                                                <option value="" selected disabled>Seleccione un banco...</option>
                                                @foreach ($bancosDisponibles as $banco)
                                                    <option value="{{ $banco['id'] }}">{{ $banco['description'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="uk-width-1-2 required">
                                            Tipo de Documento: 
                                            <select class="uk-select input-shop" name="document_type">
                                                <option value="CC">Cédula de Ciudadanía</option>
                                                <option value="CE">Cédula de Extranjería</option>
                                                <option value="NIT">Número de Identificación Tributaria</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="uk-width-1-2 required">
                                            Número de Documento: 
                                            <input class="uk-input input-shop" type="text" name="document_number">
                                        </div>
                                        <div class="uk-width-1-1">
                                            <br>
                                            <button type="submit" class="button button-checkout button-block"><i class="fa fa-shopping-cart"></i> Completar Compra</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        {{-- Efectivo (Efecty) --}}
                        <li>
                            <a class="uk-accordion-title accordion-efecty" href="#" onclick="metodo_pago('efecty');"><label><input class="uk-radio radio-efecty" type="radio"> Pagar con Efecty</label></a>
                            <div class="uk-accordion-content">
                                <form action="{{ route('students.shopping-cart.efecty-checkout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $totalItems }}">
                                    <input type="hidden" name="original_amount" value="{{ $totalAnterior }}"/>
                                    <input type="hidden" name="instructor_code_discount" value="{{ $descuentoCodigo}}">
                                    {{--@if (!is_null($cuponAplicado))
                                        <input type="hidden" name="coupon_id" value="{{ $cuponAplicado->id }}">
                                    @endif--}}
                                    <div class="uk-grid">
                                        <div class="uk-width-1-1">
                                            Pago a través de la Red Efecty a nivel nacional (Colombia).
                                        </div>
                                        <div class="uk-width-1-1">
                                            <br>
                                            <button type="submit" class="button button-checkout button-block"><i class="fa fa-shopping-cart"></i> Completar Compra</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        {{-- Paypal --}}
                        <li>
                            <a class="uk-accordion-title accordion-paypal" href="#" onclick="metodo_pago('paypal');"><label><input class="uk-radio radio-paypal" type="radio"> Pagar con PayPal</label></a>
                            <div class="uk-accordion-content">
                                <form action="{{ route('students.shopping-cart.paypal-checkout') }}" method="POST">
                                    @csrf
                                    {{--@if (!is_null($cuponAplicado))
                                            <input type="hidden" name="coupon_id" value="{{ $cuponAplicado->id }}">
                                    @endif--}}
                                    <input type="hidden" name="amount" value="{{ $totalItems }}">     
                                    <p class="h6">Antes de seguir con la compra, inicia sesión con tu cuenta de PayPal</p>
                                    <button type="submit" class="button button-paypal" @if ($totalItems == 0) disabled @endif><i class="fab fa-paypal" style="font-size: 1.500rem;"></i> Entrar en PayPal</button>
                                </form>
                            </div>
                        </li>
                    @else
                        @if ($cantItems > 0)
                            <li>
                                <a class="uk-accordion-title" href="#"><label><input class="uk-radio" type="radio" checked> Compra Gratis</label></a>
                                <div class="uk-accordion-content">
                                    <a class="uk-button btn-primary btn-lg btn-block pay-payu" href="{{ route('students.shopping-cart.free-checkout') }}"><i class="fa fa-shopping-cart"></i> Realizar Compra</a>
                                </div>
                            </li>
                        @endif
                    @endif
                </ul>

                <div class="uk-child-width-1-1@s uk-child-width-1-2@m" uk-grid>
                    <div class="uk-text-left">
                        <span style="font-size: 15px; font-weight: 500;">Pago 100% seguro</span><br>
                        <span style="font-size: 14px;">Este certificado garantiza la seguridad de todas tus conexiones mediante cifrado.</span>
                    </div>
                    <div class="uk-text-right payment-items">
                        <ul class="list-inline cards-list">
                            <li class="list-inline-item">
                                <div class="card-wrapper">
                                    <img class="payment-item-small" alt="Visa" title="Visa" src="https://www.transformatepro.com/template/images/logo-visa.png">
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="card-wrapper">
                                    <img class="payment-item-small" alt="Mastercard" title="Mastercard" src="https://www.transformatepro.com/template/images/logo-mastercard.png">
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="card-wrapper">
                                    <img class="payment-item-large" alt="Paypal" title="Paypal" src="https://www.transformatepro.com/template/images/logo-paypal.png">
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="card-wrapper">
                                    <img class="payment-item-small" alt="PSE" title="PSE" src="https://www.transformatepro.com/template/images/logo-pse.png">
                                </div>
                            </li>
                            <li class="list-inline-item">
                                <div class="card-wrapper">
                                    <img class="payment-item-large" alt="Efecty" title="Efecty" src="https://www.transformatepro.com/template/images/logo-efecty.png">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection