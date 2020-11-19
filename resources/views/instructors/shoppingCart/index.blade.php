@extends('layouts.instructor')

@push('scripts')
    <script>
        $(function(){
            $('#applyCoupon').on('click', function(){
                $('#div_coupon').removeClass('uk-hidden');
            });
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@section('content')
    <div class="background-shop">
        <div class="uk-container uk-margin-medium-top">
            @if (Session::has('msj-exitoso'))
                <div class="uk-alert-success" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-exitoso') }}</strong>
                </div>
            @endif

            @if (Session::has('msj-informativo'))
                <div class="uk-alert-primary" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-informativo') }}</strong>
                </div>
            @endif

            @if (Session::has('msj-erroneo'))
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-erroneo') }}</strong>
                </div>
            @endif
            
            <div class="uk-container" style="margin-top: 30px;">
                <div uk-grid>
                    <div class="uk-width-1-1">
                        <div>
                            <header class="page-header page-header--no-border">
                                <div class="align-items-end" uk-grid>
                                    <div class="uk-width-Expand"> 
                                        <h1 class="h2 cart__title">
                                            <i class="fa fa-shopping-cart i-space-right"></i> Carrito 
                                            <span>({{ $cantItems }} Items)</span>
                                        </h1>
                                    </div>
                                    <div class="uk-flex uk-flex-middle uk-visible@m">
                                        <a class="link-secondary" href="{{ route('landing.marketplace') }}">Continuar comprando
                                            <i class="to-right">→</i>
                                        </a>
                                    </div>
                                </div>
                            </header>

                            <div class="paper cart__box">
                                <div class="paper__body">
                                    <div class="btn-toolbar cart__box__total__actions cart__box__total__actions--top uk-text-right">
                                        <a class="link-primary" href="{{ route('instructors.shopping-cart.checkout') }}">
                                            <i class="fa fa-shopping-cart i-space-right"></i>
                                            Tramitar pedido
                                            <i class="to-right">→</i>
                                        </a>
                                    </div>
                                    <ul class="cart-list t-cart-list">
                                        @foreach ($items as $item)
                                            <li class="cart-list__item">
                                                <div uk-grid>
                                                    <div class="uk-width-expand@m">
                                                        <div class="cart-list__details">
                                                            @if (!is_null($item->membership_id))
                                                                <h3 class="h4 cart-list__title">{{ $item->membership->name }}</h3>
                                                                <div class="cart-list__img">
                                                                    <picture>
                                                                        <img class="img-item" src="{{ asset('template/images/ico8.png') }}">
                                                                    </picture>
                                                                </div>
                                                             @elseif (!is_null($item->product_id))
                                                                <h3 class="h4 cart-list__title">{{ $item->market_product->name }}</h3>
                                                                <p class="cart-list__info">Un producto de {{ $item->market_product->user->names }} {{ $item->market_product->user->last_names }}</p>

                                                                <div class="cart-list__img">
                                                                    <picture>
                                                                        <img class="img-item" src="{{ asset('uploads/images/products/'.$item->market_product->cover) }}">
                                                                    </picture>
                                                                </div>
                                                            @endif

                                                            <span class="price-badges">
                                                                <span class="badge badge--promo-tag-34">REBAJAS</span>
                                                                <span class="price-badges--prices">
                                                                    <span class="price-discount t-price-discount">80% Dto.</span>
                                                                    <b class="price-before t-price-before">
                                                                        @if (!is_null($item->membership_id))
                                                                            COP$ {{ number_format($item->membership->price*5, 0, ',', '.') }}
                                                                        @elseif (!is_null($item->product_id))
                                                                            COP$ {{ number_format($item->market_product->price*5, 0, ',', '.') }}
                                                                        @endif
                                                                    </b>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-auto@m">
                                                        <div class="cart-list__price">
                                                            @if (is_null($cuponAplicado))
                                                                @if (!is_null($item->membership_id))
                                                                    COP$ {{ number_format($item->membership->price, 0, ',', '.') }}
                                                                @elseif (!is_null($item->product_id))
                                                                    COP$ {{ number_format($item->market_product->price, 0, ',', '.') }}
                                                                @endif
                                                            @else
                                                                @if (!is_null($item->membership_id))
                                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($item->membership->price, 0, ',', '.')}}</b><br>
                                                                    COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                                @elseif (!is_null($item->product_id))
                                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($item->market_product->price, 0, ',', '.')}}</b><br>
                                                                    COP$ {{ number_format($item->new_price, 0, ',', '.') }}
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <a class="close cart-list__remove" rel="nofollow" href="{{ route('instructors.shopping-cart.delete', $item->id) }}">×</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="cart__box__total">
                                        <div class="uk-text-right order-lg-last">
                                            <div class="cart__box__total__counts">
                                                @if (is_null($cuponAplicado))
                                                    <b class="cart__box__total__counts__total">
                                                        Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
                                                    </b>
                                                @else
                                                    <b class="price-before">Total Anterior: COP$ {{ number_format($totalAnterior, 0, ',', '.') }}</b><br>
                                                    <label class="uk-label uk-success">Descuento {{ $cuponAplicado->discount }}%</label><br>
                                                    <b class="cart__box__total__counts__total">Total a Pagar: COP$ {{ number_format($totalItems, 0, ',', '.')  }}</b>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="btn-toolbar cart__box__total__actions d-block clearfix">
                                            <a class="btn btn-primary btn-lg" href="{{ route('instructors.shopping-cart.checkout') }}"><i class="fa fa-shopping-cart"></i>
                                                Tramitar pedido
                                                <i class="to-right">→</i>
                                            </a>
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
                                <br>
                                <hr>  
                                <div class="uk-grid uk-child-width-1-2">
                                    <div class="uk-text-left">
                                        @if (is_null($cuponAplicado)) 
                                            <a class="link-secondary cart__discount__link" id="applyCoupon">
                                                <i class="fas fa-percent i-space-right"></i> ¿Tienes un código de descuento?
                                            </a>
                                            <form action="{{ route('instructors.shopping-cart.apply-coupon') }}" method="POST">
                                                @csrf
                                                <div class="uk-margin uk-hidden" id="div_coupon">
                                                    <input class="uk-input uk-form-width-medium" name="coupon" type="text">
                                                    <input type="hidden" name="route" value="shopping-cart">
                                                    <button type="submit" class="uk-button btn-primary">Aplicar</button>
                                                </div>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection