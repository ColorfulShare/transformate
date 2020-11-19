@extends('layouts.instructor')

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
    </script>
@endpush

@section('content')    
    <div class="background-shop">
        <div style="margin-top: 30px; margin-left: 5%; margin-right: 5%;">
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
                                @if ($totalItems > 0)
                                    <li>
                                        <a class="uk-accordion-title accordion-paypal" href="#"><label><input class="uk-radio" type="radio"> Paypal</label></a>
                                        <div class="uk-accordion-content">
                                            <form action="{{ route('instructors.shopping-cart.paypal-checkout') }}" method="POST">
                                                @csrf
                                                @if (!is_null($cuponAplicado))
                                                    <input type="hidden" name="coupon_id" value="{{ $cuponAplicado->id }}">
                                                @endif
                                                <input type="hidden" name="amount" value="{{ $totalItems }}">     
                                                <p class="h6">Antes de seguir con la compra, inicia sesión con tu cuenta de PayPal</p>
                                                <button type="submit" class="uk-button btn-paypal uk-button-large btn-block btn-lg" @if ($totalItems == 0) disabled @endif><i class="fab fa-paypal" style="font-size: 1.500rem;"></i> Entrar en PayPal</button>
                                            </form>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="uk-accordion-title accordion-mp" href="#"><label><input class="uk-radio" type="radio"> Tarjeta de Crédito</label></a>
                                        <div class="uk-accordion-content">
                                            <form accept-charset="UTF-8" action="{{ route('instructors.shopping-cart.mercado-pago-checkout') }}" method="POST" id="pay" name="pay">
                                                @csrf
                                                <input type="hidden" name="amount" value="{{ $totalItems }}"/>
                                                <input type="hidden" name="paymentMethodId" />
                                                <div class="uk-grid">
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
                                    <li>
                                        <a class="uk-accordion-title accordion-bank" href="#"><label><input class="uk-radio radio" type="radio"> Transferencia Bancaria</label></a>
                                        <div class="uk-accordion-content">
                                            <form action="{{ route('instructors.checkout.bank-transfer-checkout') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="amount" value="{{ $totalItems }}">
                                                @if (!is_null($cuponAplicado))
                                                    <input type="hidden" name="coupon_id" value="{{ $cuponAplicado->id }}">
                                                @endif
                                                <div class="uk-grid">
                                                    <div class="uk-width-1-1">
                                                        Realiza tu pago directamente en nuestra cuenta bancaria. Por favor, usa la referencia del pedido como referencia de pago. Tu pedido no será enviado hasta que el importe haya sido recibido en nuestra cuenta.
                                                    </div>
                                                    <div class="uk-width-1-1">
                                                        <br>
                                                        <button type="submit" class="uk-button btn-primary btn-lg btn-block"><i class="fa fa-shopping-cart"></i> Completar Compra</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                @else
                                    @if ($cantItems > 0)
                                        <li>
                                            <a class="uk-accordion-title" href="#"><label><input class="uk-radio" type="radio" checked> Compra Gratis</label></a>
                                            <div class="uk-accordion-content">
                                                <a class="uk-button btn-primary btn-lg btn-block pay-payu" href="{{ route('instructors.shopping-cart.free-checkout') }}"><i class="fa fa-shopping-cart"></i> Realizar Compra</a>
                                            </div>
                                        </li>
                                    @endif
                                @endif
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
                                    <h2 class="h3 cart__title">Carrito <span>({{ $cantItems }} Item)</span></h2>
                                </div>
                            </div>
                        </div>
                        <ul class="cart-list cart-list--small">
                            @foreach ($items as $item)
                                <li class="cart-list__item">
                                    <div uk-grid>
                                        <div class="uk-width-expand@m">
                                            <div class="cart-list__details">
                                                @if (!is_null($item->course_id))
                                                    <h3 class="h4 cart-list__title">{{ $item->course->title }}</h3>
                                                    <p class="cart-list__info">Un T-Course de {{ $item->course->user->names }} {{ $item->course->user->last_names }}</p>
                                                    <div class="cart-list__img">
                                                       <picture><img class="img-item" alt="" src="{{ asset('uploads/images/courses/'.$item->course->cover)}} "></picture>
                                                    </div>
                                                @elseif (!is_null($item->certification_id))
                                                    <h3 class="h4 cart-list__title">{{ $item->certification->title }}</h3>
                                                    <p class="cart-list__info">Una T-Mentoring de {{ $item->certification->user->names }} {{ $item->certification->user->last_names }}</p>
                                                    <div class="cart-list__img">
                                                        <picture><img class="img-item" alt="{{ $item->certification->title }}" src="{{ asset('uploads/images/certifications/'.$item->certification->cover) }}"></picture>
                                                    </div>
                                                @elseif (!is_null($item->podcast_id))
                                                    <h3 class="h4 cart-list__title">{{ $item->podcast->title }}</h3>
                                                    <p class="cart-list__info">Un T-Book de {{ $item->podcast->user->names }} {{ $item->podcast->user->last_names }}</p>
                                                    <div class="cart-list__img">
                                                        <picture><img class="img-item" alt="{{ $item->podcast->title }}" src="{{ asset('uploads/images/podcasts/'.$item->podcast->cover) }}"></picture>
                                                    </div>
                                                @elseif (!is_null($item->membership_id))
                                                    <h3 class="h4 cart-list__title">{{ $item->membership->name }}</h3>
                                                   
                                                    <div class="cart-list__img">
                                                        <picture><img class="img-item" alt="T MemberPro" src="{{ asset('template/images/ico8.png') }}"></picture>
                                                    </div>
                                                 @elseif (!is_null($item->product_id))
                                                    <h3 class="h4 cart-list__title">{{ $item->market_product->name }}</h3>
                                                    <p class="cart-list__info">Un T-Product de {{ $item->market_product->user->names }} {{ $item->market_product->user->last_names }}</p>
                                                    <div class="cart-list__img">
                                                        <picture><img class="img-item" alt="{{ $item->market_product->name }}" src="{{ asset('uploads/images/products/'.$item->market_product->cover) }}"></picture>
                                                    </div>
                                                @endif
                                                <span class="price-badges">
                                                    <span class="badge badge--promo-tag-34 ">REBAJAS</span>
                                                    <span class="price-badges--prices">
                                                        <span class="price-discount t-price-discount">50% Dto.</span>
                                                        @if (!is_null($item->course_id))
                                                            <b class="price-before t-price-before">COP$ {{ number_format($item->course->price*2, 0, ',', '.') }}</b>
                                                        @elseif (!is_null($item->certification_id))
                                                            <b class="price-before t-price-before">COP$ {{ number_format($item->certification->price*2, 0, ',', '.') }}</b>
                                                        @elseif (!is_null($item->podcast_id))
                                                            <b class="price-before t-price-before">COP$ {{ number_format($item->podcast->price*2, 0, ',', '.') }}</b>
                                                        @elseif (!is_null($item->membership_id))
                                                            <b class="price-before t-price-before">COP$ {{ number_format($item->membership->price*2, 0, ',', '.') }}</b>
                                                        @elseif (!is_null($item->product_id))
                                                            <b class="price-before t-price-before">COP$ {{ number_format($item->market_product->price*2, 0, ',', '.') }}</b>
                                                        @endif
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="uk-width-auto@m">
                                            @if (is_null($cuponAplicado))
                                                @if (!is_null($item->course_id))
                                                    <div class="cart-list__price">COP$ {{ number_format($item->course->price, 0, ',', '.') }}</div>
                                                @elseif (!is_null($item->certification_id))
                                                    <div class="cart-list__price">COP$ {{ number_format($item->certification->price, 0, ',', '.') }}</div>
                                                @elseif (!is_null($item->podcast_id))
                                                    <div class="cart-list__price">COP$ {{ number_format($item->podcast->price, 0, ',', '.') }}</div>
                                                @elseif (!is_null($item->membership_id))
                                                    <div class="cart-list__price">COP$ {{ number_format($item->membership->price, 0, ',', '.') }}</div>
                                                @elseif (!is_null($item->product_id))
                                                    <div class="cart-list__price">COP$ {{ number_format($item->market_product->price, 0, ',', '.') }}</div>
                                                @endif
                                            @else
                                                @if (!is_null($item->course_id))
                                                    <div class="cart-list__price">
                                                        <b class="price-before">COP$ {{ number_format($item->course->price, 0, ',', '.')}}</b><br>
                                                        COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                    </div>
                                                @elseif (!is_null($item->certification_id))
                                                    <div class="cart-list__price">
                                                        <b class="price-before">COP$ {{ number_format($item->certification->price, 0, ',', '.')}}</b><br>
                                                        COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                    </div>
                                                @elseif (!is_null($item->podcast_id))
                                                    <div class="cart-list__price">
                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($item->podcast->price, 0, ',', '.')}}</b><br>
                                                        COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                    </div>
                                                @elseif (!is_null($item->membership_id))
                                                    <div class="cart-list__price">
                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($item->membership->price, 0, ',', '.')}}</b><br>
                                                        COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                    </div>
                                                @elseif (!is_null($item->product_id))
                                                    <div class="cart-list__price">
                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($item->market_product->price, 0, ',', '.')}}</b><br>
                                                        COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="cart__box__total">
                            <div class="cart__box__total__counts">
                                @if (is_null($cuponAplicado))
                                    <b class="cart__box__total__counts__total">Total a Pagar: COP$ {{ number_format($totalItems, 0, ',', '.') }}</b>
                                    <input type="hidden" id="totalPay" value="{{ $totalItems }}">
                                @else
                                    <b class="price-before">Total Anterior: COP$ {{ number_format($totalAnterior, 0, ',', '.')}}</b><br>
                                    <label class="uk-label uk-success">Descuento {{ $cuponAplicado->discount }}%</label><br>
                                    <b class="cart__box__total__counts__total">Total a Pagar: COP$ {{ number_format($totalItems, 0, ',', '.') }}</b>
                                @endif
                            </div>
                            <hr>
                        </div>

                        @if (is_null($cuponAplicado))
                            <div class="cart__discount__inner">
                                <a class="link-secondary cart__discount__link" id="applyCoupon" href="#">
                                    <i class="fas fa-percent i-space-right"></i> ¿Tienes un código de descuento?
                                </a>
                                <form action="{{ route('instructors.shopping-cart.apply-coupon') }}" method="POST">
                                    @csrf
                                    <div class="uk-margin uk-hidden" id="div_coupon">
                                        <input class="uk-input uk-form-width-medium input-shop" name="coupon" type="text">
                                        <input type="hidden" name="route" value="checkout">
                                        <button type="submit" class="uk-button btn-primary">Aplicar</button>
                                    </div>
                                </form>
                        </div><hr>
                        @endif
                    </div>
                    <div>
                        <div class="cart__info">
                            <div class="cart__info__section">
                                Cobraremos COP$ {{ number_format($totalItems, 0, ',', '.') }} en tu tarjeta.
                            </div>
                            <div class="cart__info__section">
                                Dispones de un plazo de 14 días desde el momento de la compra para solicitar un reembolso o cambio de curso. Para ello, ponte en contacto con nosotros enviando un email a <a class="link-secondary--underlined" href="mailto:support@transformatepro.com">support@transformatepro.com</a> indicándonos tu caso y te ayudaremos.
                            </div>
                        </div>
                        <div class="purchase-reasons">
                            <h2>¿Qué estás comprando?</h2>
                            <ul>
                                <li><i class="fa fa-smile"></i> Acceso ilimitado a los cursos que compres que nunca expira para verlos todas las veces que desees.</li>
                                <li><i class="fa fa-film"></i> Acceso por tiempo indefinido a las diferentes unidades del curso, sus lecciones, textos explicativos y recursos adicionales.</li>
                                <li><i class="fa fa-users"></i> Acceso a la comunidad exclusiva del curso donde podrás intercambiar experiencias con el profesor y otros alumnos.</li>
                            </ul>
                            <hr>
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