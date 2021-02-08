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
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif  

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Courses Destacados </h4> 
        </div>       

        <div class="uk-background-default uk-margin-top uk-padding"> 
            <!-- table -->                 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr> 
                            <th class="uk-text-center">Cover</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-table-shrink">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                    	@foreach ($cursosDisponibles as $curso)
                            <tr>                          
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/courses/'.$curso->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $curso->title }}</td> 
                                <td class="uk-text-center">{{ $curso->user->names }} {{ $curso->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $curso->category->title }} ({{ $curso->subcategory->title }})</td>   
                                <td class="uk-text-center"> 
                                	@if ($curso->featured == 0)
                                        <form action="{{ route('admins.courses.update-featured') }}" method="POST">
    	                                    @csrf
    	                                    <input type="hidden" name="course_id" value="{{ $curso->id }}">
    	                                    <input type="hidden" name="featured" value="1">
    	                                    <button class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: star;" uk-tooltip="Destacar"></button>
    	                                </form> 
    	                            @else
    	                            	<form action="{{ route('admins.courses.update-featured') }}" method="POST">
    	                                    @csrf
    	                                    <input type="hidden" name="course_id" value="{{ $curso->id }}">
    	                                    <input type="hidden" name="featured" value="0">
    	                                    <button class="uk-icon-button uk-button-danger btn-icon" uk-icon="icon: reply;" uk-tooltip="Quitar de Destacados"></button>
    	                                </form> 
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