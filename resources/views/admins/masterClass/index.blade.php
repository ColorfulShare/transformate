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
            <i class="fas fa-microphone icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T Master Clases Disponibles</h4>                          
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
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Subtítulo</th> 
                            <th class="uk-text-center">Fecha de Creación</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($clases as $clase)
                            <tr>                                    
                                <td class="uk-text-center">{{ $clase->title }}</td> 
                                <td class="uk-text-center">{{ $clase->subtitle }}</td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$clase->created_at -5 Hours")) }}</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('landing.master-class.show', [$clase->slug, $clase->id]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a> 
                                    @if (Auth::user()->profile->master_class == 2)
                                        <a href="{{ route('admins.master-class.edit', [$clase->slug, $clase->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>
                                        <a href="{{ route('admins.master-class.change-status', [$clase->id, 0]) }}" class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: ban;" uk-tooltip="Deshabilitar"></a>
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