@extends('layouts.coursesLanding')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@push('scripts')
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script src="{{ asset('js/mercadoPago.js') }}"></script>
@endpush

@section('content')
    <div class="background-shop">
        <div style="margin-top: 30px; margin-bottom: 30px; margin-left: 5%; margin-right: 5%;">
            @if ($errors->any())
                <div class="uk-alert-danger" uk-alert>
                    <ul class="uk-list uk-list-bullet">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (Session::has('msj-erroneo'))
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-erroneo') }}</strong>
                </div>
            @endif

            <div class="uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
                {{-- Sección Izquierda --}}
                <div>
                    <div class="paper">
                        <div class="checkout__head">
                            <h1 class="h2">Confirma tu compra</h1>
                            <p>Selecciona tu método de pago..</p>
                        </div>
                        <div class="checkout__body">
                            <ul uk-accordion="collapsible: false">
                                <li>
                                    <a class="uk-accordion-title accordion-paypal" href="#"><label><input class="uk-radio" type="radio"> Paypal</label></a>
                                    <div class="uk-accordion-content" style="margin-top: 0px;">
                                        <form action="{{ route('landing.shopping-cart.paypal-gift-membership') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="amount" value="{{ $membresia->price }}">   
                                            <input type="hidden" name="gift" value="1">  
                                            <p class="h6">Antes de seguir con la compra, inicia sesión con tu cuenta de PayPal</p>
                                            <button type="submit" class="uk-button btn-paypal uk-button-large btn-block btn-lg"><i class="fab fa-paypal" style="font-size: 1.500rem;"></i> Entrar en PayPal</button>
                                        </form>
                                    </div>
                                </li>
                                <li>
                                    <a class="uk-accordion-title accordion-mp" href="#"><label><input class="uk-radio" type="radio"> Tarjeta de Crédito</label></a>
                                    <div class="uk-accordion-content" style="margin-top: 0px;">
                                        <form accept-charset="UTF-8" action="{{ route('landing.shopping-cart.mercado-pago-gift-membership') }}" method="POST" id="pay" name="pay">
                                            @csrf
                                            <input type="hidden" name="amount" value="{{ $membresia->price }}"/>
                                            <input type="hidden" name="gift" value="1">
                                            <input type="hidden" name="paymentMethodId" />
                                            <div class="uk-grid">
                                                <div class="uk-width-1-1 required">
                                                    Correo Electrónico: 
                                                    <input class="uk-input input-shop" type="email" name="email">
                                                </div>
                                                <div class="uk-width-1-1 required">
                                                    Nombre en la Tarjeta: 
                                                    <input class="uk-input input-shop" type="text" id="cardholderName" data-checkout="cardholderName">
                                                </div>
                                                <div class="uk-width-1-2 required">
                                                    Tipo de Documento: 
                                                    <select class="uk-select input-shop" id="docType" data-checkout="docType"></select>
                                                </div>
                                                <div class="uk-width-1-2 required">
                                                    Número de Documento: 
                                                    <input class="uk-input input-shop" type="text" id="docNumber" data-checkout="docNumber">
                                                </div>
                                                <div class="uk-width-1-1 required">
                                                    Número de Tarjeta: 
                                                    <input class="uk-input input-shop" type="text" id="cardNumber" data-checkout="cardNumber" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete='off'>
                                                </div>
                                                <div class="uk-width-1-3 required">
                                                    CVV: 
                                                    <input class="uk-input input-shop" type="number" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete='off'>
                                                </div>
                                                <div class="uk-width-1-3 required">
                                                    Mes: 
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
                                                <div class="uk-width-1-3 required">
                                                    Año: 
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
                                                <div class="uk-width-1-1">
                                                    <br>
                                                    <button type="submit" class="uk-button btn-primary btn-lg btn-block"><i class="fa fa-shopping-cart"></i> Completar Compra</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="checkout__security">
                        <div class="uk-child-width-1-2" uk-grid>
                            <div class="checkout__security__item">
                                <h4 class="h3">Pago 100% seguro</h4>
                                <p>Este certificado garantiza la seguridad de todas tus conexiones mediante cifrado.</p>
                            </div>

                            <div class="checkout__security__item uk-text-right">
                                <ul class="list-inline cards-list">
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-visa-8a18fbf2359d9aa9ae996beea6fb859de3be9d8d5256e4fb7c0f926f22f51c45.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-visa@2x-d683b41bd4bdde323cb893fed75b982577c2352b3d22dca76d141ef9ada44113.png 2x" alt="Visa" title="Visa" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-visa-8a18fbf2359d9aa9ae996beea6fb859de3be9d8d5256e4fb7c0f926f22f51c45.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-visa@2x-d683b41bd4bdde323cb893fed75b982577c2352b3d22dca76d141ef9ada44113.png 2x">
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-mastercard-ea3facb35831c19f7d184954248b0e458ed8f1efb6495f75bcc797a014252d96.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-mastercard@2x-df0939e975e0b2549a921a8d4754c7c960a55dcdfee81a2a755d0dc6f1610a78.png 2x" alt="MasterCard" title="MasterCard" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-mastercard-ea3facb35831c19f7d184954248b0e458ed8f1efb6495f75bcc797a014252d96.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-mastercard@2x-df0939e975e0b2549a921a8d4754c7c960a55dcdfee81a2a755d0dc6f1610a78.png 2x">
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-amex-e085770cc9a90b2d0137362d2368866f03d44eb3cb5d5727f231ddc01981a37b.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-amex@2x-dbe04ea03eba35bef4ab4377ad5a3e96d62567caf3e13615bfa8f21e55227958.png 2x" alt="American Express" title="American Express" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-amex-e085770cc9a90b2d0137362d2368866f03d44eb3cb5d5727f231ddc01981a37b.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-amex@2x-dbe04ea03eba35bef4ab4377ad5a3e96d62567caf3e13615bfa8f21e55227958.png 2x">
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-discover-ab22881b089834c3f03d4c4adde12a1e4829082575b2f07f38d19bee36000b36.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-discover@2x-39b7ebaca3f7a78aca6bc3028b2eb7e26e046cd517dc94b8aa9f2d06a81b6243.png 2x" alt="Discover" title="Discover" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-discover-ab22881b089834c3f03d4c4adde12a1e4829082575b2f07f38d19bee36000b36.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-discover@2x-39b7ebaca3f7a78aca6bc3028b2eb7e26e046cd517dc94b8aa9f2d06a81b6243.png 2x">
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-jcb-568d3abc2d789efab23cf844d293a23c388d9a8a877940cc2d5950fa25219181.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-jcb@2x-ade6160b5e6ea6bb2b944d29ab2d8ea9f62bc7bc16c966598a33b5fffee0335e.png 2x" alt="JCB" title="JCB" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-jcb-568d3abc2d789efab23cf844d293a23c388d9a8a877940cc2d5950fa25219181.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-jcb@2x-ade6160b5e6ea6bb2b944d29ab2d8ea9f62bc7bc16c966598a33b5fffee0335e.png 2x">
                                        </div>
                                    </li>
                                    <li class="list-inline-item">
                                        <div class="card-wrapper">
                                            <img data-srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-paypal-ade0ee5fa42753277828096cb7c287be63b3527903f2efbedeafb63ef55556c4.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-paypal@2x-48146b210b75027ddc93d59cd0b229348c1be5e62c6b8411119c9ade8cc02d78.png 2x" alt="PayPal" title="PayPal" class=" lazyloaded" src="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-lazyload-6b2937151d7662d2a0b37af79c23a1dfaa812544aa1bd701b42077157c08dd93.svg" srcset="https://cdn.domestika.org/raw/upload/assets/payment-methods/card-paypal-ade0ee5fa42753277828096cb7c287be63b3527903f2efbedeafb63ef55556c4.png, https://cdn.domestika.org/raw/upload/assets/payment-methods/card-paypal@2x-48146b210b75027ddc93d59cd0b229348c1be5e62c6b8411119c9ade8cc02d78.png 2x">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="checkout__security__info">
                            <i class="fa fa-info-circle i-space-right-small"></i> Tus tarjetas se guardan de forma segura para que puedas reutilizar el método de pago.
                        </p>
                    </div>
                </div>

                {{-- Sección Derecha --}}
                <div>
                    <div class="paper--gray cart__box">
                        <div class="cart__box__head">
                            <div class="align-items-end">
                                <div class="mr-auto">
                                    <h2 class="h3 cart__title">Carrito <span>(1 Item)</span></h2>
                                </div>
                            </div>
                        </div>
                        <ul class="cart-list cart-list--small">
                            <li class="cart-list__item">
                                <div uk-grid>
                                    <div class="uk-width-expand@m">
                                        <div class="cart-list__details">
                                            <h3 class="h4 cart-list__title">{{ $membresia->name }}</h3>
                                            <p class="cart-list__info">Compra para Regalo</p>
                                            <div class="cart-list__img">
                                                <img class="img-item" src="{{ asset('template/images/ico8.png') }}">
                                            </div>
                                            
                                            <p></p> 
                                            <span class="price-badges">
                                                <span class="badge badge--promo-tag-34 ">REBAJAS</span>
                                                <span class="price-badges--prices">
                                                    <span class="price-discount t-price-discount">50% Dto.</span>
                                                    <b class="price-before t-price-before">COP$ {{ number_format($membresia->price*2, 0, ',', '.') }}</b>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="uk-width-auto@m">
                                        <div class="cart-list__price">COP$ {{ number_format($membresia->price, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="cart__box__total">
                            <div class="cart__box__total__counts" style="padding-bottom: 15px;">
                                <b class="cart__box__total__counts__total">Total a Pagar: COP$ {{ number_format($membresia->price, 0, ',', '.') }}</b>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="cart__info">
                            <div class="cart__info__section">
                                Cobraremos COP$ {{ number_format($membresia->price, 0, ',', '.') }} en tu tarjeta.
                            </div>
                            <div class="cart__info__section">
                                Dispones de un plazo de 14 días desde el momento de la compra para solicitar un reembolso. Para ello, ponte en contacto con nosotros enviando un email a <a class="link-secondary--underlined" href="mailto:support@transformatepro.com">support@transformatepro.com</a> indicándonos tu caso y te ayudaremos.
                            </div>
                        </div>
                        <div class="purchase-reasons">
                            <ul>
                                <li>
                                    <i class="fa fa-question-circle"></i> Si tienes cualquier pregunta, envíanos un email a <a class="link-secondary--underlined" href="mailto:support@transformatepro.com">support@transformatepro.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection