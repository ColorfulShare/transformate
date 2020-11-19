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
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Ventas por Curso</h4>                          
        </div>             
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Id de Compra</th> 
                            <th class="uk-text-center">Alumno</th>
                            <th class="uk-text-center">Correo</th>
                            <th class="uk-text-center">Monto Pagado</th> 
                            <th class="uk-text-center">T-Mentor</th> 
                            <th class="uk-text-center">T-Partner</th> 
                            <th class="uk-text-center">Método de Pago</th>
                            <th class="uk-text-center">Fecha</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($compras as $compra)
                            <tr>                                                                 
                                <td class="uk-text-center">#{{ $compra->purchase_id }}</td> 
                                <td class="uk-text-center">{{ $compra->purchase->user->names }} {{ $compra->purchase->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $compra->purchase->user->email }}</td>
                                <td class="uk-text-center">{{ $compra->amount }}</td> 
                                <td class="uk-text-center">
                                    @if ($compra->instructor_code == 0) 
                                        <span class="uk-label uk-label-danger">No</span> 
                                    @else 
                                        <span class="uk-label uk-label-success">Si</span> 
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    @if (!is_null($compra->partner_code))
                                        {{ $compra->partner->names }} {{ $compra->partner->last_names }}
                                    @else 
                                        N/A
                                    @endif
                                </td>
                                <td class="uk-text-center">{{ $compra->purchase->payment_method }}</td>
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$compra->created_at -5 Hours")) }} </td>                  
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                 
        </div>             
    </div>
@endsection