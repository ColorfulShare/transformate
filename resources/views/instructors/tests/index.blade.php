@extends('layouts.instructor')

@push('scripts')
    <script>
        function cargarModulos(){
        	var cont = document.getElementById("tipo_contenido").value;
            if (cont == 'courses'){
                var contenido = 'curso';
            }else{
                var contenido = 'certificacion';
            }
            var id = document.getElementById("id_contenido").value; 
        	
            var route = "https://transformatepro.com/ajax/modulos-por-curso/"+id+"/"+contenido;
            //var route = "http://localhost:8000/ajax/modulos-por-curso/"+id+"/"+contenido;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    document.getElementById("modulo").innerHTML = "";
                    for (var i = 0; i < ans.length; i++){
                        document.getElementById("modulo").innerHTML += '<option value="'+ans[i].id+'">'+ans[i].title+'</option>';
                    }

                    var modal = UIkit.modal("#modalEvaluacion");
                    modal.show();
                }
            });
        }

        function eliminarEvaluacion($id_evaluacion){
            document.getElementById("test_id").value = $id_evaluacion;
            var modal = UIkit.modal("#modalEliminar");
            modal.show();
        }
    </script>
@endpush

@section('content')
	<div class="uk-container">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

		<div class="uk-card-default">
		   	<div class="uk-card-header">
		        <div uk-grid> 
	                <div class="uk-width-expand@m"> 
	                    Evaluaciones                      
	                </div>                         
	                <div class="uk-width-auto@m uk-text-small uk-text-right"> 
                        <a href="#" class="uk-button uk-button-success" onclick="cargarModulos();"><i class="fa fa-plus-circle"></i> Crear Evaluación</a>
	                </div>                         
	            </div>   
		    </div> 

		    <div class="uk-card-body">
                @if ($cantEvaluaciones > 0)
    				@foreach ($evaluaciones as $evaluacion)
    					<div class="uk-padding-small uk-card-hover border-radius-6" uk-grid>
    				        <div class="uk-padding-remove-left uk-visible@m">
    				            @if (!is_null($evaluacion->module->course_id)) 
    				            	<img class="uk-border-circle user-profile-tiny img-small" src="{{ asset('uploads/images/courses/'.$evaluacion->module->course->cover) }}"> 
    				            @elseif (!is_null($evaluacion->module->certification_id))
    								<img class="uk-border-circle user-profile-tiny img-small" src="{{ asset('uploads/images/certifications/'.$evaluacion->module->certification->cover) }}"> 
    							@endif
    				        </div>                         
    				        <div class="uk-width-Expand"> 
    				            <a href="{{ route('instructors.tests.show', [$evaluacion->slug, $evaluacion->id]) }}" class="uk-link-reset">
    				            	<h4 class="uk-margin-small-bottom">{{ $evaluacion->title }}</h4>
    				                Módulo: <b>{{ $evaluacion->module->title }}</b>
    				            </a>
    				        </div>
    				        <div class="uk-width-medium uk-flex uk-flex-middle uk-visible@m">
    				            <span> 
    				            	<a href="{{ route('instructors.tests.show-resume', [$evaluacion->slug, $evaluacion->id]) }}">	Presentaciones: <b>{{ $evaluacion->presentations }}</b>
    				            	</a><br>
    				            	Promedio de Nota: <b>{{ number_format($evaluacion->average) }}/100 </b>
    				            </span>
                                <span class="uk-align-right"> 
                                    <a class="uk-icon-button uk-button-danger" uk-icon="icon: trash;" uk-tooltip="Eliminar" onclick="eliminarEvaluacion({{ $evaluacion->id }});"></a>
                                </span>
    					    </div>
    					</div>
    				    <hr >
    				@endforeach

    				{{ $evaluaciones->links() }}
                @else
                    @if ($content_type == 'courses') 
                        El T-Course no posee ninguna evaluación aún...
                    @else
                        La T-Mentoring no posee ninguna evaluación aún...              
                    @endif
                @endif
		    </div>	
	    </div>
	</div>

	<!-- Modal de Cargar Evaluación -->
    <div id="modalEvaluacion" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Cargar Evaluación</h4>
            </div>
            <form action="{{ route('instructors.tests.create') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo_contenido" id="tipo_contenido" value="{{ $content_type }}">
                <input type="hidden" name="id_contenido" id="id_contenido" value="{{ $id }}">

                <div class="uk-modal-body">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <b>Módulo</b>
                            <select class="uk-select" name="modulo" id="modulo"></select>
                        </div>

                        <div class="uk-width-1-1">
                            <b>Cantidad de Preguntas</b>
                            <input class="uk-input" type="number" name="cant_preguntas">
                        </div>
                    </div>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Continuar</button>
                </div>
            </form>
        </div>
    </div>

     <!-- Modal de Eliminar Evaluación -->
    <div id="modalEliminar" uk-modal>
        <div class="uk-modal-dialog">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
                <h4>Eliminar Evaluación</h4>
            </div>
            <form action="{{ route('instructors.tests.delete') }}" method="POST">
                @csrf

                <input type="hidden" name="test_id" id="test_id">

                <div class="uk-modal-body">
                    <center>
                        ¿Está seguro que desea eliminar la evaluación y todas sus presentaciones?<br>
                        <span style="color: red;">Advertencia: Esta operación no puede ser revertida.</span>
                    </center>
                </div>
                <div class="uk-modal-footer uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                    <button class="uk-button uk-button-primary" type="submit">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
@endsection