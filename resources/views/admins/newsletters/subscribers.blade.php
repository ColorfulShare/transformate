@extends('layouts.admin')

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
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
            <i class="fas fa-book-reader icon-large uk-margin-right"></i>
            <h4 class="uk-margin-remove"> Suscriptores</h4>                    
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
                            <th class="uk-text-center">Fecha</th> 
                            <th class="uk-text-center">Suscriptor</th> 
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($suscriptores as $suscriptor)
                            <tr>                                   
                                <td class="uk-text-center">{{ date('Y-m-d', strtotime($suscriptor->created_at)) }}</td> 
                                <td class="uk-text-center">{{ $suscriptor->email }}</td>           
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                   
        </div>                 
    </div>
@endsection