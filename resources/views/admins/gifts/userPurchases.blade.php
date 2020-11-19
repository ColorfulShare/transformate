@extends('layouts.admin')

@push('scripts')
    <script>
         $(document).ready( function () {
            $('#datatable').DataTable( {
                dom: '<Bf<t>ip>',
                responsive: true,
                order: [[ 0, "desc" ]],
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
            <i class="fas fa-gift icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Gifts entre Usuarios </h4>                    
        </div>  

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">Fecha de Compra</th> 
                            <th class="uk-text-center">Contenido</th> 
                            <th class="uk-text-center">Código T-Gift</th>
                            <th class="uk-text-center">Comprador</th>
                            <th class="uk-text-center">Beneficiario</th>
                            <th class="uk-text-center">Estado</th>
                            <th class="uk-text-center">Fecha de Aplicación</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($regalos as $regalo)
                            <tr>    
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$regalo->created_at -5 Hours")) }}</td>
                                <td class="uk-text-center">
                                    @if (!is_null($regalo->course_id)) 
                                        {{ $regalo->course->title }} <br>(T-Course) 
                                    @else 
                                        {{ $regalo->podcast->title }} <br>(T-Book)
                                    @endif
                                </td>
                                <td class="uk-text-center">{{ $regalo->code }}</td>
                                <td class="uk-text-center">{{ $regalo->buyer->names }} {{ $regalo->buyer->last_names }}</td>
                                <td class="uk-text-center">@if (!is_null($regalo->user_id)) {{ $regalo->user->names }} {{ $regalo->user->last_names }} @else - @endif</td>  
                                <td class="uk-text-center">@if (!is_null($regalo->user_id)) Aplicado @else Sin Aplicar @endif</td>  
                                <td class="uk-text-center">@if (!is_null($regalo->user_id)) {{ date('d-m-Y', strtotime("$regalo->applied_at -5 Hours")) }} @else - @endif</td>  
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                                            
        </div>  
    </div>
@endsection