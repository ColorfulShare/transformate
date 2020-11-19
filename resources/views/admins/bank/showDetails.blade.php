@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/shoppingCart.css') }}">
@endpush

@section('content')
    <div class="background-shop">
        <div style="margin-top: 30px; margin-left: 150px; margin-right: 150px;">
            @if (Session::has('msj-exitoso'))
                <div class="uk-alert-success" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-exitoso') }}</strong>
                </div>
            @endif
            
            <div class="uk-text-center" style="padding-bottom: 20px;">
                <a href="{{ route('admins.bank.update-transfer', [$datosPago->id, 1]) }}" class="uk-button uk-button-primary"><i class="fa fa-check"></i> Aprobar</a> 
                <a href="{{ route('admins.bank.update-transfer', [$datosPago->id, 2]) }}" class="uk-button uk-button-secondary"><i class="fa fa-times"></i> Denegar</a>
            </div>
            
            <div class="uk-child-width-1-2" uk-grid>
                {{-- Sección Izquierda --}}
                <div>
                    <div class="paper">
                        <div class="checkout__head">
                            <h1 class="h2">Factura Provisional de Compra</h1>
                        </div>
                        <div class="checkout__body">
                            Nombre Cliente: <b>{{ $datosPago->user->names }} {{ $datosPago->user->last_names }}</b><br>
                            Ubicación: <b>{{ $datosPago->state }} ({{ $datosPago->country }})</b><br>
                            # de Transferencia: <b>{{ $datosPago->id }}</b><br>
                            Fecha de Compra: <b>{{ date('d-m-Y H:i A', strtotime("$datosPago->created_at -5 Hours")) }}</b><br>
                            Monto Original: <b>COP$ {{ number_format($datosPago->original_amount, 0, ',', '.') }}</b><br>
                            Descuentos Aplicados: <br>
                            @if (!is_null($datosPago->coupon_id)) 
                                <label class="uk-label uk-success">- {{ $datosPago->coupon->discount }}% (Cupón de Descuento) </label><br>
                            @endif
                            @if ($datosPago->membership_discount == 1)
                                <label class="uk-label uk-success" uk-tooltip="80% en T-Courses<br> 20% en T-Mentorings<br> 50% en T-Books">- Descuento por Membresía Activa</label><br>
                            @endif
                            @if ($datosPago->instructor_code_discount == 1)
                                <label class="uk-label uk-success">- 10% (Descuento Acumulado por Compra Anterior)</label><br>
                            @endif
                            Monto Pagado: <b>COP$ {{ number_format($datosPago->amount, 0, ',', '.') }}</b><br>
                        </div>
                    </div>
                </div>

                {{-- Sección Derecha --}}
                <div>
                    <div class="paper--gray cart__box">
                        <div class="cart__box__head">
                            <div class="align-items-end">
                                <div class="mr-auto">
                                    <h2 class="h3 cart__title">Cursos Comprados <span>({{ $cantItems }})</span></h2>
                                </div>
                            </div>
                        </div>
                        <ul class="cart-list cart-list--small">
                            @foreach ($items as $item)
                                <li class="cart-list__item d-none d-md-block">
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
                                                @else
                                                    <h3 class="h4 cart-list__title">{{ $item->membership->title }}</h3>
                                                   
                                                    <div class="cart-list__img">
                                                        <picture><img class="img-item" alt="{{ $item->membership->name }}" src="{{ asset('template/images/ico8.png') }}"></picture>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="uk-width-auto@m">
                                            <div class="cart-list__price">COP$ {{ number_format($item->original_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection