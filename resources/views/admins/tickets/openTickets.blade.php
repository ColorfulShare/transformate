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
            <i class="fas fa-ticket-alt icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Listado de Tickets Abiertos</h4>                     
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
                            <th class="uk-text-center">#</th> 
                            <th class="uk-text-center">Cliente</th>
                            <th class="uk-text-center">Motivo</th> 
                            <th class="uk-text-center">Prioridad</th> 
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Fecha de Creación</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($tickets as $ticket)
                            <tr>      
                                <td class="uk-text-center">{{ $ticket->id }}</td>
                                <td class="uk-text-center">{{ $ticket->user->names }} {{ $ticket->user->last_names }}</td>                           
                                <td class="uk-text-center">{{ $ticket->title }}</td> 
                                <td class="uk-text-center">
                                    @if ($ticket->priority == 1)
                                        <label class="uk-label uk-label-success">Baja</label>
                                    @elseif ($ticket->priority == 2)
                                        <label class="uk-label uk-label-warning">Media</label>
                                    @else
                                        <label class="uk-label uk-label-danger">Alta</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ $ticket->category->title }}</td>
                                <td class="uk-text-center">
                                    @if ($ticket->status == 1)
                                        <label class="uk-label uk-label-warning">Esperando Respuesta del Cliente</label>
                                    @else
                                        <label class="uk-label uk-label-danger">Esperando Respuesta del Soporte</label>
                                    @endif
                                </td>
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$ticket->created_at -5 Hours")) }}</td>
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Más" href="{{ route('admins.tickets.show', $ticket->id) }}"></a> 
                                </td>
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                              
        </div>        
    </div>
@endsection