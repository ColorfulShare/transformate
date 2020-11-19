@extends('layouts.instructor')

@push('scripts')
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
@endpush

@section('content')
	<div style="margin-left: 50px; margin-right: 50px;">
		@if ($errors->any())
	        <div class="uk-alert-danger" uk-alert>
	            <ul class="uk-list uk-list-bullet">
	                @foreach ($errors->all() as $error)
	                    <li>{{ $error }}</li>
	                @endforeach
	            </ul>
	        </div>
	    @endif
	    
		@if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif
        
		<div class="uk-card-default">
	        <div class="uk-card-header">
	            Mis Tickets

	            <div class="uk-align-right uk-text-right" style="color: green;">
	            	<button class="uk-button uk-button-danger" href="#create-modal" uk-toggle><i class="fa fa-plus-circle"></i> Nuevo Ticket</button>
	            </div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                           	<th class="uk-width-small uk-text-center">#</th> 
		                        <th class="uk-width-small uk-text-center">Título</th> 
		                        <th class="uk-width-small uk-text-center">Prioridad</th> 
		                        <th class="uk-width-small uk-text-center">Categoría</th>
		                        <th class="uk-width-small uk-text-center">Fecha de Creación</th>
		                        <th class="uk-width-small uk-text-center">Estado</th>
		                        <th class="uk-width-small uk-text-center">Fecha de Solución</th>
		                        <th class="uk-width-small uk-text-center">Acción</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@if ($totalTickets > 0)
		                        @foreach ($tickets as $ticket)
		                            <tr>
		                                <td class="uk-width-small uk-text-center">{{ $ticket->id }}</td>
		                                <td class="uk-width-small uk-text-center">{{ $ticket->title }}</td>
		                                <td class="uk-width-small uk-text-center">
		                                	@if ($ticket->priority == 1) 
		                                		<label class="uk-label uk-label-success">Baja</label>
		                                	@elseif ($ticket->priority == 2)
		                                		<label class="uk-label uk-label-warning">Media</label>
		                                	@else
		                                		<label class="uk-label uk-label-danger">Alta</label> 
		                                	@endif
		                                </td>
		                                <td class="uk-width-small uk-text-center">{{ $ticket->category->title }}</td>
		                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$ticket->created_at -5 Hours")) }}</td>
		                                <td class="uk-width-small uk-text-center">
		                                    @if ($ticket->status == 0)
		                                        <label class="uk-label uk-label-danger">Sin Procesar</label>
		                                    @elseif ($ticket->status == 1)
												<label class="uk-label uk-label-warning">Esperando su respuesta</label>
											@elseif ($ticket->status == 2)
												<label class="uk-label uk-label-warning">Esperando respuesta del soporte</label>
		                                    @else
		                                        <label class="uk-label uk-label-success">Resuelto</label>
		                                    @endif
		                                </td>
		                                <td class="uk-width-small uk-text-center">
		                                	@if ($ticket->status == 3)
		                                		{{ date('d-m-Y H:i A', strtotime("$ticket->resolved_at -5 Hours")) }}
		                                	@else
		                                		-
		                                	@endif
		                                </td>
		                                <td class="uk-width-small uk-text-center">
		                                	<a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver Más" href="{{ route('instructors.tickets.show', $ticket->id) }}"></a>
		                                	@if ($ticket->status != 3)
		                                		<a class="uk-icon-button uk-button-danger" uk-icon="icon: ban;" uk-tooltip="Cerrar Ticket" href="{{ route('instructors.tickets.close', $ticket->id) }}"></a> 
		                                	@endif
		                                </td>
		                            </tr>    
		                        @endforeach
		                    @else
		                    	<td colspan="8" class="uk-text-center">No posee ningún ticket de soporte aún...</td>
		                    @endif
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $tickets->links() }}
	       </div>
	    </div>  
	</div>

	<!-- Modal para Crear Ticket -->                     
    <div id="create-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Crear Nuevo Ticket de Soporte </h4> 
            </div>                    
            <form action="{{ route('instructors.tickets.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body"> 
                    <div class="uk-grid">
                    	<div class="uk-width-1-2">
                            Categoría (*):
                            <select class="uk-select" name="category_id" required>
                            	<option value="" selected disabled>Seleccione una categoría...</option>
                            	@foreach ($categorias as $categoria)
                            		<option value="{{ $categoria->id }}">{{ $categoria->title }}</option>
                            	@endforeach
                            </select>
                        </div>
                        <div class="uk-width-1-2">
                            Prioridad (*):
                            <select class="uk-select" name="priority" required>
                            	<option value="" selected disabled="">Seleccione la prioridad...</option>
                            	<option value="1">Baja</option>
                            	<option value="2">Media</option>
                            	<option value="3">Alta</option>
                            </select>
                        </div><br>
                        <div class="uk-width-1-1">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Motivo del Ticket (Máximo: 150 Caracteres)" maxlength="150" required>
                        </div><br>
                        <div class="uk-width-1-1">
                            Descripción (*):
                            <textarea class="ckeditor" name="description" rows="5"></textarea>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Ticket</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection