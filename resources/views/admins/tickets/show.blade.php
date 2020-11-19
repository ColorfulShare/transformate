@extends('layouts.admin')

@push('scripts')
    {{-- <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Detalles del Ticket #{{ $ticket->id }}
            </div>              
            <div class="uk-card-body"> 
                <div uk-grid> 
                    <div class="uk-width-1-3@m uk-text-center"> 
                        <img src="{{ asset('uploads/images/users/'.$ticket->user->avatar) }}" class="uk-border-circle" style="width: 300px;">
                    </div>                             
                    <div class="uk-width-2-3@m"> 
                        <div class="uk-grid-small" uk-grid> 
                            <div class="uk-width-1-1"> 
                                <div class="uk-form-label">
                                    Id de Cliente: <b>{{ $ticket->user_id }}</b><br>
                                    Cliente: <b>{{ $ticket->user->names }} {{ $ticket->user->last_names }} @if ($ticket->user->role_id == 1) (Alumno) @elseif ($ticket->role_id == 2) (Mentor) @endif </b><br>
                                    Ubicación: <b>{{ $ticket->user->state }} ({{ $ticket->user->country }})</b><br>
                                    Fecha del Ticket: <b>{{ date('d-m-Y H:i A', strtotime("$ticket->created_at -5 Hours")) }}</b><br>
                                    Categoría del Ticket: <b>{{ $ticket->category->title }}</b><br>
                                    Prioridad del Ticket: <b> 
                                        @if ($ticket->priority == 1)
                                            <label class="uk-label uk-label-success">Baja</label>
                                        @elseif ($ticket->priority == 2)
                                            <label class="uk-label uk-label-warning">Media</label>
                                        @else
                                            <label class="uk-label uk-label uk-label-danger">Alta</label>
                                        @endif
                                    </b><br>
                                    Motivo del Ticket: <b>{{ $ticket->title }}</b><br>
                                    Descripción del Ticket: <b>{!! $ticket->description !!}</b><br>
                                    Estatus del Ticket: <b>
                                        @if ($ticket->status == 0)
                                            <label class="uk-label uk-label-danger">Sin Revisar</label>
                                        @elseif ($ticket->status == 1)
                                            <label class="uk-label uk-label-warning">Esperando Respuesta Cliente</label>
                                        @elseif ($ticket->status == 2)
                                            <label class="uk-label uk-label-warning">Esperando Respuesta Soporte</label>
                                        @else
                                            <label class="uk-label uk-label-success">Resuelto</label>
                                        @endif
                                    </b><br>
                                    @if ($ticket->status == 3)
                                        Fecha de Solución: <b>{{ date('d-m-Y H:i A', strtotime("$ticket->resolved_at -5 Hours")) }}</b>
                                    @endif
                                </div> 
                            </div>                  
                        </div>                            
                    </div>                             
                </div>                         
            </div>              
        </div> 

        <div class="uk-margin-medium"> 
            <div uk-grid> 
                @foreach ($respuestas as $respuesta)
                    <div class="uk-width-1-1"> 
                        <div class="uk-card-small uk-card-default"> 
                            <div class="uk-card-header">
                                <div uk-grid> 
                                    <div class="uk-width-expand@m"> 
                                        @if ($respuesta->reply_type == 'Soporte')
                                            <i class="fas fa-comment uk-margin-small-right"></i><i class="fas fa-user-tie uk-margin-small-right"></i> {{ $respuesta->user->names }} {{ $respuesta->user->last_names }} 
                                        @else
                                            <i class="fas fa-comment uk-margin-small-right"></i><i class="fas fa-user uk-margin-small-right"></i> {{ $respuesta->user->names }} {{ $respuesta->user->last_names }}       
                                        @endif    
                                    </div>                         
                                    <div class="uk-width-auto@m uk-text-small uk-text-right"> 
                                        {{ date('d-m-Y H:i', strtotime("$respuesta->created_at -5 Hours")) }}
                                    </div>                         
                                </div>  
                            </div>   
                            <div class="uk-card-body uk-padding-remove-top">   
                                {!! $respuesta->reply !!}
                            </div>                             
                        </div>                             
                    </div>   
                @endforeach                   
                
                @if ($ticket->status != 3)
                    <div class="uk-width-1-1"> 
                        <div class="uk-card-small uk-card-default"> 
                            <div class="uk-card-header"> 
                                <i class="fas fa-comment uk-margin-small-right"></i><i class="fas fa-keyboard uk-margin-small-right"></i> Responder Ticket
                            </div>      
                            <form action="{{ route('admins.tickets.reply') }}" method="POST"> 
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <input type="hidden" name="reply_type" value="Soporte">                           
                                <div class="uk-card-body uk-padding-remove-top"> 
                                    <textarea class="ckeditor" name="reply" rows="5"></textarea>
                                    <script>
                                        ClassicEditor
                                            .create( document.querySelector( '.ckeditor' ) )
                                            .catch( error => {
                                                console.error( error );
                                            } );
                                    </script>
                                </div>   
                                <div class="uk-card-footer uk-text-right">
                                    @if (Auth::user()->profile->tickets == 2)
                                        <input class="uk-button uk-button-grey uk-margin" type="submit" value="Enviar Respuesta"> 
                                    @else
                                        <input class="uk-button uk-button-default uk-margin" value="Enviar Respuesta" disabled> 
                                    @endif
                                </div> 
                            </form>                               
                        </div>                             
                    </div>    
                @endif                     
            </div> 
        </div> 
    </div>
@endsection