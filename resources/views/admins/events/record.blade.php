@extends('layouts.admin')

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready( function () {
            $('#users-table').DataTable( {
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
            <i class="fas fa-users icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Eventos Deshabilitados </h4>                      
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif               
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="users-table"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Tipo</th>
                            <th class="uk-text-center">Subscripciones</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($eventos  as $evento)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime($evento->date)) }}</td>        
                                <td class="uk-text-center">{{ $evento->title }}</td>
                                <td class="uk-text-center">@if ($evento->type == 'Free') Gratuito @else Pago @endif</td>  
                                <td class="uk-text-center">{{ $evento->subscriptions_count }}</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.events.subscriptions', [$evento->slug, $evento->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Subscripciones"></a>
                                    @if (Auth::user()->profile->events == 2)
                                        <a href="{{ route('admins.events.show', [$evento->slug, $evento->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>
                                        <a href="{{ route('admins.events.disabled', [$evento->id, 1]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: check;" uk-tooltip="Habilitar"></a>
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