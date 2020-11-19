@extends('layouts.admin')

@push('scripts')
    <script>
        $(document).ready( function () {
            $('#datatable').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 0, "asc" ]],
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
            <h4 class="uk-margin-remove"> Transferencias Pendientes </h4>                 
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
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Cliente</th> 
                            @if (Auth::user()->profile->banks == 2)
                                <th class="uk-text-center">Acción</th> 
                            @endif
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($transferencias as $transferencia)
                            <tr>                                   
                                <td class="uk-text-center">{{ date('Y-m-d H:i A', strtotime("$transferencia->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center">@if (!is_null($transferencia->user->names)) {{ $transferencia->user->names }} {{ $transferencia->user->last_names }} @else {{ $transferencia->user->email }} @endif</td> 
                                @if (Auth::user()->profile->banks == 2)
                                    <td class="uk-text-center">
                                        <a href="{{ route('admins.bank.show-details', $transferencia->id) }}" class="uk-icon-button uk-button-primary btn-icon" uk-tooltip="Ver Detalles"><i class="fa fa-search"></i></a> 

                                        <a href="{{ route('admins.bank.update-transfer', [$transferencia->id, 1]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-tooltip="Aprobar"><i class="fa fa-check"></i></a> 

                                        <a href="{{ route('admins.bank.update-transfer', [$transferencia->id, 2]) }}" class="uk-icon-button uk-button-secondary btn-icon" uk-tooltip="Denegar"><i class="fa fa-times"></i></a>
                                    </td>   
                                @endif                             
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                 
        </div>          
    </div>

    <!-- Modal para Ver Detalles de la Transferencia-->                     
    <div id="modal-details" uk-modal> 
        <div class="uk-modal-dialog" id="content"> 
                                  
        </div>                         
    </div>
@endsection