@extends('layouts.admin')

@push('styles')
    <style>
        input[type="radio"] {
          display: none;
        }

        label {
          color: grey;
        }

        .clasificacion {
          direction: rtl;
          unicode-bidi: bidi-override;
        }

        label:hover,
        label:hover ~ label {
          color: orange;
        }

        input[type="radio"]:checked ~ label {
          color: orange;
        }
    </style>
@endpush

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

            $(".rating").on('click', function(){
                $("#course_id").val($(this).attr('data-id'));
                UIkit.modal("#new-rating").show();
            });

            $("#user_id").on('change', function(){
                if ($("#user_id").val() == 0){
                    $("#user_name_div").css('display', 'block');
                    $("#user_name").prop('required', true);
                }else{
                    $("#user_name_div").css('display', 'none');
                    $("#user_name").prop('required', false);
                }
            });
        });
    </script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <nav class="uk-navbar-container uk-margin-medium-bottom" uk-navbar style="padding-top: 20px; padding-bottom: 40px;">
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li class="uk-active"><a href="javascript:;">Reportes</a></li></ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.courses.reports.sales') }}">Ventas</a></li>
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.courses.reports.students') }}">Estudiantes</a></li>
                    <li class="uk-active"><a href="{{ route('admins.courses.reports.ratings') }}">Valoraciones</a></li>
                </ul>
            </div>
        </nav>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif 

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-star icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Reporte de Valoraciones</h4>                          
        </div>  

        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Cover</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Puntuación</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($cursos as $curso)
                            @php
                                $promedio = explode('.', $curso->promedio);
                            @endphp
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/courses/'.$curso->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $curso->title }}</td> 
                                <td class="uk-text-center">{{ $curso->user->names }} {{ $curso->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $curso->category->title }} <br>({{ $curso->subcategory->title }})</td> 
                                <td class="uk-text-center">
                                    @if ($promedio[0] >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($promedio[0] >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($promedio[0] >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($promedio[0] >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($promedio[0] >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    ({{ number_format($curso->promedio, 2) }})
                                </td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="javascript:;" class="uk-icon-button uk-button-primary rating btn-icon" data-id="{{ $curso->id }}" uk-icon="icon: star;" uk-tooltip="Agregar Valoración"></a>
                                    <a href="{{ route('admins.courses.reports.show-ratings', $curso->id) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: file-text;" uk-tooltip="Ver Reporte"></a>              
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                        
        </div>     
    </div>

    <!-- Modal para agregar Valoración a un Curso -->                     
    <div id="new-rating" uk-modal> 
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Valorar Contenido</h4>
            </div>
            <form action="{{ route('admins.courses.reports.add-rating') }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" id="course_id">
                <div class="uk-modal-body">
                    <div class="uk-margin">
                        <div class="uk-width-1-1">
                            <select class="uk-select" name="user_id" id="user_id">
                                <option value="0">Estudiante no registrado</option>
                                @foreach ($estudiantes as $estudiante) 
                                    <option value="{{ $estudiante->id }}">{{ $estudiante->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="uk-margin" id="user_name_div">
                        <div class="uk-width-1-1">
                            <input class="uk-input" type="text" name="name" id="user_name" placeholder="Nombre del Valorador" required>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-width-1-1">
                            <input class="uk-input" type="text" name="title" placeholder="Título de la valoración" required>
                        </div>
                    </div>
                    <div class="uk-margin">
                        <div class="uk-width-1-1">
                            <textarea class="uk-textarea" rows="5" name="comment" placeholder="¿Qué te pareció el curso?" required></textarea>
                        </div>
                    </div>
                    <p class="clasificacion uk-text-center">
                        <input id="radio1c" type="radio" name="points" value="5"><label for="radio1c"><i class="fa fa-star"></i></label>
                        <input id="radio2c" type="radio" name="points" value="4"><label for="radio2c"><i class="fa fa-star"></i></label>
                        <input id="radio3c" type="radio" name="points" value="3"><label for="radio3c"><i class="fa fa-star"></i></label>
                        <input id="radio4c" type="radio" name="points" value="2"><label for="radio4c"><i class="fa fa-star"></i></label>
                        <input id="radio5c" type="radio" name="points" value="1"><label for="radio5c"><i class="fa fa-star"></i></label>
                      </p>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <input type="submit" class="uk-button uk-button-primary" value="Publicar Valoración"/>
                </div>
            </form>
        </div>                         
    </div>
@endsection