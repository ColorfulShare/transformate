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
            <h4 class="uk-margin-remove"> Newsletters </h4> 
            @if (Auth::user()->profile->newsletters == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#newsletter-modal" uk-tooltip="title: Crear Cuenta; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="far fa-envelope"></i> Enviar Correo
                </button>    
            @endif                     
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
                            <th class="uk-text-center">Título</th> 
                            <th class="uk-text-center">Texto</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($boletines as $boletin)
                            <tr>                                   
                                <td class="uk-text-center">{{ date('Y-m-d', strtotime($boletin->created_at)) }}</td> 
                                <td class="uk-text-center">{{ $boletin->title }}</td> 
                                <td class="uk-text-center">{!! $boletin->body !!}</td>           
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                   
        </div>                 
    </div>

    <!-- Modal para Crear Boletin -->                     
    <div class="uk-modal-container" id="newsletter-modal" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>                   
            <div class="uk-modal-header"> 
                <h4> Enviar Boletín de Correo </h4> 
            </div>                    
            <form action="{{ route('admins.newsletters.store') }}" method="POST" enctype="multipart/form-data">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Título (*):
                            <input class="uk-input" type="text" name="title" placeholder="Título del correo" required>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Descripción (*):
                            <textarea class="ckeditor" name="body" rows="10"></textarea>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '.ckeditor' ) )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>
                        </div>
                        <div class="uk-width-1-1 uk-margin-small-bottom">
                            Archivo Adjunto:
                            <input class="uk-input" type="file" name="adjunto">
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Enviar Correo</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection