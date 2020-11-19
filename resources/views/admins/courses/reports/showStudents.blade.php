@extends('layouts.admin')

@push('scripts')
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
            <i class="fas fa-users icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Alumnos por Curso</h4>       
            @if (Auth::user()->profile->courses == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#email-modal" uk-tooltip="title: Enviar Correo Electrónico; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fa fa-envelope"></i> Enviar Correo Electrónico
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
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Avatar</th> 
                            <th class="uk-text-center">Alumno</th>
                            <th class="uk-text-center">Progreso</th> 
                            <th class="uk-text-center">Fecha de Inicio</th> 
                            <th class="uk-text-center">Fecha de Fin</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($datosCurso->students as $estudiante)
                            <tr>                                                                 
                                 <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/users/'.$estudiante->avatar) }}" width="50">
                                </td>
                                <td class="uk-text-center">{{ $estudiante->names }} {{ $estudiante->last_names }}</td> 
                                <td class="uk-text-center">
                                    @if ($estudiante->pivot->progress < 100)
                                        <progress id="js-progressbar" class="uk-progress uk-margin-small-bottom uk-margin-small-top" value="{{ $estudiante->pivot->progress }}" max="100" style="height: 8px;"></progress> ({{ $estudiante->pivot->progress }}%)
                                    @else
                                        <progress id="js-progressbar" class="uk-progress progress-green uk-margin-small-bottom uk-margin-small-top" value="{{ $estudiante->pivot->progress }}" max="100" style="height: 8px;"></progress> ({{ $estudiante->pivot->progress }}%)
                                    @endif
                                </td> 
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime($estudiante->pivot->start_date)) }} </td> 
                                 <td class="uk-text-center">
                                    @if ($estudiante->pivot->progress == 100) 
                                        {{ date('d-m-Y', strtotime($estudiante->pivot->ending_date)) }} 
                                    @else
                                        -
                                    @endif
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
            <form action="{{ route('admins.users.send-mail-students-by-course') }}" method="POST">  
                @csrf
                <input type="hidden" name="course_id" value="{{ $datosCurso->id }}">
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