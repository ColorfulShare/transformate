@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-coins uk-margin-small-right"></i> Detalles del Ingreso #{{ $ingreso->id }}
            </div>              
            <div class="uk-card-body"> 
                <div uk-grid> 
                    <div class="uk-width-1-3@m uk-text-center"> 
                        <img src="{{ asset('uploads/images/users/'.$ingreso->user->avatar) }}" class="uk-border-circle" style="width: 200px;">
                    </div>                             
                    <div class="uk-width-2-3@m"> 
                        <div class="uk-grid-small" uk-grid> 
                            <div class="uk-width-1-1"> 
                                <div class="uk-form-label">
                                    Id de Cliente: <b>{{ $ingreso->user_id }}</b><br>
                                    Cliente: <b>{{ $ingreso->user->names }} {{ $ingreso->user->last_names }}</b><br>
                                    Ubicación: <b>{{ $ingreso->user->state }} ({{ $ingreso->user->country }})</b><br>
                                    Fecha de Compra: <b>{{ date('d-m-Y H:i A', strtotime("$ingreso->created_at -5 Hours")) }}</b><br>
                                    Método de Pago: <b>{{ $ingreso->payment_method }}</b><br>
                                    Id de Transacción: <b>{{ $ingreso->payment_id }}</b><br>
                                    @if (!is_null($ingreso->coupon_id)) 
                                        Cupón de Descuento: <b>{{ $ingreso->coupon->name }} ({{ $ingreso->coupon->discount }}%)</b><br>
                                    @endif

                                    Monto Original: <b>COP$ {{ number_format($ingreso->amount, 0, ',', '.') }}</b><br>
                                    Descuentos Aplicados: <br>
                                    @if (!is_null($ingreso->coupon_id)) 
                                        <label class="uk-label uk-success">- {{ $ingreso->coupon->discount }}% (Cupón de Descuento) </label><br>
                                    @endif
                                    @if ($ingreso->membership_discount == 1)
                                        <label class="uk-label uk-success" uk-tooltip="80% en T-Courses<br> 20% en T-Mentorings<br> 50% en T-Books">- Descuento por Membresía Activa</label><br>
                                    @endif
                                    @if ($ingreso->instructor_code_discount == 1)
                                        <label class="uk-label uk-success">- 10% (Descuento Acumulado por Compra Anterior)</label><br>
                                    @endif
                                    Monto Pagado: <b>COP$ {{ number_format($ingreso->amount, 0, ',', '.') }}</b><br>
                                </div> 
                            </div>                  
                        </div>                            
                    </div>                             
                </div>                         
            </div>              
        </div> 

        <div class="uk-margin-medium"> 
            <div uk-grid> 
                @if (!is_null($productos))
                    @php
                        $cont = 0;
                    @endphp
                    @foreach ($productos as $producto)
                        @php
                            $cont++;
                        @endphp
                        <div class="uk-width-1-2"> 
                            <div class="uk-card-small uk-card-default"> 
                                <div class="uk-card-header">
                                    Item #{{ $cont }}
                                </div>   
                                <div class="uk-card-body uk-padding-remove-top">  
                                    @if (!is_null($producto->course_id))
                                        Producto Comprado: <b>T-Course</b><br>
                                        Detalle del Producto: <b>{{ $producto->course->title }}</b><br>
                                    @elseif (!is_null($producto->certification_id))
                                        Producto Comprado: <b>T-Mentoring</b><br>
                                        Detalle del Producto: <b>{{ $producto->certification->title }}</b><br>
                                    @elseif (!is_null($producto->podcast_id))
                                        Producto Comprado: <b>T-Book</b><br>
                                        Detalle del Producto: <b>{{ $producto->podcast->title }}</b><br>
                                    @endif
                                    Costo del Producto: <b>{{ $producto->amount }}$</b><br>
                                    Aplicó Código de Instructor: <b>
                                        @if ($producto->instructor_code == 0) 
                                            <label class="uk-label uk-label-danger">No</label>
                                        @else
                                            <label class="uk-label uk-label-success">Si</label>
                                        @endif
                                    </b><br>
                                </div>                             
                            </div>                             
                        </div>   
                    @endforeach  
                @else                 
                    <div class="uk-width-1-1"> 
                        <div class="uk-card-small uk-card-default"> 
                            <div class="uk-card-header">
                                Detalle de Compra
                            </div>   
                            <div class="uk-card-body uk-padding-remove-top">  
                                Producto Comprado: <b>Membresía</b><br>
                                Detalle del Producto: <b>{{ $ingreso->membership->name }}</b><br>
                                Costo del Producto: <b>{{ $ingreso->amount }}$</b>                      
                            </div>                             
                        </div>
                    </div>
                @endif                 
            </div> 
        </div> 
    </div>
@endsection