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

        function checkOption(){
            if (document.getElementById("type").value == 'free'){
                document.getElementById("price").disabled = true;
            }else{
                document.getElementById("price").disabled = false;
            }
        }

        var nextinput = 0;
        function addBenefit(){
            nextinput++;
            campo = '<input class="uk-input" type="text" name="benefits[]">';
            $("#benefits-div").append(campo);
        }
    </script>

@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-users icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Eventos Disponibles </h4> 
            @if (Auth::user()->profile->events == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Alumno; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-plus-circle"></i> Nuevo Evento
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
                                <td class="uk-text-center">@if ($evento->type == 'Free') Gratuito @else Pago <b>(COP$ {{ number_format($evento->price, 0, ',', '.') }})</b> @endif</td> 
                                <td class="uk-text-center">{{ $evento->subscriptions_count }}</td>  
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.events.subscriptions', [$evento->slug, $evento->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Subscripciones"></a>
                                    @if (Auth::user()->profile->events == 2)
                                        <a href="{{ route('admins.events.show', [$evento->slug, $evento->id]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>
                                        <a href="{{ route('admins.events.disabled', [$evento->id, 0]) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: ban;" uk-tooltip="Deshabilitar"></a>
                                    @endif                       
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                         
        </div>                 
    </div>

    <!-- Modal para Crear Evento -->                     
    <div id="create-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Crear Nuevo Evento </h4> 
            </div>                    
            <form action="{{ route('admins.events.store') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título del evento" required>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Mentor (*):
                            <select class="uk-select" name="user_id" id="user_id" required>
                                <option value="free">Seleccione un mentor...</option>
                                @foreach ($mentores as $mentor)
                                    <option value="{{ $mentor->id }}">@if (!is_null($mentor->names)) {{ $mentor->names }} {{ $mentor->last_names }} @else {{ $mentor->email }} @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Fecha (*):
                            <input class="uk-input" type="date" name="date" required>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Horario (*):
                            <input class="uk-input" type="text" name="time" required>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Breve Leyenda (*):
                            <input class="uk-input" type="text" name="legend" placeholder="Leyenda del evento" maxlength="200" required>
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
                        </div><br>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Tipo de Evento (*):
                            <select class="uk-select" name="type" id="type" onchange="checkOption();">
                                <option value="free">Gratuito</option>
                                <option value="pay">Pago</option>
                            </select>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Costo (*):
                            <input class="uk-input" type="number" name="price" id="price" value="0" disabled>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            PDF Informativo:
                            <input class="uk-input" type="file" name="informative_pdf">
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Portada Versión Escritorio (*):
                            <input class="uk-input" type="file" name="image" required>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Portada Versión Móvil (*):
                            <input class="uk-input" type="file" name="image_movil" required>
                        </div>
                        <div class="uk-width-1-3 uk-margin-small-bottom">
                            Video Trailer:
                            <input class="uk-input" type="file" name="video">
                        </div>
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Imagenes para Slider (Versión Escritorio):
                            <input class="uk-input" type="file" name="slider_images[]" multiple>
                        </div> 
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Imagenes para Slider (Versión Móvil):
                            <input class="uk-input" type="file" name="slider_images_movil[]" multiple>
                        </div>   
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Imagen de los Beneficios:
                            <input class="uk-input" type="file" name="benefits_img">
                        </div>   
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Testimonio:
                            <textarea class="ckeditor2" name="testimony" rows="10"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '.ckeditor2' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div><br>  
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Autor del Testimonio:
                            <input class="uk-input" type="text" name="testimony_autor">
                        </div> 
                        <div class="uk-width-1-2 uk-margin-small-bottom">
                            Imagen del Autor del Testimonio:
                            <input class="uk-input" type="file" name="testimony_img">
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Evento</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection