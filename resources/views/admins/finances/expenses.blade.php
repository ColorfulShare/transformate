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
            <i class="fas fa-money-check-alt icon-large uk-margin-right"></i>
            <h4 class="uk-margin-remove"> Egresos </h4>                        
        </div>    

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha de Egreso</th> 
                            <th class="uk-text-center"># Retiro</th> 
                            <th class="uk-text-center">Cliente</th> 
                            <th class="uk-text-center">Monto</th>
                            <th class="uk-text-center">Método de Pago</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($egresos as $egreso)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$egreso->created_at -5 Hours")) }}</td>       
                                <td class="uk-text-center">{{ $egreso->id }}</td>    
                                <td class="uk-text-center">{{ $egreso->user->names }} {{ $egreso->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $egreso->amount }}$</td> 
                                <td class="uk-text-center">{{ $egreso->payment_method }}</td>
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Detalles" href="{{ route('admins.finances.show-expense', $egreso->id) }}"></a>
                                </td> 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                        
        </div>                 
    </div>
@endsection