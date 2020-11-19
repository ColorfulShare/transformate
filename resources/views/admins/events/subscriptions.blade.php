@extends('layouts.admin')

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready( function () {
            $('#users-table').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 0, "desc" ]],
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
            <h4 class="uk-margin-remove"> Subscripciones en el Evento {{ $evento->title }} </h4>              
        </div>  

        <div class="uk-margin-small-bottom">
            @if (Auth::user()->profile->events == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#email-modal" uk-tooltip="title: Enviar Correo Electrónico; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fa fa-envelope"></i> Enviar Correo a Todos
                </button> 
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#email-selection-modal" uk-tooltip="title: Enviar Correo Electrónico; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fa fa-envelope"></i> Enviar Correo por Selección
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
                <table id="users-table"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Nombre</th> 
                            <th class="uk-text-center">País</th> 
                            <th class="uk-text-center">Correo</th> 
                            <th class="uk-text-center">Teléfono</th> 
                            <th class="uk-text-center">¿Cómo se enteró?</th> 
                            <th class="uk-text-center">T-Mentor</th> 
                            <th class="uk-text-center">T-Partner</th> 
                            <th class="uk-text-center">T-Gift</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($evento->subscriptions  as $suscriptor)
                            <tr>      
                                <td class="uk-text-center">{{ date('d-m-Y H:i', strtotime("$suscriptor->created_at -5 Hours")) }}</td>
                                <td class="uk-text-center">{{ $suscriptor->names }}</td> 
                                <td class="uk-text-center">{{ $suscriptor->country }}</td> 
                                <td class="uk-text-center">{{ $suscriptor->email }}</td> 
                                <td class="uk-text-center">{{ $suscriptor->phone }}</td> 
                                <td class="uk-text-center">{{ $suscriptor->reason }}</td> 
                                <td class="uk-text-center">@if (is_null($suscriptor->instructor_code)) N/A @else {{ $suscriptor->instructor->names }} {{ $suscriptor->instructor->last_names }} @endif</td> 
                                <td class="uk-text-center">@if (is_null($suscriptor->partner_code)) N/A @else {{ $suscriptor->partner->names }} {{ $suscriptor->partner->last_names }} @endif</td>
                                <td class="uk-text-center">@if ($suscriptor->gift == 1) SI @else NO @endif</td> 
                                <td class="uk-text-center">
                                    @if ($evento->type == 'free')
                                        Aprobado
                                    @else
                                        @if ($suscriptor->status == 0)
                                            Pendiente
                                        @elseif ($suscriptor->status == 1)
                                            Aprobado
                                        @else
                                            Rechazado
                                        @endif
                                    @endif
                                </td>
                                <td class="uk-flex-inline uk-text-center">
                                    <a href="{{ route('admins.events.show-subscription', $suscriptor->id) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Más"></a>
                                    <a href="{{ route('admins.events.delete-subscription', $suscriptor->id) }}" class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: trash;" uk-tooltip="Eliminar Suscriptor"></a>
                                </td>              
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                         
        </div>                 
    </div>

    <!-- Modal para Enviar Correo Electrónico -->                     
    <div id="email-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Enviar Correo Electrónico </h4> 
            </div>                    
            <form action="{{ route('admins.events.send-mail') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <input type="hidden" name="event_id" value="{{ $evento->id }}">
                <input type="hidden" name="event_slug" value="{{ $evento->slug }}">
                <input type="hidden" name="selection" value="0">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título del correo" required>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Descripción (*):
                            <textarea class="ckeditor" name="description" rows="10"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '.ckeditor' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Archivo Adjunto:
                            <input class="uk-input" type="file" name="adjunto">
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Enviar Correo</button>
                </div>     
            </form>                        
        </div>                         
    </div>

    <!-- Modal para Enviar Correo Electrónico por Selección-->                     
    <div id="email-selection-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Seleccionar Suscriptores para el Correo </h4> 
            </div>                    
            <form action="{{ route('admins.events.send-mail') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <input type="hidden" name="event_id" value="{{ $evento->id }}">
                <input type="hidden" name="event_slug" value="{{ $evento->slug }}">
                <input type="hidden" name="selection" value="1">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título del correo" required>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Descripción (*):
                            <textarea class="ckeditor" id="description" name="description"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '#description' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Archivo Adjunto:
                            <input class="uk-input" type="file" name="adjunto">
                        </div>
                    </div>  
                    <div>
                        <table class="uk-table uk-table-striped uk-table-hover uk-table-small"> 
                            <thead> 
                                <tr class="uk-text-small uk-text-bold"> 
                                    <th></th>
                                    <th class="uk-text-center">Suscriptor</th> 
                                    <th class="uk-text-center">Correo</th> 
                                    <th class="uk-text-center">Estado</th>
                                </tr>                             
                            </thead>                         
                            <tbody> 
                                @foreach ($evento->subscriptions  as $suscriptor2)
                                    <tr>      
                                        <td><label><input class="uk-checkbox" type="checkbox" name="suscriptors_selected[]" value="{{ $suscriptor2->id }}"></label></td>
                                        <td class="uk-text-center">{{ $suscriptor2->names }}</td>  
                                        <td class="uk-text-center">{{ $suscriptor2->email }}</td> 
                                        <td class="uk-text-center">
                                            @if ($evento->type == 'free')
                                                <label class="uk-label uk-label-success">Aprobado</label>
                                            @else
                                                @if ($suscriptor2->status == 0)
                                                    <label class="uk-label uk-label-warning">Pendiente</label>
                                                @elseif ($suscriptor2->status == 1)
                                                    <label class="uk-label uk-label-success">Aprobado</label>
                                                @else
                                                <label class="uk-label uk-label-danger">Rechazado</label>
                                                @endif
                                            @endif
                                        </td>             
                                    </tr>   
                                @endforeach  
                            </tbody>                         
                        </table>     
                    </div>                            
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Enviar Correo</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection