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
                    <li class="uk-active" style="border-right: 1px solid #000;"><a href="{{ route('admins.courses.reports.sales') }}">Ventas</a></li>
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.courses.reports.students') }}">Estudiantes</a></li>
                    <li><a href="{{ route('admins.courses.reports.ratings') }}">Valoraciones</a></li>
                </ul>
            </div>
        </nav>   

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-shopping-cart icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Reporte de Ventas</h4>                          
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
                            <th class="uk-text-center">Ventas</th> 
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($cursos as $curso)
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/courses/'.$curso->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $curso->title }}</td> 
                                <td class="uk-text-center">{{ $curso->user->names }} {{ $curso->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $curso->category->title }} ({{ $curso->subcategory->title }})</td> 
                                <td class="uk-text-center">{{ $curso->purchases_count }}</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.courses.reports.show-sales', $curso->id) }}" class="uk-icon-button uk-button-success" uk-icon="icon: file-text;" uk-tooltip="Ver Reporte"></a>              
                                </td>                                 
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                         
        </div>        
    </div>
@endsection