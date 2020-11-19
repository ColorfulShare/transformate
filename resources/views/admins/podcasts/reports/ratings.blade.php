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
        <nav class="uk-navbar-container uk-margin-medium-bottom" uk-navbar style="padding-top: 20px; padding-bottom: 40px;">
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li class="uk-active"><a href="javascript:;">Reportes</a></li></ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.podcasts.reports.sales') }}">Ventas</a></li>
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.podcasts.reports.students') }}">Estudiantes</a></li>
                    <li class="uk-active"><a href="{{ route('admins.podcasts.reports.ratings') }}">Valoraciones</a></li>
                </ul>
            </div>
        </nav> 

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-star icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Reporte de Valoraciones</h4>                          
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
                            <th class="uk-text-center">Cover</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Puntuación</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($podcasts as $podcast)
    	                    @php
    	                    	$promedio = explode('.', $podcast->promedio);
    	                    @endphp
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/podcasts/'.$podcast->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $podcast->title }}</td> 
                                <td class="uk-text-center">{{ $podcast->user->names }} {{ $podcast->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $podcast->category->title }} <br>({{ $podcast->subcategory->title }})</td> 
                                <td class="uk-text-center">
                                	@if ($promedio[0] >= 1) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
    					            @if ($promedio[0] >= 2) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
    					            @if ($promedio[0] >= 3) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
    					            @if ($promedio[0] >= 4) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
    					            @if ($promedio[0] >= 5) <i class="fas fa-star icon-small icon-rate"></i> @else <i class="far fa-star icon-small icon-rate"></i> @endif
    					            ({{ number_format($podcast->promedio, 2) }})
                                </td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.podcasts.reports.show-ratings', $podcast->id) }}" class="uk-icon-button uk-button-success" uk-icon="icon: file-text;" uk-tooltip="Ver Reporte"></a>              
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>   
        </div>          
    </div>
@endsection