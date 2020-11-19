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
            <i class="fas fa-coins icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Comisiones por referencia</h4>                          
        </div>  

        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">#</th> 
                            <th class="uk-text-center">Cliente</th> 
                            <th class="uk-text-center">Referido</th> 
                            <th class="uk-text-center">Monto</th>
                            <th class="uk-text-center">Estado</th> 
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($comisiones as $comision)
                            <tr>                                 
                                <td class="uk-text-center">{{ $comision->id }}</td>
                                <td class="uk-text-center">{{ $comision->user->names }} {{ $comision->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $comision->referred->names }} {{ $comision->referred->last_names }}</td> 
                                <td class="uk-text-center">COP$ {{ $comision->amount }}</td> 
                                <td class="uk-text-center">
                                    @if ($comision->status == 0)
                                        <label class="uk-label uk-label-danger">Generada</label>
                                    @elseif ($comision->status == 1)
                                        <label class="uk-label uk-label-warning">Esperando Liquidación</label>
                                    @else
                                        <label class="uk-label uk-label-success">Liquidada</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$comision->created_at -5 Hours")) }}</td> 
                                <td class=" uk-text-center"> 
                                    <a href="{{ route('admins.commissions.show', $comision->id) }}" class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a>              
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                            
        </div>    
    </div>
@endsection