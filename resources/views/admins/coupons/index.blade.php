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

		$(function() {
			$("#selectall").on("click", function() {  
			  	$(".case").prop("checked", this.checked);  
			});  

			// if all checkbox are selected, check the selectall checkbox and viceversa  
			$(".case").on("click", function() { 
			  	if ($(".case").length == $(".case:checked").length) {  
			    	$("#selectall").prop("checked", true);  
			  	} else {  
			    	$("#selectall").prop("checked", false);  
			  	}  
			});
		});
	</script>
@endpush

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-tag icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Listado de Cupones </h4>   
            @if (Auth::user()->profile->coupons == 2)
                <button class="uk-button uk-button-success uk-margin-medium-left admin-btn" href="#create-modal" uk-tooltip="title: Crear Cupón; delay: 500 ; pos: top ;animation: uk-animation-scale-up" uk-toggle> 
                    <i class="fas fa-plus"></i> Nuevo Cupón
                </button> 
            @endif                    
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif       

        <div class="uk-background-default uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 
                <table id="datatable"> 
                    <thead> 
                        <tr class="uk-text-small uk-text-bold"> 
                            <th class="uk-text-center">#</th> 
                            <th class="uk-text-center">Fecha de Creación</th>
                            <th class="uk-text-center">Nombre</th> 
                            <th class="uk-text-center">Descuento (%)</th> 
                            <th class="uk-text-center">Código</th>
                            <th class="uk-text-center">Estatus</th>
                            <th class="uk-text-center">Acción</th>
                        </tr>                             
                    </thead>                         
                    <tbody> 
                        @foreach ($cupones as $cupon)
                            <tr>      
                                <td class="uk-text-center">{{ $cupon->id }}</td>
                                <td class="uk-text-center">{{ date('d-m-Y', strtotime("$cupon->created_at -5 Hours")) }}</td>                                                           
                                <td class="uk-text-center">{{ $cupon->name }}</td> 
                                <td class="uk-text-center">{{ $cupon->discount }}%</td> 
                                <td class="uk-text-center">{{ $cupon->code }}</td>
                                <td class="uk-text-center">
                                    @if ($cupon->status == 0)
                                        <label class="uk-label uk-label-danger">Inactivo</label>
                                    @else
                                        <label class="uk-label uk-label-success">Activo</label>
                                    @endif
                                </td>
                                <td class="uk-text-center">
                                    <a class="uk-icon-button uk-button-success" uk-icon="icon: search;" uk-tooltip="Ver - Editar" href="{{ route('admins.coupons.show', $cupon->id) }}"></a> 
                                </td>
                            </tr>   
                        @endforeach  
                    </tbody>                         
                </table>                     
            </div>                            
        </div>            
    </div>

    <!-- Modal para Procesar Retiro -->                     
    <div id="create-modal" class="uk-modal-container" uk-modal> 
        <div class="uk-modal-dialog"> 
            <button class="uk-modal-close-default uk-padding-small" type="button" uk-close></button>     
            <div class="uk-modal-header"> 
                <h4> Crear Cupón</h4> 
            </div>                    
            <form action="{{ route('admins.coupons.store') }}" method="POST">  
                @csrf
                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-3">
                            Nombre (*):
                            <input class="uk-input" type="text" name="name" placeholder="Nombre del Cupón" required>
                        </div>
                        <div class="uk-width-1-3">
                            Descuento (*):
                            <input class="uk-input" type="number" name="discount" required>
                        </div>
                        <div class="uk-width-1-3">
                            Límite de Aplicaciones (*):
                            <input class="uk-input" type="number" name="limit" required>
                        </div>
						<div class="uk-width-1-1">
                            Categorías Aplicables(*):<br>
                            <div class="uk-child-width-1-4" uk-grid>
                            	<div>
							        <label><input class="uk-checkbox" type="checkbox" name="all_categories" id="selectall" value="Todas" checked> <i class="fas fa-globe"></i> Todas</label>
							    </div>
							    @foreach ($categorias as $categoria)
							    	<div>
							    		<label><input class="uk-checkbox case" type="checkbox" name="categories[]" value="{{ $categoria->id }}" checked> <i class="{{ $categoria->icon }}"></i> {{ $categoria->title }}</label>
							    	</div>
							 	@endforeach
							</div>
                        </div>
                    </div>                              
                </div>                             
                <div class="uk-modal-footer uk-text-right"> 
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>                                 
                    <button class="uk-button uk-button-primary" type="submit" id="btn-crear">Crear Cupón</button>
                </div>     
            </form>                        
        </div>                         
    </div>
@endsection