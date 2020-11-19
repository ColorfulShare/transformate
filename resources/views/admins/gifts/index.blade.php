@extends('layouts.admin')

@push('scripts')
    <script>
         $(document).ready( function () {
            $('#datatable').DataTable( {
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

        function loadContent(){
            if (document.getElementById("type").value == 'Curso'){
                $('#podcast_id').removeAttr("required");
                $('#podcast_div').hide();
                $('#event_id').removeAttr("required");
                $('#event_div').hide();
                $('#course_id').prop("required", true);
                $('#course_div').show();
            }else if (document.getElementById("type").value == 'Podcast'){
                $('#course_id').removeAttr("required");
                $('#course_div').hide();
                $('#event_id').removeAttr("required");
                $('#event_div').hide();
                $('#podcast_id').prop("required", true);
                $('#podcast_div').show();
            }else if (document.getElementById("type").value == 'Evento'){
                $('#course_id').removeAttr("required");
                $('#course_div').hide();
                $('#podcast_id').removeAttr("required");
                $('#podcast_div').hide();
                $('#event_id').prop("required", true);
                $('#event_div').show();
            }
         }
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-gift icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Gifts </h4>   
            @if (Auth::user()->profile->gifts == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Asignar Regalo; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-user-plus"></i> Nuevo Regalo
                </button>    
            @endif                     
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif       
        
        @if (Session::has('msj-erroneo'))
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-erroneo') }}</strong>
            </div>
        @endif

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Tipo de Contenido</th>
                            <th class="uk-text-center">Contenido</th> 
                            <th class="uk-text-center">Usuario</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($regalos as $regalo)
                            <tr>    
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$regalo->created_at -5 Hours")) }}</td>
                                <td class="uk-text-center">
                                    @if (!is_null($regalo->course_id)) 
                                        T-Course 
                                    @elseif (!is_null($regalo->podcast_id)) 
                                        T-Book 
                                    @elseif (!is_null($regalo->event_id)) 
                                        T-Event
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    @if (!is_null($regalo->course_id)) 
                                        {{ $regalo->course->title }}
                                    @elseif (!is_null($regalo->podcast_id)) 
                                        {{ $regalo->podcast->title }}
                                    @elseif (!is_null($regalo->event_id)) 
                                        {{ $regalo->event->title }}
                                    @endif
                                </td>
                                <td class="uk-text-center">{{ $regalo->user->names }} {{ $regalo->user->last_names }}</td> 
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-danger" href="{{ route('admins.gifts.delete', $regalo->id) }}" uk-icon="icon: trash;" uk-tooltip="Quitar Regalo"></a> 
                                </td>
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                            
        </div>  
    </div>

    <!-- Modal para Crear Regalo -->                     
    <div id="create-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4>Asignar Contenido Regalo</h4> 
            </div>                    
            <form action="{{ route('admins.gifts.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            Tipo de Contenido (*):
                            <select class="uk-select" name="type" id="type" required onchange="loadContent();">
                                <option value="" selected disabled>Seleccione una opción...</option>
                                <option value="Curso">T-Course</option>
                                <option value="Podcast">T-Book</option>
                                <option value="Evento">T-Event</option>
                            </select>
                        </div>
                        <div class="uk-width-1-1" id="course_div" style="display: none;">
                            T-Course (*):
                            <select class="uk-select" name="course_id" id="course_id">
                                <option value="" selected disabled>Seleccione un T-Course...</option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->id }}">{{ $curso->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="uk-width-1-1" id="podcast_div" style="display: none;">
                            T-Book (*):
                            <select class="uk-select" name="podcast_id" id="podcast_id">
                                <option value="" selected disabled>Seleccione un T-Book...</option>
                                @foreach ($podcasts as $podcast)
                                    <option value="{{ $podcast->id }}">{{ $podcast->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="uk-width-1-1" id="event_div" style="display: none;">
                            T-Book (*):
                            <select class="uk-select" name="event_id" id="event_id">
                                <option value="" selected disabled>Seleccione un T-Event...</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}">{{ $evento->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="uk-width-1-1">
                            Correo del Usuario (*):
                            <input class="uk-input" type="email" name="email" required>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Asignar Regalo</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection