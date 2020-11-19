@extends('layouts.coursesLanding')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
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

            <div class="uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
                {{-- Sección Izquierda --}}
                <div>
                    <div class="paper">
                        <div class="checkout__head">
                            <h1 class="h2">Factura de Compra</h1>
                        </div>
                        <div class="checkout__body">
                            # de Orden: <b>{{ $datosPago->id }}</b><br>
                            Forma de Pago: <b> {{ $datosPago->payment_method }}</b><br>
                            ID de Transacción: <b>{{ $datosPago->payment_id }}</b><br>
                            Fecha de Compra: <b>{{ date('d-m-Y H:i A', strtotime("$datosPago->created_at -5 Hours")) }}</b><br>
                            Monto Pagado: <b>COP$ {{ number_format($datosPago->amount, 0, ',', '.') }}</b>
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
                    </div>
                </div>

                {{-- Sección Derecha --}}
                <div>
                    <div class="paper--gray cart__box">
                        <div class="cart__box__head">
                            <div class="align-items-end">
                                <div class="mr-auto">
                                    <h2 class="h3 cart__title">Membresía de Regalo</h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="uk-text-center" style="padding-bottom: 15px;">
                            @foreach ($items as $item)
                                <p>
                                    <i class="fa fa-gift" style="font-size: 7em;"></i>
                                </p>
                                <p>
                                    <b>{{ $item->membership->name }}</b><br>
                                    Código de Aplicación: {{ $item->gift_code }}
                                </p>
                            @endforeach

                            <a href="#modalCorreo" uk-toggle>Enviar Regalo a Correo Electrónico</a>
                        </div>
                    </div>
                    <div>
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

    <div id="modalCorreo" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Enviar Membresía de Regalo</h4>
            </div>
            <form action="{{ route('landing.shopping-cart.send-gift-membership') }}" method="POST">
                @csrf
                <input type="hidden" name="purchase_id" value="{{ $datosPago->id }}">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el correo electrónico: </b></label>
                        <input class="uk-input uk-form-success" type="email" name="email" required>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
@endsection