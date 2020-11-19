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
            <i class="fas fa-university icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Historial de Transferencias Bancarias </h4>                 
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif               
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold">
                            <th class="uk-text-center">#</th>
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Cliente</th> 
                            <th class="uk-text-center">Banco</th>
                            <th class="uk-text-center">Monto</th> 
                            <th class="uk-text-center">N° Referencia</th>
                            <th class="uk-text-center">Estado</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($transferencias as $transferencia)
                            <tr>  
                                <td class="uk-text-center">{{ $transferencia->id }}</td>                                 
                                <td class="uk-text-center">{{ date('Y-m-d', strtotime("$transferencia->date -5 Hours")) }}</td> 
                                <td class="uk-text-center">{{ $transferencia->user->names }} {{ $transferencia->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $transferencia->bank_account->bank }}</td> 
                                <td class="uk-text-center">{{ $transferencia->amount }}</td> 
                                <td class="uk-text-center">{{ $transferencia->payment_id }}</td> 
                                <td class="uk-text-center">
                                    @if ($transferencia->status == 2)
                                        <label class="uk-label uk-label-danger">Denegada</label>
                                    @else
                                        <label class="uk-label uk-label-success">Aprobada</label>
                                    @endif
                                </td>                                
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                  
        </div>          
    </div>
@endsection