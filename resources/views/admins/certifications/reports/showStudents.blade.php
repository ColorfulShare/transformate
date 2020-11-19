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
            <i class="fas fa-user icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Alumnos</h4>                          
        </div>                 
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto"> 
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
                        @foreach ($datosCertificacion->students as $estudiante)
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
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$estudiante->pivot->start_date -5 Hours")) }} </td> 
                                 <td class="uk-text-center">
                                    @if ($estudiante->pivot->progress == 100) 
                                        {{ date('d-m-Y', strtotime("$estudiante->pivot->ending_date -5 Hours")) }} 
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
@endsection