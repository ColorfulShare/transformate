@extends('layouts.admin')

@push('scripts')
    <script>
         $(document).ready( function () {
            $('#datatable').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 0, "desc" ]],
                pageLength: 20,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis', 
                ]
            });
        });
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Pedidos </h4>                        
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif       
        
        <div class="uk-background-default uk-padding">
            <h5>Filtros de Búsqueda</h5>
            <form action="{{ route('admins.orders.index') }}" method="GET">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        Curso: 
                        <select class="uk-select" name="contenido">
                            <option value="" selected>Todos</option>
                            @foreach ($contenido as $conte)
                                <option value="{{ $conte->identificador }}" @if ($conte->identificador == $content) selected @endif>{{ $conte->title }} ({{ $conte->content_type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-width-1-2">
                        Instructor: 
                        <select class="uk-select" name="instructor">
                            <option value="" selected>Todos</option>
                            @foreach ($instructores as $instructor)
                                <option value="{{ $instructor->id }}" @if ($instructor->id == $instru) selected @endif>@if (!is_null($instructor->names)) {{ $instructor->names }} {{ $instructor->last_names }} @else {{ $instructor->email }} @endif</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-width-1-2">
                        Forma de Pago: 
                        <select class="uk-select" name="forma_pago">
                            <option value="" selected>Todos</option>
                            <option value="Transferencia Bancaria" @if ($forma_pago == 'Transferencia Bancaria') selected @endif>Transferencia Bancaria</option>
                            <option value="Stripe" @if ($forma_pago == 'Stripe') selected @endif>Stripe</option>
                            <option value="Paypal" @if ($forma_pago == 'Paypal') selected @endif>Paypal</option>
                            <option value="MercadoPago" @if ($forma_pago == 'MercadoPago') selected @endif>Mercado Pago</option>
                        </select>
                    </div>
                    <div class="uk-width-1-1 uk-text-center" style="padding-top: 15px;">
                        <input type="submit" class="uk-button uk-button-success" value="Buscar">
                    </div>
                </div>
            </form>
        </div><br>

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">N° de Pedido</th>
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Usuario</th> 
                            <th class="uk-text-center">Correo</th>
                            <th class="uk-text-center">Monto</th> 
                            <th class="uk-text-center">Forma de Pago</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Cupón Aplicado</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($compras as $compra)
                            <tr>   
                                <td class="uk-text-center">{{ $compra->id }}</td>    
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$compra->created_at -5 Hours")) }}</td>                                                           
                                <td class="uk-text-center">{{ $compra->user->names }} {{ $compra->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $compra->user->email }}</td>
                                <td class="uk-text-center">COP$ {{ $compra->amount }}</td> 
                                <td class="uk-text-center">{{ $compra->payment_method }}</td> 
                                <td class="uk-text-center">
                                    @if ($compra->status == 0)
                                        <label class="uk-label uk-label-warning">Pendiente</label>
                                    @elseif ($compra->status == 1)
                                        <label class="uk-label uk-label-success">Completada</label>
                                    @else 
                                        <label class="uk-label uk-label-danger">Rechazada</label>
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    @if (is_null($compra->coupon_id))
                                        <label class="uk-label uk-label-danger">No</label>
                                    @else
                                        <label class="uk-label uk-label-success">Si ({{$compra->coupon->discount}}%)</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-success" href="{{ route('admins.orders.show-bill', $compra->id) }}" uk-icon="icon: search;" uk-tooltip="Ver Factura"></a> 
                                </td>
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                            
        </div>  
    </div>
@endsection