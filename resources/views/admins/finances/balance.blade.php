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
            <i class="fas fa-hand-holding-usd icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Balance </h4>                        
        </div>    

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha de Movimiento</th> 
                            <th class="uk-text-center"># Movimiento</th> 
                            <th class="uk-text-center">Tipo de Movimiento</th> 
                            <th class="uk-text-center">Monto</th>
                            <th class="uk-text-center">Balance</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($balance as $movimiento)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$movimiento->created_at -5 Hours")) }}</td>       
                                <td class="uk-text-center">#{{ $movimiento->operation_id }}</td>    
                                <td class="uk-text-center"><label class="uk-label @if ($movimiento->type == 'Ingreso') uk-label-success @else uk-label-danger @endif">{{ $movimiento->type }}</label></td> 
                                <td class="uk-text-center">
                                    @if ($movimiento->type == 'Ingreso')
                                        <span style="color: green;">+{{ $movimiento->amount }}$ </span>
                                    @else
                                        <span style="color: red;">-{{ $movimiento->amount }}$</span>
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    @if ($movimiento->balance >= 0)
                                        <span style="color: green;">{{ $movimiento->balance }}$</span>
                                    @else
                                        <span style="color: red;">{{ $movimiento->balance }}$</span>
                                    @endif
                                </td> 
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Detalles" @if ($movimiento->type == 'Ingreso') href="{{ route('admins.finances.show-earning', $movimiento->operation_id) }}" @else href="{{ route('admins.finances.show-expense', $movimiento->operation_id) }}" @endif></a>
                                </td>
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                    
        </div>             
    </div>
@endsection