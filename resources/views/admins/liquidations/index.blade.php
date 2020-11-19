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
            <i class="fas fa-wallet icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Retiros </h4>                        
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
                            <th class="uk-text-center">Fecha de Solicitud</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Monto</th> 
                            <th class="uk-text-center">Forma de Pago</th>
                            <th class="uk-text-center">Fecha de Procesamiento</th>
                            <th class="uk-text-center"># Transacción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($retiros as $retiro)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$retiro->created_at -5 Hours")) }}</td>                                   
                                <td class="uk-text-center">{{ $retiro->user->names }} {{ $retiro->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $retiro->amount }}</td> 
                                <td class="uk-text-center">{{ $retiro->payment_method }}</td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$retiro->processed_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">{{ $retiro->transaction_id }} </td> 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                            
        </div>  
    </div>
@endsection