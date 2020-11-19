@extends('layouts.admin')

@push('scripts')
    <script>
         $(document).ready( function () {
            $('#datatable').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 1, "asc" ]],
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
            <i class="fas fa-tag icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Historial de Aplicaciones </h4>                       
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif       

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha de Aplicación</th>
                            <th class="uk-text-center">Cupón</th> 
                            <th class="uk-text-center">Usuario</th> 
                            <th class="uk-text-center"># Compra</th>
                            <th class="uk-text-center">Monto Original</th>
                            <th class="uk-text-center">Descuento</th>
                            <th class="uk-text-center">Monto Final</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($cuponesAplicados as $cupon)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$cupon->apply_date -5 Hours")) }}</td>  
                                <td class="uk-text-center">{{ $cupon->name }}</td> 
                                <td class="uk-text-center">{{ $cupon->user }}</td> 
                                <td class="uk-text-center">#{{ $cupon->purchase_id }}</td> 
                                <td class="uk-text-center">COP$ {{ $cupon->original_amount }}</td>
                                <td class="uk-text-center">{{ $cupon->discount }}%</td> 
                                <td class="uk-text-center">COP$ {{ $cupon->amount}}</td> 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                            
        </div>             
    </div>
@endsection