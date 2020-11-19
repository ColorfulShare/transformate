@extends('layouts.admin')

@push('scripts')
    <script>
         $(document).ready( function () {
            $('#datatable').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 0, "asc" ]],
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
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Lecciones por Curso</h4>                          
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
                            <th class="uk-text-center"># Lección</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Módulo</th> 
                            <th class="uk-text-center">Orden</th>
                            <th class="uk-text-center">Duración</th>
                            <th class="uk-text-center">Última Modificación</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($lecciones as $leccion)
                            <tr>                                 
                                <td class="uk-text-center">{{ $leccion->id }}</td>
                                <td class="uk-text-center">{{ $leccion->title }}</td> 
                                <td class="uk-text-center">{{ $leccion->module->title }} (#{{ $leccion->module->priority_order }})</td> 
                                <td class="uk-text-center">#{{ $leccion->priority_order }}</td> 
                                <td class="uk-text-center">
                                    @if ($leccion->duration > 0)
                                        {{ $leccion->minutes }}m
                                    @else
                                        Sin Capturar
                                    @endif
                                </td>
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$leccion->updated_at -5 Hours")) }}</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.courses.lessons.show-video', $leccion->id) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: video-camera;" uk-tooltip="Ver Video"></a>
                                    @if (Auth::user()->profile->courses == 2)
                                       <a href="{{ route('admins.courses.lessons.delete', $leccion->id) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar Lección"></a>
                                    @endif                           
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                 
        </div>                
    </div>
@endsection