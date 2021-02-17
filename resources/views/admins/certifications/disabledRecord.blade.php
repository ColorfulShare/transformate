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
            <i class="fas fa-landmark icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Certificaciones Deshabilitadas</h4>                          
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif               
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatables"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Cover</th> 
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Instructor</th> 
                            <th class="uk-text-center">Categoría</th>
                            <th class="uk-text-center">Acción</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($certificaciones as $certificacion)
                            <tr>                                 
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $certificacion->title }}</td> 
                                <td class="uk-text-center">{{ $certificacion->user->names }} {{ $certificacion->user->last_names }}</td> 
                                <td class="uk-text-center"><i class="{{ $certificacion->category->icon }}"></i> {{ $certificacion->category->title }} ({{ $certificacion->subcategory->title }})</td> 
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ route('admins.certifications.resume', [$certificacion->slug, $certificacion->id]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a> 
                                    <a href="{{ route('admins.certifications.show', [$certificacion->slug, $certificacion->id]) }}" class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: search;" uk-tooltip="Editar"></a>
                                    @if (Auth::user()->profile->certifications == 2)
                                        <form action="{{ route('admins.certifications.change-status') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="certification_id" value="{{ $certificacion->id }}">
                                            <input type="hidden" name="action" value="Restaurar">
                                            <button class="uk-icon-button uk-button-success btn-icon" uk-icon="icon: refresh;" uk-tooltip="Restaurar"></button>
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