@extends('layouts.coursesLanding')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@push('scripts')
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script src="{{ asset('js/mercadoPago.js') }}"></script>

    <script>
        $(document).ready(function(){
            if ( ($("#instructor-code-check").val() == "") || ($("#partner-code-check").val() == "")){
                var modal = UIkit.modal("#infoModal");
                modal.show();
            }
           
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
<input type="hidden" id="instructor-code-check" value="{{$suscriptor->instructor_code}}">
<input type="hidden" id="partner-code-check" value="{{$suscriptor->partner_code}}">
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
                    <i class="fa fa-shopping-cart icon-cart"></i> Pago del Evento <b>{{ $evento->title }} </b>
                </div>
            </div>

            <div class="uk-text-left checkout-total-div">
                Total COP$ @if ($evento->presale == 1) {{ number_format($evento->presale_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif
                @if ( (!is_null($suscriptor->instructor_code)) || (!is_null($suscriptor->partner_code)) )
                    <br>-10% de descuento por aplicación de Código<br>
                    Total a Pagar:  {{ number_format(($evento->amount), 0, ',', '.') }} 
                @endif
            </div>

            <div class="items-content">
                <span class="register-for-continue">Confirma tu compra</span>
                <p class="login-click">Ya puedes realizar tu compra</p>
                
                <div class="buttons-div">
                    <div class="uk-child-width-1-1@s uk-child-width-1-2@m" uk-grid>
                        @if (is_null($suscriptor->instructor_code))
                            <div class="button-code-div uk-text-right">
                                <a class="button button-code" href="#addInstructorCodeModal" uk-toggle>
                                    <i class="fas fa-tags"></i> Aplicar código de mentor
                                </a>
                            </div>
                        @else
                            <div class="button-code-div">
                                ¡¡Código de Instructor Aplicado!! (Instructor: <b>{{ $suscriptor->instructor->names }} {{ $suscriptor->instructor->last_names }}</b>) 
                            </div>
                        @endif
                        @if (is_null($suscriptor->partner_code))
                            <div class="button-checkout-div uk-text-left">
                                <a class="button button-checkout" href="#addPartnerCodeModal" uk-toggle>
                                    <i class="fas fa-tags"></i> Aplicar código T-Partner
                                </a>
                            </div>
                        @else
                            <div class="button-code-div">
                                ¡¡Código de T-Partner Aplicado!! (T-Partner: <b>{{ $suscriptor->partner->names }} {{ $suscriptor->partner->last_names }}</b>) 
                            </div>
                        @endif
                    </div>
                </div>

                <ul class="payment-methods" uk-accordion>
                    {{-- MercadoPago Tarjetas --}}
                    <li>
                        <a class="uk-accordion-title accordion-mp" href="#" onclick="metodo_pago('tdc');"><label><input class="uk-radio radio-tdc" type="radio"> Pagar con tarjeta</label></a>
                        <div class="uk-accordion-content">
                            <form accept-charset="UTF-8" action="{{ route('landing.events.mercado-pago-checkout') }}" method="POST" id="pay" name="pay">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $evento->amount }}"/>
                                @if ($suscriptor->gift == 0)
                                    <input type="hidden" name="payer_email" value="{{ $suscriptor->email }}">
                                @else
                                    <input type="hidden" name="payer_email" value="{{ $suscriptor->gift_buyer }}">
                                @endif
                                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">
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
                            <form action="{{ route('landing.events.bank-transfer-checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $evento->amount }}" >
                                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">
                                <div class="uk-grid">
                                    <div class="uk-width-1-1">
                                        Pago seguro en línea a través de cuenta corriente o ahorros. Recuerda tener habilitada tu cuenta corriente/ahorros para realizar compras vía internet.
                                    </div>
                                    <div class="uk-width-1-1 required">
                                        Banco PSE: 
                                        <select class="uk-select input-shop" name="bank_id" required>
                                            <option value="" selected disabled>Seleccione un banco...</option>
                                            <option value="1007">Bancolombia</option>
                                            <option value="1061">Bancoomeva S.A.</option>
                                            <option value="1040">Banco Agrario</option>
                                            <option value="1052">Banco AV Villas</option>
                                            <option value="1013">Banco BBVA Colombia S.A.</option>
                                            <option value="1032">Banco Caja Social</option>
                                            <option value="1066">Banco Cooperativo Coopcentral</option>
                                            <option value="1558">Banco Credifinanciera</option>
                                            <option value="1051">Banco Davivienda</option>
                                            <option value="1001">Banco De Bogotá</option>
                                            <option value="1059">Banco De Las Microfinanzas Bancamia</option>
                                            <option value="1023">Banco De Occidente</option>
                                            <option value="1062">Banco Falabella</option>
                                            <option value="1012">Banco GNB Sudameris</option>
                                            <option value="1006">Banco Itaú</option>
                                            <option value="1060">Banco Pichincha S.A.</option>
                                            <option value="1002">Banco Popular</option>
                                            <option value="1065">Banco Santander Colombia</option>
                                            <option value="1069">Banco Serfinanza</option>
                                            <option value="1283">Cfa Cooperativa Financiera</option>
                                            <option value="1009">Citibank</option>
                                            <option value="1370">Coltefinanciera</option>
                                            <option value="1292">Confiar Cooperativa Financiera</option>
                                            <option value="1289">Cotrafa</option>
                                            <option value="1551">DaviPlata</option>
                                            <option value="1507">Nequi</option>
                                            <option value="1151">Rappipay</option>
                                            <option value="1019">Scotiabank Colpatria</option>
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
                            <form action="{{ route('landing.events.efecty-checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $evento->amount }}">
                                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">
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
                            <form action="{{ route('landing.events.paypal-checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $evento->amount }}">  
                                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">   
                                <p class="h6">Antes de seguir con la compra, inicia sesión con tu cuenta de PayPal</p>
                                <button type="submit" class="button button-paypal"><i class="fab fa-paypal" style="font-size: 1.500rem;"></i> Entrar en PayPal</button>
                            </form>
                        </div>
                    </li>
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

    <div id="addInstructorCodeModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Agregar Código de Mentor</h4>
            </div>
            <form action="{{ route('landing.events.add-instructor-code') }}" method="POST">
                @csrf
                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">
                <input type="hidden" name="event_id" value="{{ $evento->id }}">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el código del mentor: </b></label>
                        <input class="uk-input uk-form-success" type="text" name="codigo" required>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Aplicar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addPartnerCodeModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Agregar Código de T-Partner</h4>
            </div>
            <form action="{{ route('landing.events.add-partner-code') }}" method="POST">
                @csrf
                <input type="hidden" name="suscriptor_id" value="{{ $suscriptor->id }}">
                <input type="hidden" name="event_id" value="{{ $evento->id }}">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el código del T-Partner: </b></label>
                        <input class="uk-input uk-form-success" type="text" name="codigo" required>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Aplicar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="infoModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Información Importante</h4>
            </div>
            <div class="uk-modal-body">
                Recuerda que si posees un código de Mentor o un código T-Partner es importante que lo apliques antes de realizar el pago. En la página encontrarás los botones correspondientes a la aplicación de cada código. ¡Gracias!
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-primary uk-modal-close" type="button">Entendido</button>
            </div>
        </div>
    </div>
@endsection