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
            <i class="fas fa-user-tie icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Listado de Perfiles </h4>  
            @if (Auth::user()->profile->profiles == 2) 
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Perfil; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-plus-circle"></i> Nuevo Perfil
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
                            <th class="uk-text-center uk-width-large">Perfil</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Usuarios Asignados</th>
                            @if (Auth::user()->profile->profiles == 2) 
                                <th class="uk-text-center">Acción</th>
                            @endif
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($perfiles as $perfil)
                            <tr>      
                                <td class="uk-text-center">{{ $perfil->id }}</td>                
                                <td class="uk-text-center">{{ $perfil->name }}</td>
                                <td class="uk-text-center">
                                    @if ($perfil->status == 0)
                                        <label class="uk-label uk-label-danger">Inactivo</label>
                                    @else
                                        <label class="uk-label uk-label-success">Activo</label>
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ $perfil->users_count }}</td>  
                                @if (Auth::user()->profile->profiles == 2) 
                                    <td class="uk-text-center">
                                        <a class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver - Editar" href="{{ route('admins.profiles.show', $perfil->id) }}">
                                        
                                        @if ($perfil->users_count == 0)
                                            <a class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: trash;" uk-tooltip="Eliminar" href="{{ route('admins.profiles.delete', $perfil->id) }}">
                                        @else 
                                            <a class="uk-icon-button uk-button-default btn-icon" uk-icon="icon: trash;" uk-tooltip="Perfil en Uso"></a>
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

    <!-- Modal para Crear Etiqueta-->                     
    <div id="create-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>     
            <div class="uk-modal-header"> 
                <h4> Crear Perfil Administrativo</h4> 
            </div>                    
            <form action="{{ route('admins.profiles.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Nombre del Perfil (*):
                            <input class="uk-input" type="text" name="name" required>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Usuarios (*):
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="users" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="users" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="users" value="2"> Acceso Total</label>
                            </div>
                        </div>
                         <div class="uk-width-1-1">
                            <hr>
                            Acceso a Perfiles (*):
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="profiles" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="profiles" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="profiles" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Cursos (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="courses" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="courses" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="courses" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Regalos (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="gifts" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="gifts" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="gifts" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Eventos (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="events" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="events" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="events" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Podcasts (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="podcasts" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="podcasts" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="podcasts" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Etiquetas (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="tags" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="tags" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="tags" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Pedidos (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="orders" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="orders" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="orders" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Retiros (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="liquidations" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="liquidations" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="liquidations" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Banco (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="banks" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="banks" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="banks" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Cupones (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="coupons" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="coupons" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="coupons" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Tickets (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="tickets" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="tickets" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="tickets" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Reportes (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="reports" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="reports" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="reports" value="2"> Acceso Total</label>
                            </div>
                        </div>
                        <div class="uk-width-1-1">
                            <hr>
                            Acceso a Finanzas (*):
                            <div class="uk-margin uk-child-width-auto uk-grid">
                                <label><input class="uk-radio" type="radio" name="finances" value="0" checked> Sin Acceso</label>
                                <label><input class="uk-radio" type="radio" name="finances" value="1"> Solo Lectura</label>
                                <label><input class="uk-radio" type="radio" name="finances" value="2"> Acceso Total</label>
                            </div>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Perfil</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection