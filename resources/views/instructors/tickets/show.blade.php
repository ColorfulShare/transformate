@extends('layouts.instructor')

@push('scripts')
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
@endpush

@section('content')
    <div class="uk-container">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Detalles del Ticket #{{ $ticket->id }}
                @if ($ticket->status != 3)
                    <div class="uk-align-right uk-text-right">
                        <a class="uk-button uk-button-danger" href="{{ route('instructors.tickets.close', $ticket->id) }}">Cerrar Ticket</a>
                    </div>
                @endif
            </div>              
            <div class="uk-card-body"> 
                <div uk-grid>                            
                    <div class="uk-width-1-1"> 
                        <div class="uk-grid-small" uk-grid> 
                            <div class="uk-width-1-1"> 
                                <div class="uk-form-label">
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
                                            <label class="uk-label uk-label-warning">Esperando Su Respuesta</label>
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
                                            <i class="fas fa-comment uk-margin-small-right"></i><i class="fas fa-user uk-margin-small-right"></i> Tu Respuesta       
                                        @endif    
                                    </div>                         
                                    <div class="uk-width-auto@m uk-text-small uk-text-right"> 
                                        {{ date('d-m-Y H:i A', strtotime("$respuesta->created_at -5 Hours")) }}
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
                            <form action="{{ route('instructors.tickets.reply') }}" method="POST"> 
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <input type="hidden" name="reply_type" value="Cliente">                           
                                <div class="uk-card-body uk-padding-remove-top"> 
                                    <textarea class="ckeditor" name="reply" rows="5"></textarea>
                                </div>   
                                <div class="uk-card-footer uk-text-right">
                                    <input class="uk-button uk-button-success uk-margin" type="submit" class="button" id="btn-clave" value="Enviar Respuesta"> 
                                </div> 
                            </form>                               
                        </div>                             
                    </div>    
                @endif                     
            </div> 
        </div> 
    </div>
@endsection