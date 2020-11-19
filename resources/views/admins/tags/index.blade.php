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

        function verificarTag($accion){
            if ($accion == 1){
                var etiqueta = document.getElementById("tag").value;
            }else{
                var etiqueta = document.getElementById("tag2").value;
            }
           
            var route = "https://transformatepro.com/ajax/verificar-etiqueta/"+etiqueta;
            //var route = "http://localhost:8000/ajax/verificar-etiqueta/"+etiqueta;
            
            if (etiqueta != ""){
                $.ajax({
                    url:route,
                    type:'GET',
                    success:function(ans){
                        if ($accion == 1){
                            if (ans == 0){
                                $("#div_error").addClass('uk-hidden');
                                document.getElementById("btn-crear").disabled = false;
                            }else{
                                $("#div_error").removeClass('uk-hidden');
                                document.getElementById("btn-crear").disabled = true;
                            }
                        }else{
                            if (ans == 0){
                                $("#div_error2").addClass('uk-hidden');
                                document.getElementById("btn-editar").disabled = false;
                            }else{
                                $("#div_error2").removeClass('uk-hidden');
                                document.getElementById("btn-editar").disabled = true;
                            }
                        }
                    }
                });
            }
        }

        function cargarModalEditar($etiqueta){
            var route = "https://transformatepro.com/admins/tags/edit/"+$etiqueta;
            //var route = "http://localhost:8000/admins/tags/edit/"+$etiqueta;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    document.getElementById("tag_id").value = $etiqueta;
                    document.getElementById("tag2").value = ans.tag;
                    UIkit.modal("#edit-modal").show();
                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-tags icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Listado de Etiquetas </h4>  
            @if (Auth::user()->profile->tags == 2) 
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Etiqueta; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-plus-circle"></i> Nueva Etiqueta
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
                            <th class="uk-text-center">Etiqueta</th>
                            @if (Auth::user()->profile->tags == 2) 
                                <th class="uk-text-center">Acción</th>
                            @endif
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($etiquetas as $etiqueta)
                            <tr>      
                                <td class="uk-text-center">{{ $etiqueta->id }}</td>                          
                                <td class="uk-text-center">{{ $etiqueta->tag }}</td> 
                                @if (Auth::user()->profile->tags == 2) 
                                    <td class="uk-text-center">
                                        <a class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar" href="#" onclick="cargarModalEditar({{ $etiqueta->id }});">
                                        
                                        @if ( ($etiqueta->courses_count == 0) && ($etiqueta->certifications_count == 0) && ($etiqueta->podcasts_count == 0) ) 
                                            <a class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: trash;" uk-tooltip="Eliminar" href="{{ route('admins.tags.delete', $etiqueta->id) }}">
                                        @else 
                                            <a class="uk-icon-button uk-button-default btn-icon" uk-icon="icon: trash;" uk-tooltip="Etiqueta en Uso"></a>
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
                <h4> Crear Etiqueta</h4> 
            </div>                    
            <form action="{{ route('admins.tags.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Nombre (*):
                            <input class="uk-input" type="text" name="tag" id="tag" onkeyup="verificarTag(1);" required>
                        </div><br>
                        <div class="uk-width-1-1 uk-hidden" id="div_error">
                            <div class="uk-alert-danger" uk-alert>
                               <i class="fa fa-times"></i> La etiqueta ya existe.
                            </div>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Etiqueta</button>
                </div>     
            </form>                        
        </div>                         
    </div>

    <!-- Modal para Editar Etiqueta-->                     
    <div id="edit-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>     
            <div class="uk-modal-header"> 
                <h4> Modificar Etiqueta</h4> 
            </div>                    
            <form action="{{ route('admins.tags.update') }}" method="POST">  
                @csrf
                <input type="hidden" name="tag_id" id="tag_id">
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Nombre (*):
                            <input class="uk-input" type="text" name="tag" id="tag2" onkeyup="verificarTag(2);" required>
                        </div><br>
                        <div class="uk-width-1-1 uk-hidden" id="div_error2">
                            <div class="uk-alert-danger" uk-alert>
                               <i class="fa fa-times"></i> La etiqueta ya existe.
                            </div>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-editar">Guardar Cambios</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection