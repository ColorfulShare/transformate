@extends('layouts.admin')

@push('scripts')
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
        });

        function loadReview($id){
            document.getElementById("review-content").innerHTML = document.getElementById("course-review-"+$id).value;
            var modal = UIkit.modal("#review-modal");
            modal.show(); 
        }
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-users icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Instructores Pendientes</h4>                  
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
                            <th class="uk-text-center">Fecha de Registro</th> 
                            <th class="uk-text-center">Nombres</th> 
                            <th class="uk-text-center">Correo</th> 
                            <th class="uk-text-center">Hoja de Vida</th> 
                            <th class="uk-text-center">Video de Presentación</th> 
                            <th class="uk-text-center">Reseña del Curso</th>
                            @if (Auth::user()->profile->users == 2) 
                                <th class="uk-text-center">Acciones</th>
                            @endif 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($instructores as $instructor)
                            <tr>    
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$instructor->created_at -5 Hours")) }}</td>                                
                                <td class="uk-text-center">{{ $instructor->names }} {{ $instructor->last_names }}</td> 
                                <td class="uk-text-center">{{ $instructor->email }}</td> 
                                <td class="uk-text-center"><a href="{{ asset('uploads/instructors/resumes/'.$instructor->curriculum) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" target="_blank" uk-tooltip="Ver Hoja de Vida"></a></td> 
                                <td class="uk-text-center"><a href="{{ asset('uploads/instructors/presentations/'.$instructor->video_presentation) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" target="_blank" uk-tooltip="Ver Video de Presentación"></a></td> 
                                <td class="uk-text-center">
                                    <input type="hidden" id="course-review-{{$instructor->id}}" value="{{$instructor->course_review}}">
                                    <a href="javascript:;" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Resumen del Curso" onclick="loadReview({{$instructor->id}});"></a></td> 
                                @if (Auth::user()->profile->users == 2)
                                    <td class="uk-flex-inline uk-text-center">
                                        <a href="{{ route('admins.users.instructors.approve', [$instructor->id, 1]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: check;" uk-tooltip="Aprobar"></a> 
                                        <a href="{{ route('admins.users.instructors.approve', [$instructor->id, 0]) }}" class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: ban;" uk-tooltip="Denegar"></a>
                                    </td> 
                                @endif                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                 
        </div>                 
    </div>

    <!-- Modal para Ver Reseña del Curso -->                     
    <div id="review-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>
            <div class="uk-modal-header"> 
                <h4>Reseña del Curso</h4> 
            </div>                    
            <div class="uk-modal-body" id="review-content">
                                          
            </div>                             
            <div class="uk-modal-footer uk-text-right"> 
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cerrar</button>   
            </div>                      
        </div>                         
    </div>
@endsection