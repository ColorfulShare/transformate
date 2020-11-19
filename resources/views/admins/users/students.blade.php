@extends('layouts.admin')

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script>
        $(document).ready( function () {
            $('#users-table').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 1, "asc" ]],
                pageLength: 15,
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

            $('#table2').DataTable( {
                dom: '<f<t>ip>',
                responsive: true,
                order: [[ 1, "asc" ]],
                pageLength: 25
            });
        });

        function verificarCorreo(){
            $correo = document.getElementById("email").value; 

            var route = "https://transformatepro.com/ajax/verificar-correo/"+$correo;
            //var route = "http://localhost:8000/ajax/verificar-correo/"+$correo;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    console.log(ans);
                    if (ans == 1){
                        document.getElementById("div_error").innerHTML = 'El correo ingresado ya se encuentra registrado.';
                        document.getElementById("div_error").style.display = 'block';
                        document.getElementById("btn-crear").disabled = true;
                    }else{
                        document.getElementById("div_error").style.display = 'none';
                        document.getElementById("btn-crear").disabled = false;
                    }
                }
            });
        }

        function verificarClaves(){
            if (document.getElementById("password").value == document.getElementById("password2").value){
                document.getElementById("div_error").style.display = 'none';
                document.getElementById("btn-crear").disabled = false;
            }else{
                document.getElementById("div_error").innerHTML = 'Las contraseñas ingresadas no coinciden.';
                document.getElementById("div_error").style.display = 'block';
                document.getElementById("btn-crear").disabled = true;
            }
        }
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-users icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Alumnos </h4>                
        </div>  

        <div class="uk-margin-small-bottom">
            @if (Auth::user()->profile->users == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Alumno; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-user-plus"></i> Nuevo Alumno
                </button>  
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
                            <th class="uk-text-center">Nombres</th> 
                            <th class="uk-text-center">Correo</th> 
                            <th class="uk-text-center">Ubicación</th> 
                            <th class="uk-text-center">Miembro desde</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($estudiantes as $estudiante)
                            <tr>                                    
                                <td class="uk-text-center">{{ $estudiante->names }} {{ $estudiante->last_names }}</td> 
                                <td class="uk-text-center">{{ $estudiante->email }}</td> 
                                <td class="uk-text-center">{{ $estudiante->country }} ({{ $estudiante->city }})</td> 
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$estudiante->created_at -5 Hours")) }}</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.users.show', $estudiante->id) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver - Editar"></a> 
                                    @if (Auth::user()->profile->users == 2)
                                        <form action="{{ route('admins.users.change-status') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $estudiante->id }}">
                                            @if ($estudiante->status == 1)
                                                <input type="hidden" name="action" value="0">
                                                <button class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: trash;" uk-tooltip="Eliminar"></button>
                                            @else
                                                <input type="hidden" name="action" value="1">
                                                <button class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: refresh;" uk-tooltip="Restaurar"></button>
                                            @endif
                                        </form> 
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
                <h4> Crear Nuevo Alumno   </h4> 
            </div>                    
            <form action="{{ route('admins.users.store') }}" method="POST">  
                @csrf
                <input type="hidden" name="role_id" value="1">

                <div class="uk-modal-body">
                    <div class="uk-alert-danger" uk-alert id="div_error" style="display: none;"></div> 
                    <div class="uk-grid">
                        <div class="uk-width-1-2">
                            Nombres (*):
                            <input class="uk-input" type="text" name="names" placeholder="Nombres" required>
                        </div>
                        <div class="uk-width-1-2">
                            Apellidos (*):
                            <input class="uk-input" type="text" name="last_names" placeholder="Apellidos" required>
                        </div>
                        <div class="uk-width-1-2">
                            Correo Electrónico (*):
                            <input class="uk-input" type="email" name="email" id="email" placeholder="Correo Electrónico" onkeyup="verificarCorreo();" required>
                        </div>
                        <div class="uk-width-1-2">
                            Teléfono:
                            <input class="uk-input" type="text" name="phone" placeholder="Teléfono">
                        </div>
                        <div class="uk-width-1-2">
                            Contraseña (*):
                            <input class="uk-input" type="password" name="password" id="password" placeholder="Ingrese la contraseña..." onkeyup="verificarClaves();" required>
                        </div>
                        <div class="uk-width-1-2">
                            Confirmar Contraseña (*):
                            <input class="uk-input" type="password" id="password2" placeholder="Confirme la contraseña..." onkeyup="verificarClaves();" required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Alumno</button>
                </div>     
            </form>                        
        </div>                         
    </div>

    <!-- Modal para Enviar Correo Electrónico -->                     
    <div id="email-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Enviar Correo Electrónico </h4> 
            </div>                    
            <form action="{{ route('admins.users.send-mail-all-students') }}" method="POST" enctype="multipart/form-data">  
                @csrf
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
                <h4> Seleccionar Alumnos para Enviar Correo Electrónico </h4> 
            </div>                    
            <form action="{{ route('admins.users.send-mail-all-students') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <input type="hidden" name="selection" value="1">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título del correo" required>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Descripción (*):
                            <textarea class="ckeditor" name="description" id="description"></textarea>
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
                                    <th class="uk-text-center">Alumno</th> 
                                    <th class="uk-text-center">Correo</th> 
                                    <th class="uk-text-center">Estado</th>
                                </tr>                             
                            </thead>                         
                            <tbody> 
                                @foreach ($estudiantes  as $estudiante2)
                                    <tr>      
                                        <td><label><input class="uk-checkbox" type="checkbox" name="students_selected[]" value="{{ $estudiante2->id }}"></label></td>
                                        <td class="uk-text-center">{{ $estudiante2->names }} {{ $estudiante2->last_names }}</td>  
                                        <td class="uk-text-center">{{ $estudiante2->email }}</td> 
                                        <td class="uk-text-center">
                                            @if ($estudiante2->status == 0)
                                                <label class="uk-label uk-label-danger">Eliminado</label>
                                            @elseif ($estudiante2->status == 1)
                                                    <label class="uk-label uk-label-success">Activo</label>
                                            @else
                                                <label class="uk-label uk-label-warning">Pendiente</label>
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