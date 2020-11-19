@extends('layouts.coursesLanding')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
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
                    <a class="keep-buying-header" href="{{ route('landing.courses') }}">Seguir comprando
                        <i class="to-right">→</i>
                    </a>
                </div>
            </div>

            <div class="items-content">
                <div class="uk-text-right keep-buying-card-div">
                    <a class="keep-buying-card" href="{{ route('landing.shopping-cart.checkout') }}">
                        <i class="fa fa-shopping-cart icon-cart"></i>
                        Tramitar pedido
                        <i class="to-right">→</i>
                    </a>
                </div>

                <div class="items">
                    @foreach ($items as $item)
                        <div class="uk-text-right close-div">
                            <a class="close-icon" href="{{ route('landing.shopping-cart.delete', $item->type."-".$item->id) }}"><i class="fa fa-times"></i></a>
                        </div>
                        <div uk-grid>
                            <div class="uk-width-auto">
                                @if ($item->type == 'curso')
                                    <img class="img-item" src="{{ asset('uploads/images/courses/'.$item->cover) }}">
                                @elseif ($item->type == 'certificacion')
                                    <img class="img-item" src="{{ asset('uploads/images/certifications/'.$item->cover) }}">
                                @elseif ($item->type == 'podcast')
                                    <img class="img-item" src="{{ asset('uploads/images/podcasts/'.$item->cover) }}">
                                @elseif ($item->type == 'product')
                                    <img class="img-item" src="{{ asset('uploads/images/products/'.$item->cover) }}">
                                @else
                                    <img class="img-item" src="{{ asset('template/images/ico8.png') }}">
                                @endif
                            </div>
                            <div class="uk-width-expand">
                                <div class="item-info">
                                    @if ($item->type == 'curso')
                                        <div class="item-title">{{ $item->title }}</div>
                                        <div class="item-instructor">Un curso de {{ $item->user->names }} {{ $item->user->last_names }}</div>
                                        <div class="item-discount">33.3% Dto. <span style="text-decoration: line-through;">COP$ {{ number_format($item->price*3, 0, ',', '.') }}</span></div>
                                    @elseif ($item->type == 'certificacion')
                                        <div class="item-title">{{ $item->title }}</div>
                                        <div class="item-instructor">Una mentoría de {{ $item->user->names }} {{ $item->user->last_names }}</div>
                                        <div class="item-discount">50% Dto. COP$ {{ number_format($item->price*2, 0, ',', '.') }}</div>
                                    @elseif ($item->type == 'podcast')
                                        <div class="item-title">{{ $item->title }}</div>
                                        <div class="item-instructor">Un audiolibro de {{ $item->user->names }} {{ $item->user->last_names }}</div>
                                        <div class="item-discount">50% Dto. COP$ {{ number_format($item->price*2, 0, ',', '.') }}</div>
                                    @elseif ($item->type == 'membresia')
                                        <div class="item-title">{{ $item->name }}</div>
                                    @else
                                        <div class="item-title">{{ $item->name }}</div>
                                        <div class="item-instructor">Un producto de {{ $item->user->names }} {{ $item->user->last_names }}</div>
                                    @endif
                                    @if ($item->instructor_code == 1)
                                        <span class="item-code"><i class="fa fa-check"></i> Código de Instructor Aplicado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="uk-text-right item-price">
                            COP$ {{ number_format($item->price, 0, ',', '.') }}
                        </div>
                    @endforeach
                </div>

                <div class="uk-text-right total-div">
                    Total COP$ {{ number_format($totalItems, 0, ',', '.') }}
                </div>

                <div class="buttons-div">
                    <div class="uk-child-width-1-1@s uk-child-width-1-2@m" uk-grid>
                        <div class="button-code-div">
                            <a class="button button-code" href="#addCodeModal" uk-toggle>
                                <i class="fas fa-tags"></i> Ingresa código de mentor
                            </a>
                        </div>
                        <div class="button-checkout-div">
                            <a class="button button-checkout" href="{{ route('landing.shopping-cart.checkout') }}">
                                <i class="fa fa-shopping-cart"></i> Tramitar pedido <i class="to-right">→</i>
                            </a>
                        </div>
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

    <div id="addCodeModal" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Agregar Código de Instructor</h4>
            </div>
            <form action="{{ route('landing.shopping-cart.add-code') }}" method="POST">
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Seleccione el curso: </b></label>
                        <select class="uk-select" name="item_id" required>
                            <option value="" selected disabled>Seleccione un item...</option>
                            @foreach ($items as $item)
                                @if ($item->instructor_code == 0)
                                    <option value="{{ $item->type."-".$item->id }}">{{ $item->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-margin">
                        <label class="uk-form-label" for="codigo"><b>Ingrese el código del instructor: </b></label>
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
@endsection