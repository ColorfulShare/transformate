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

		function procesarPago($id_retiro){
			document.getElementById("liquidation_id").value = $id_retiro;
            var modal = UIkit.modal("#process-modal");
            modal.show(); 
		}

        function cargarDatos($id_usuario){
            var route = "https://transformatepro.com/ajax/datos-cobro-mentor/"+$id_usuario;
            //var route = "http://localhost:8000/ajax/datos-cobro-mentor/"+$id_usuario;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    $("#content").html(ans);  
                    var modal = UIkit.modal("#charging_data");
                    modal.show(); 
                }
            });
        }
	</script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-wallet icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Retiros Pendientes </h4>                        
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
                            <th class="uk-text-center">Fecha de Solicitud</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Monto</th> 
                            <th class="uk-text-center">Método de Cobro</th>
                            <th class="uk-text-center">Generada Por</th>
                            @if (Auth::user()->profile->liquidations == 2)
                                <th class="uk-text-center">Acción</th>
                            @endif
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($retiros as $retiro)
                            <tr>      
                                <td class="uk-text-center">{{ $retiro->id }}</td>
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$retiro->created_at -5 Hours")) }}</td>                                                           
                                <td class="uk-text-center">{{ $retiro->user->names }} {{ $retiro->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $retiro->amount }}</td> 
                                <td class="uk-text-center">
                                    @if (!is_null($retiro->user->charging_method))
                                        {{ $retiro->user->charging_method }}
                                    @else
                                        Sin Especificar
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    @if (is_null($retiro->admin))
                                        Mentor
                                    @else
                                        Administrador
                                    @endif
                                </td>
                                @if (Auth::user()->profile->liquidations == 2)
                                    <td class="uk-text-center">
                                        <a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Datos" onclick="cargarDatos({{ $retiro->user_id }});"></a>
                                        <a class="uk-icon-button uk-button-success" uk-icon="icon: check;" uk-tooltip="Procesar" onclick="procesarPago({{ $retiro->id }});"></a> 
                                    </td>
                                @endif
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                          
        </div>           
    </div>

    <!-- Modal para Procesar Retiro -->                     
    <div id="process-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Procesar Retiro   </h4> 
            </div>                    
            <form action="{{ route('admins.liquidations.update') }}" method="POST">  
                @csrf
                <input type="hidden" name="liquidation_id" id ="liquidation_id">

                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Id de Transacción (*):
                            <input class="uk-input" type="text" name="transaction_id" placeholder="Hash, Id de Pago, etc..." required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Procesar</button>
                </div>     
            </form>                        
        </div>                         
    </div>

    <!-- Modal para Ver Datos de Cobro-->                     
    <div id="charging_data" uk-modal> 
        <div class="uk-modal-dialog" id="content"> 
                                  
        </div>                         
    </div>
@endsection