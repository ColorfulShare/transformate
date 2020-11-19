@extends('layouts.landing')

@push('scripts')
    <script>
        function prueba2(){
            var modal = UIkit.modal("#infoModal");
            modal.show();
        }

        $(function(){
            $('#applyCoupon').on('click', function(){
                $('#div_coupon').removeClass('uk-hidden');
            });
        });

        function loadCodeModal($tipo){
            if ($tipo == 'mentor'){
                UIkit.modal("#addInstructorCodeModal").show();
            }else{
                UIkit.modal("#addPartnerCodeModal").show();
            }
        }
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@section('content')
    <div class="background-shop">
        <div class="uk-container" style="margin-top: 20px;">
            @if (!Session::has('msj-exitoso'))
                <script>
                    prueba2();
                </script>
            @endif

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
            
            <div class="uk-child-width-1-2" uk-grid>
                <div class="items-number">
                    <i class="fa fa-shopping-cart icon-cart"></i> Carrito 
                    <span>({{ $cantItems }} Items)</span>
                </div>
                <div class="uk-text-right keep-buying-header-div">
                    <a class="keep-buying-header" href="{{ route('students.courses') }}">Seguir comprando
                        <i class="to-right">→</i>
                    </a>
                </div>
            </div>
            <div class="uk-text-left checkout-total-div">
                Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
            </div>

            <div class="items-content">
                <div class="uk-text-right keep-buying-card-div">
                    <a class="keep-buying-card" href="{{ route('students.shopping-cart.checkout') }}">
                        <i class="fa fa-shopping-cart icon-cart"></i>
                        Tramitar pedido
                        <i class="to-right">→</i>
                    </a>
                </div>

                <div class="items">
                    @foreach ($items as $item)
                        <div class="uk-text-right close-div">
                            <a class="close-icon" href="{{ route('students.shopping-cart.delete', $item->id) }}"><i class="fa fa-times"></i></a>
                        </div>
                        <div uk-grid>
                            <div class="uk-width-auto">
                                @if (!is_null($item->course_id))
                                    <img class="img-item" src="{{ asset('uploads/images/courses/'.$item->course->cover) }}">
                                @elseif (!is_null($item->certification_id))
                                    <img class="img-item" src="{{ asset('uploads/images/certifications/'.$item->certification->cover) }}">
                                @elseif (!is_null($item->podcast_id))
                                    <img class="img-item" src="{{ asset('uploads/images/podcasts/'.$item->podcast->cover) }}">
                                @elseif (!is_null($item->membership_id))
                                    <img class="img-item" src="{{ asset('template/images/ico8.png') }}">
                                @elseif (!is_null($item->product_id))
                                    <img class="img-item" src="{{ asset('uploads/images/products/'.$item->market_product->cover) }}">
                                @endif
                            </div>
                            <div class="uk-width-expand">
                                <div class="item-info">
                                    @if (!is_null($item->course_id))
                                        <div class="item-title">{{ $item->course->title }}</div>
                                        <div class="item-instructor">Un curso de {{ $item->course->user->names }} {{ $item->course->user->last_names }}</div>
                                        <div class="item-discount">66.6% Dto. <span style="text-decoration: line-through;">COP$ {{ number_format($item->course->price*3, 0, ',', '.') }}</span></div>
                                    @elseif (!is_null($item->certification_id))
                                        <div class="item-title">{{ $item->course->title }}</div>
                                        <div class="item-instructor">Una mentoría de {{ $item->course->user->names }} {{ $item->course->user->last_names }}</div>
                                        <div class="item-discount">50% Dto. COP$ {{ number_format($item->course->price*2, 0, ',', '.') }}</div>
                                    @elseif (!is_null($item->podcast_id))
                                        <div class="item-title">{{ $item->podcast->title }}</div>
                                        <div class="item-instructor">Un audiolibro de {{ $item->podcast->user->names }} {{ $item->podcast->user->last_names }}</div>
                                        <div class="item-discount">50% Dto. COP$ {{ number_format($item->podcast->price*2, 0, ',', '.') }}</div>
                                    @elseif (!is_null($item->membership_id))
                                        <div class="item-title">{{ $item->membership->name }}</div>
                                    @else
                                        <div class="item-title">{{ $item->market_product->name }}</div>
                                        <div class="item-instructor">Un producto de {{ $item->market_product->user->names }} {{ $item->market_product->user->last_names }}</div>
                                    @endif
                                     @if ($item->instructor_code == 1)
                                        <span class="item-code"><i class="fa fa-check"></i> Código T-Mentor Aplicado</span><br>
                                    @endif
                                    @if (!is_null($item->partner_code))
                                        <span class="item-code"><i class="fa fa-check"></i> Código T-Partner Aplicado <br> ({{ $item->partner->names }} {{ $item->partner->last_names }})</span><br>
                                    @endif
                                    @if ($item->gift == 1)
                                        <span class="item-code"><i class="fa fa-gift"></i> T-GIFT</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="uk-text-right item-price">
                            @if (!is_null($item->course_id))
                                COP$ {{ number_format($item->course->price, 0, ',', '.') }}
                            @elseif (!is_null($item->certification_id))
                                COP$ {{ number_format($item->certification->price, 0, ',', '.') }}
                            @elseif (!is_null($item->podcast_id))
                                COP$ {{ number_format($item->podcast->price, 0, ',', '.') }}
                            @elseif (!is_null($item->membership_id))
                                COP$ {{ number_format($item->membership->price, 0, ',', '.') }}
                            @elseif (!is_null($item->product_id))
                                COP$ {{ number_format($item->market_product->price, 0, ',', '.') }}
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="uk-text-right total-div">
                    Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
                </div>

                <div class="buttons-div">
                    <div class="uk-child-width-1-1" uk-grid>
                        @if ($cantItems > $cantItemsInstructorCode)
                            <div class="uk-text-center">
                                <a class="button button-code" href="javascript:;" onclick="loadCodeModal('mentor');">
                                    <i class="fas fa-tags"></i> Ingresa código T-Mentor
                                </a>
                            </div>
                        @endif
                        @if ($cantItems > $cantItemsPartnerCode)
                            <div class="uk-text-center">
                                <a class="button button-partner-code" href="javascript:;" onclick="loadCodeModal('partner');">
                                    <i class="fas fa-tags"></i> Ingresa código T-Partner
                                </a>
                            </div>
                        @endif
                        @if ($cantItems > 0)
                            @if ($cantItems > $cantGifts)
                                <div class="uk-text-center">
                                    <a class="button button-code" href="#giftModal" uk-toggle>
                                        <i class="fa fa-gift"></i> Compra Tu T-Gift Card
                                    </a>
                                </div>
                            @endif
                            <div class="uk-text-center">
                                <a class="button button-checkout" href="{{ route('students.shopping-cart.checkout') }}">
                                    <i class="fa fa-shopping-cart"></i> Tramitar pedido <i class="to-right">→</i>
                                </a>
                            </div>
                        @endif
                    </div>
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

    <div id="addInstructorCodeModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Aplicar Código de Mentor</h4>
            </div>
            <form action="{{ route('students.shopping-cart.add-code') }}" method="POST">
                @csrf
                <input type="hidden" name="type_code" value="mentor">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Seleccione el contenido: </b></label>
                        <select class="uk-select" name="item_id" required>
                            <option value="" selected disabled>Seleccione un item...</option>
                            @foreach ($items as $item)
                                @if ($item->instructor_code == 0)
                                    @if (!is_null($item->course_id))
                                        <option value="{{ $item->id }}">{{ $item->course->title }}</option>
                                    @elseif (!is_null($item->certification_id))
                                        <option value="{{ $item->id }}">{{ $item->certification->title }}</option>
                                    @elseif (!is_null($item->podcast_id))
                                        <option value="{{ $item->id }}">{{ $item->podcast->title }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el código: </b></label>
                        <input class="uk-input uk-form-success" type="text" id="codigo" name="codigo" required>
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
                <h4>Aplicar Código T-Partner</h4>
            </div>
            <form action="{{ route('students.shopping-cart.add-code') }}" method="POST">
                @csrf
                <input type="hidden" name="type_code" value="partner">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Seleccione el contenido: </b></label>
                        <select class="uk-select" name="item_id" required>
                            <option value="" selected disabled>Seleccione un item...</option>
                            @foreach ($items as $item)
                                @if (is_null($item->partner_code))
                                    @if (!is_null($item->course_id))
                                        <option value="{{ $item->id }}">{{ $item->course->title }}</option>
                                    @elseif (!is_null($item->certification_id))
                                        <option value="{{ $item->id }}">{{ $item->certification->title }}</option>
                                    @elseif (!is_null($item->podcast_id))
                                        <option value="{{ $item->id }}">{{ $item->podcast->title }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el código: </b></label>
                        <input class="uk-input uk-form-success" type="text" id="codigo" name="codigo" required>
                        </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Aplicar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="giftModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Regalar Contenido</h4>
            </div>
            <form action="{{ route('students.shopping-cart.add-gift') }}" method="POST">
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Seleccione el contenido que desea comprar para regalar: </b></label>
                        <select class="uk-select" name="item_id" required>
                            <option value="" selected disabled>Seleccione un item...</option>
                            @foreach ($items as $item)
                                @if ($item->gift == 0)
                                    @if (!is_null($item->course_id))
                                        <option value="{{ $item->id }}">{{ $item->course->title }}</option>
                                    @elseif (!is_null($item->certification_id))
                                        <option value="{{ $item->id }}">{{ $item->certification->title }}</option>
                                    @elseif (!is_null($item->podcast_id))
                                        <option value="{{ $item->id }}">{{ $item->podcast->title }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
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