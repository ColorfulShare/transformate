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

    <script>
         $(function(){
            $('.form-cover').on('submit',function(e){
                e.preventDefault();
                $.ajax({
                    url:"{{ route('admin.certifications.load-cover-image') }}",
                    type:'POST',
                    data: new FormData(this),
                    cache: false,
                    processData: false,
                    contentType: false,
                    success:function(response){
                        let text = JSON.parse(response);
                        if(text.error){
                            $('.error-image').html(text.error).addClass('uk-text-danger').removeClass('uk-hidden');
                        }else{
                            $('.error-image').html(text.success).addClass('uk-text-success').removeClass('uk-hidden');                                                    
                            location.reload();
                        }
                    }
                });
            });

            $('body').on('click','.select-cover',function(){
                let 
                id = $(this).data('id'),
                modal = UIkit.modal("#modalCover");
                $('.certification_id').val(id);
                modal.show(); 
            });

            $('.img-cover').on('change',function(){
                $('.save-image').attr('disabled',false);
            });

            $('.image-course').on('change',function(){
                $('.save-image-course').attr('disabled',false);
            })
        });     
    </script>
@endpush

@section('content')
	<div class="admin-content-inner"> 
        <nav class="uk-navbar-container uk-margin-medium-bottom" uk-navbar style="padding-top: 20px; padding-bottom: 40px;">
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li class="uk-active"><a href="javascript:;">Configuraciones</a></li></ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li class="uk-active" style="border-right: 1px solid #000;"><a href="{{ route('admins.certifications.home-cover') }}">Portada del Home</a></li>
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.certifications.home-sliders') }}">Carruseles del Home</a></li>
                    <li><a href="{{ route('admins.certifications.featured') }}">Gestionar Destacados</a></li>
                </ul>
            </div>
        </nav>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif 

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-landmark icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> T-Mentorings Disponibles </h4> 
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
                            <th class="uk-table-shrink">Seleccionar portada</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @if (!is_null($portadaActual))
                            <tr style="background-color: #D6F9B6;" uk-tooltip="Portada Actual">                          
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/certifications/'.$portadaActual->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $portadaActual->title }}</td> 
                                <td class="uk-text-center">{{ $portadaActual->user->names }} {{ $portadaActual->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $portadaActual->category->title }} ({{ $portadaActual->subcategory->title }})</td>   
                                <td class="uk-text-center">
                                    <input class="uk-radio" type="radio" disabled checked>                                                               
                                </td>                                
                            </tr>
                        @endif
                    	@foreach ($certificacionesDisponibles as $certificacion)
                            <tr>                          
                                <td class="uk-text-center">
                                    <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/certifications/'.$certificacion->cover) }}" width="50">
                                </td>                                 
                                <td class="uk-text-center">{{ $certificacion->title }}</td> 
                                <td class="uk-text-center">{{ $certificacion->user->names }} {{ $certificacion->user->last_names }}</td> 
                                <td class="uk-text-center">{{ $certificacion->category->title }} ({{ $certificacion->subcategory->title }})</td>  
                                <td class="uk-text-center">
                                    <label><input class="uk-radio select-cover" type="radio" name="select-cover" data-id="{{ $certificacion->id }}"> </label>                                                     
                                </td>                                  
                            </tr>
                        @endforeach                      
                    </tbody>                         
                </table>                     
            </div>    
        </div>       
        
        <div id="modalCover" uk-modal> 
            <div class="uk-modal-dialog"> 
                <button class="uk-modal-close-default" type="button" uk-close></button>   
                <div class="uk-modal-header"> 
                    <h4> Seleccione imagen de portada</h4> 
                </div> 
                <form action="#" method="POST" enctype="multipart/form-data" class="form-cover"> 
                    @csrf     
                    <input type="hidden" name="certification_id" class="certification_id">      
                    <div class="uk-modal-body uk-margin-left"> 
                        <div class="uk-margin uk-text-center" uk-margin>
                            <div uk-form-custom="target: true">
                                <input type="file" name="cover" class="img-cover">
                                <input class="uk-input uk-form-width-medium" type="text" placeholder="Seleccione archivo" disabled>
                                <p class="error-image uk-hidden"></p>
                            </div>
                        </div>	                        
                        <div class="uk-modal-footer uk-text-right"> 
                            <button class="uk-button uk-button-primary save-image" type="submit" disabled>Guardar Cambios</button> 
                            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button> 
                        </div>    
                    </div>                         
                </form>                         
            </div>            
        </div>         
    </div>
@endsection