@extends('layouts.student2')

@push('scripts')
    <script>
        function detallesCompra($id_compra){
            //var route = "https://transformatepro.com/ajax/detalles-compra/"+$id_compra;
            var route = "http://localhost:8000/ajax/detalles-compra/"+$id_compra;
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#details").html(ans);  
                    UIkit.modal("#modal-details").show();                  
                }
            });
        }        
    </script>
@endpush

@section('content')
    <div class="uk-container uk-margin-medium-bottom padding-top">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Historial de Compras </h4>                   
        </div>
        
        <div class="uk-background-default"> 
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                        <tr>
                            <th class="uk-text-center uk-width-small"># Orden</th> 
                            <th class="uk-text-center uk-width-medium">Fecha</th>
                            <th class="uk-text-center uk-table-medium">Compra</th> 
                            <th class="uk-width-medium">Descripción</th> 
                            <th class="uk-text-center uk-width-small">Precio Total</th>
                            <th class="uk-text-center uk-width-medium">Tipo de Pago</th> 
                            <th class="uk-text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            <tr>     
                                <td class="uk-text-center">{{ $compra->id }}</td>                          
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$compra->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">
                                    @if ($compra->details_count == 1)
                                        @foreach ($compra->details as $producto)
                                            @if (!is_null($producto->course_id))
                                                <img  src="{{ asset('uploads/images/courses/'.$producto->course->cover) }}" style="width: 50%;">
                                            @elseif (!is_null($producto->certification_id))
                                                <img  src="{{ asset('uploads/images/certifications/'.$producto->certification->cover) }}" style="width: 50%;">
                                            @elseif (!is_null($producto->podcast_id))
                                                <img src="{{ asset('uploads/images/podcasts/'.$producto->podcast->cover) }}" style="width: 50%;">
                                            @elseif (!is_null($producto->product_id))
                                                <img src="{{ asset('uploads/images/products/'.$producto->market_product->cover) }}" style="width: 50%;">
                                            @elseif (!is_null($compra->membership_id))
                                                <img src="{{ asset('uploads/images/memberships/'.$producto->membership->image) }}" style="width: 50%;">
                                            @endif
                                        @endforeach
                                    @else
                                        <img src="{{ asset('template/images/carrito-compra.png') }}" style="width: 30%;">
                                    @endif
                                </td> 
                                <td>
                                    @if ($compra->details_count == 1)
                                        @foreach ($compra->details as $producto)
                                            @if (!is_null($producto->course_id)) 
                                                {{ $producto->course->title }}
                                            @elseif (!is_null($producto->certification_id))
                                                {{ $producto->certification->title }}
                                            @elseif (!is_null($producto->podcast_id))
                                                {{ $producto->podcast->title }}
                                            @elseif (!is_null($producto->product_id))
                                                {{ $producto->market_product->name }}
                                            @elseif (!is_null($compra->membership_id))
                                                {{ $compra->membership->name }}
                                            @endif
                                        @endforeach
                                    @else
                                        {{ $compra->details_count }} Items Comprados. <br>
                                        <a href="#" onclick="detallesCompra({{ $compra->id }});">Ver Detalles</a>
                                    @endif
                                </td> 
                                <td class="uk-text-center">COP$ {{ number_format($compra->amount, 0, ',', '.') }}</td> 
                                <td class="uk-text-center">{{ $compra->payment_method }}</td>
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('students.purchases.show-bill', $compra->id) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: file-text;" uk-tooltip="Ver Recibo"></a>         
                                </td>                                 
                            </tr>   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-details" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h2 class="uk-modal-title">Detalles de Compra</h2>
            </div>
            <div class="uk-modal-body" id="details">
                
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>
            </div>
        </div>
    </div>
    @endsection