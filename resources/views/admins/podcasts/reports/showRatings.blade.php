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
            <i class="fas fa-star icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Valoraciones</h4>                          
        </div>                 
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Avatar</th> 
                            <th class="uk-text-center">Alumno</th>
                            <th class="uk-text-center">Comentario</th> 
                            <th class="uk-text-center">Puntuación</th> 
                            <th class="uk-text-center">Fecha</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($valoraciones as $valoracion)
                            <tr>                                                                 
                                 <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/users/'.$valoracion->user->avatar) }}" width="50">
                                </td>
                                <td class="uk-text-center">{{ $valoracion->user->names }} {{ $valoracion->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $valoracion->comment }}</td> 
                                <td class="uk-text-center">
                                    @if ($valoracion->points >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($valoracion->points >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($valoracion->points >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($valoracion->points >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                    @if ($valoracion->points >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
                                </td>
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$valoracion->created_at -5 Hours")) }} </td>      
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>   
        </div>             
    </div>
@endsection