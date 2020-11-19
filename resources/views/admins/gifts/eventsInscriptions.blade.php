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
            <i class="fas fa-gift icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Gifts de Eventos </h4>                    
        </div>  

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha de Compra</th> 
                            <th class="uk-text-center">Evento</th> 
                            <th class="uk-text-center">Código T-Gift</th>
                            <th class="uk-text-center">Comprador</th>
                            <th class="uk-text-center">Beneficiario</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Fecha de Aplicación</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($regalos as $regalo)
                            <tr>    
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$regalo->payment_date -5 Hours")) }}</td>
                                <td class="uk-text-center">{{ $regalo->event->title }}</td>
                                <td class="uk-text-center">{{ $regalo->gift_code }}</td>
                                <td class="uk-text-center">{{ $regalo->gift_buyer }}</td>
                                <td class="uk-text-center">@if ($regalo->gift_status == 1) {{ $regalo->names }} ({{ $regalo->email }}) @else - @endif</td>  
                                <td class="uk-text-center">@if ($regalo->gift_status == 1) Aplicado @else Sin Aplicar @endif</td>  
                                <td class="uk-text-center">@if ($regalo->gift_status == 1) {{ date('d-m-Y', strtotime("$regalo->updated_at -5 Hours")) }} @else - @endif</td>  
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                            
        </div>  
    </div>
@endsection