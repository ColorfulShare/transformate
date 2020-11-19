@extends('layouts.admin')

@push('scripts')
    {{-- <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script> --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>

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
            <i class="fas fa-tags icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Listado de Categorías </h4> 
            @if (Auth::user()->profile->tickets == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Categoría; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-plus"></i> Nueva Categoría
                </button>   
            @endif                      
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
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Descripción</th> 
                            <th class="uk-text-center">Tickets Asignados</th>
                            @if (Auth::user()->profile->tickets == 2)
                                <th class="uk-text-center">Acción</th>
                            @endif
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($categoriasTickets as $categoria)
                            <tr>      
                                <td class="uk-text-center">{{ $categoria->id }}</td>                          
                                <td class="uk-text-center">{{ $categoria->title }}</td> 
                                <td class="uk-text-center">@if (!is_null($categoria->description)) {!! $categoria->description!!} @else Sin Descripción @endif</td>
                                <td class="uk-text-center">{{ $categoria->tickets_count }}</td>
                                @if (Auth::user()->profile->tickets == 2)
                                    <td class="uk-text-center">
                                        @if ($categoria->tickets_count > 0)
                                            <button class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar" disabled></button>
                                        @else
                                           <a class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar" href="{{ route('admins.tickets.delete-category', $categoria->id) }}"></a> 
                                        @endif
                                    </td>
                                @endif
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                   
        </div>            
    </div>  

    <!-- Modal para Crear Categoría -->                     
    <div id="create-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Crear Nueva Categoría </h4> 
            </div>                    
            <form action="{{ route('admins.tickets.create-category') }}" method="POST">  
                @csrf
                <div class="uk-modal-body"> 
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título de la Categoría" required>
                        </div>
                        <div class="uk-width-1-1">
                            Descripción:
                            <textarea class="ckeditor" name="description" rows="5"></textarea>
                            <script>
								ClassicEditor
									.create( document.querySelector( '.ckeditor' ) )
									.catch( error => {
										console.error( error );
									} );
							</script>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Categoría</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection