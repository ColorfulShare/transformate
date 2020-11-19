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
            <i class="fas fa-user icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Cuentas Bancarias </h4> 
            @if (Auth::user()->profile->banks == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Cuenta; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-user-plus"></i> Nueva Cuenta
                </button>    
            @endif                     
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
                            <th class="uk-text-center">Banco</th> 
                            <th class="uk-text-center">Razón Social</th> 
                            <th class="uk-text-center">DNI</th>
                            <th class="uk-text-center">N°de Cuenta</th> 
                            <th class="uk-text-center">Tipo de Cuenta</th> 
                            <th class="uk-text-center">Estado</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($cuentasBancarias as $cuenta)
                            <tr>                                   
                                <td class="uk-text-center">{{ $cuenta->bank }}</td> 
                                <td class="uk-text-center">{{ $cuenta->business_name }}</td> 
                                <td class="uk-text-center">{{ $cuenta->identification }}</td> 
                                <td class="uk-text-center">{{ $cuenta->account_number}}</td> 
                                <td class="uk-text-center">{{ $cuenta->account_type }}</td> 
                                <td class="uk-text-center">
                                    @if ($cuenta->status == 0)
                                        <label class="uk-label uk-label-danger">Inactiva</label>
                                    @else
                                        <label class="uk-label uk-label-success">Activa</label>
                                    @endif
                                </td>
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.bank.show', $cuenta->id) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: search;" uk-tooltip="Ver - Editar"></a> 
                                    @if (Auth::user()->profile->banks == 2)
                                        @if ($cuenta->status == 0)
                                            <a href="{{ route('admins.bank.change-status', [$cuenta->id, 1]) }}" class="uk-icon-button uk-button-success btn-icon" uk-tooltip="Activar"><i class="fa fa-check"></i></a> 
                                        @else
                                            <a href="{{ route('admins.bank.change-status', [$cuenta->id, 0]) }}" class="uk-icon-button uk-button-secondary btn-icon" uk-tooltip="Inactivar"><i class="fa fa-times"></i></a>
                                        @endif  
                                    @endif                    
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                   
        </div>                 
    </div>

    <!-- Modal para Crear Alumno -->                     
    <div id="create-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4>Crear Cuenta Bancaria</h4> 
            </div>                    
            <form action="{{ route('admins.bank.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Banco(*):
                            <input class="uk-input" type="text" name="bank" placeholder="Nombre del Banco" required>
                        </div>
                        <div class="uk-width-1-2">
                            Razón Social (*):
                            <input class="uk-input" type="text" name="business_name" placeholder="Propietario de la Cuenta" required>
                        </div>
                        <div class="uk-width-1-2">
                            N° de Identificación (*):
                            <input class="uk-input" type="text" name="identification" placeholder="DNI" required>
                        </div>
                        <div class="uk-width-1-2">
                            Tipo de Cuenta:
                            <select class="uk-select" name="account_type" required>
                                <option value="" selected disabled>Seleccione una opción...</option>
                                <option value="Ahorro">Ahorro</option>
                                <option value="Corriente">Corriente</option>
                            </select>
                        </div>
                        <div class="uk-width-1-2">
                            N° de Cuenta (*):
                            <input class="uk-input" type="text" name="account_number" placeholder="N° de Cuenta" required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Cuenta</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection