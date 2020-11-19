@extends('layouts.admin')

@push('styles')
    <style>
        .content-image{
            max-height: 10em;
            max-width: 50em;
            padding: 5px;
        }

        .label-slider{
        	border: 1.5px solid #3197B9; 
        	box-sizing: border-box; 
        	padding: 0 50px;
		    vertical-align: middle;
		    font-size: 16px;
		    line-height: 38px;
		    background-color: white;
        }

        .divider-slider{
        	padding-top: 35px;
        }
    </style>
@endpush

@push('scripts')
	<script>
		function actualizarOpcion($slider){
			if ($slider == 'vendidos'){
				if ($("#vendidos").prop('checked') == true){
					var value = 1;
				}else{
					var value = 0;
				}
			}else if ($slider == 'cursados'){
				if ($("#cursados").prop('checked') == true){
					var value = 1;
				}else{
					var value = 0;
				}
			}else{
				if ($("#recientes").prop('checked') == true){
					var value = 1;
				}else{
					var value = 0;
				}
			}
			
			var parametros = {"content_type": 'certifications', "slider" : $slider, "value" : value};
			var url = {{ $www }};
            if (url == 1){
				var route = "https://www.transformatepro.com/admins/t-mentorings/update-home-sliders";
			}else{
				var route = "https://transformatepro.com/admins/t-mentorings/update-home-sliders";
			}
			//var route = "http://localhost:8000/admins/t-mentorings/update-home-sliders";

			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')     
                },
                url: route,
                type:'POST',
                data:  parametros,
                success:function(ans){
                    UIkit.notification({message:'<i class="fa fa-check"></i> Actualización Exitosa.', status: 'success'});
                }
            });
		}
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
                    <li style="border-right: 1px solid #000;"><a href="{{ route('admins.certifications.home-cover') }}">Portada del Home</a></li>
                    <li class="uk-active" style="border-right: 1px solid #000;"><a href="{{ route('admins.certifications.home-sliders') }}">Carruseles del Home</a></li>
                    <li><a href="{{ route('admins.certifications.featured') }}">Gestionar Destacados</a></li>
                </ul>
            </div>
        </nav>

		<div class="uk-grid uk-child-width-1-2 uk-margin-small-bottom">
		    <div class="uk-text-left">
			    <h4>T-Mentorings Más Vendidas</h4>
			</div>
			<div class="uk-text-right">
				<label class="label-slider"><input class="uk-checkbox" type="checkbox" name="vendidos" id="vendidos" @if ($configuracion->most_sellers_slider_certification == 1) checked @endif onchange="actualizarOpcion('vendidos');"> Mostrar en T-Mentoring</label>
			</div>
		</div>
		
		@if ($cantCertificacionesMasVendidas > 0)
			<div uk-slider class="content-carousel">
		        <div class="uk-position-relative">
		            <div class="uk-slider-container uk-light">
		                <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
		                    @foreach ($certificacionesMasVendidas as $certificacionVendida)      
		                        <li class="course-li">
	                                <div class="uk-card uk-card-default uk-card-small uk-card-courses">
	                                    <div class="uk-card-media-top">
	                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionVendida->certification->cover) }}" class="content-image">
	                                    </div>
	                                    <div class="uk-card-body">
                                            <h3 class="uk-card-title tile2">{{ $certificacionVendida->certification->title }}</h3>
                                            <p class="tile2">Una Mentoria de: {{ $certificacionVendida->certification->user->names.' '.$certificacionVendida->certification->user->last_names }}</p>
                                            <p class="tile3">{{ $certificacionVendida->certification->subtitle }}</p>
                                            <p class="tile4">
                                                <span class="desc">COP$ {{ number_format($certificacionVendida->certification->price*1.50, 0, ',', '.') }}</span><br>
                                                COP$ {{ number_format($certificacionVendida->certification->price, 0, ',', '.') }}
                                            </p>
                                        </div>
	                                </div>
	                            </li>
		                     @endforeach
		                </ul>                       
		            </div>
		            <div class="uk-hidden@s uk-light">
		                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
		                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
		            </div>
			        <div class="uk-visible@s">
			            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
			            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
			        </div> 
		            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
		        </div>
	        </div>
	    @else
	        No hay ninguna T-Mentoring vendida aún...
	    @endif

        <div class="uk-grid uk-child-width-1-2 uk-margin-small-bottom divider-slider">
		    <div class="uk-text-left">
			    <h4>T-Mentoring Más Cursadas</h4>
			</div>
			<div class="uk-text-right">
				<label class="label-slider"><input class="uk-checkbox" type="checkbox" name="cursados" id="cursados" @if ($configuracion->most_taken_slider_certification == 1) checked @endif onchange="actualizarOpcion('cursados');"> Mostrar en T-Mentoring</label>
			</div>
		</div>
		
		@if ($cantCertificacionesMasCursadas > 0)
			<div uk-slider class="content-carousel">
		        <div class="uk-position-relative">
		            <div class="uk-slider-container uk-light">
		               	<ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
		                    @foreach ($certificacionesMasCursadas as $certificacionCursada)      
		                        <li class="course-li">
	                                <div class="uk-card uk-card-default uk-card-small uk-card-courses">
	                                    <div class="uk-card-media-top">
	                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionCursada->cover) }}" class="content-image">
	                                    </div>
	                                    <div class="uk-card-body">
                                            <h3 class="uk-card-title tile2">{{ $certificacionCursada->title }}</h3>
                                            <p class="tile2">Una Mentoria de: {{ $certificacionCursada->user->names.' '.$certificacionCursada->user->last_names }}</p>
                                            <p class="tile3">{{ $certificacionCursada->subtitle }}</p>
                                            <p class="tile4">
                                                <span class="desc">COP$ {{ number_format($certificacionCursada->price*1.50, 0, ',', '.') }}</span><br>
                                                COP$ {{ number_format($certificacionCursada->price, 0, ',', '.') }}
                                            </p>
                                        </div>
	                                </div>
	                            </li>
		                    @endforeach
		                </ul>                       
		            </div>
		            <div class="uk-hidden@s uk-light">
		                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
		                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
		            </div>
			        <div class="uk-visible@s">
			            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
			            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
			        </div> 
		            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
		        </div>
	        </div>
	    @else
	        No hay ninguna T-Mentoring cursada aún...
	    @endif
			
		<div class="uk-grid uk-child-width-1-2 uk-margin-small-bottom divider-slider">
		    <div class="uk-text-left">
			    <h4>T-Mentoring Más Recientes</h4>
			</div>
			<div class="uk-text-right">
				<label class="label-slider"><input class="uk-checkbox" type="checkbox" name="recientes" id="recientes" @if ($configuracion->most_recent_slider_certification == 1) checked @endif onchange="actualizarOpcion('recientes');"> Mostrar en T-Mentoring</label>
			</div>
		</div>

		@if ($cantCertificacionesMasRecientes > 0)
			<div uk-slider class="content-carousel">
		        <div class="uk-position-relative">
		            <div class="uk-slider-container uk-light">
		                <ul class="uk-slider-items uk-child-width-1-1@xs uk-child-width-1-2@s uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-4@xl uk-grid">
		                    @foreach ($certificacionesMasRecientes as $certificacionReciente)      
		                        <li class="course-li">
	                                <div class="uk-card uk-card-default uk-card-small uk-card-courses">
	                                    <div class="uk-card-media-top">
	                                        <img src="{{ asset('uploads/images/certifications/'.$certificacionReciente->cover) }}" class="content-image">
	                                    </div>
	                                    <div class="uk-card-body">
                                            <h3 class="uk-card-title tile2">{{ $certificacionReciente->title }}</h3>
                                            <p class="tile2">Una Mentoria de: {{ $certificacionReciente->user->names.' '.$certificacionReciente->user->last_names }}</p>
                                            <p class="tile3">{{ $certificacionReciente->subtitle }}</p>
                                            <p class="tile4">
                                                <span class="desc">COP$ {{ number_format($certificacionReciente->price*1.50, 0, ',', '.') }}</span><br>
                                                COP$ {{ number_format($certificacionReciente->price, 0, ',', '.') }}
                                            </p>
                                        </div>
	                                </div>
	                            </li>
		                    @endforeach
		                </ul>                       
		            </div>
		            <div class="uk-hidden@s uk-light">
		                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
		                 <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
		            </div>
			        <div class="uk-visible@s">
			            <a class="uk-position-center-left-out uk-position-small uk-text-white" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
			            <a class="uk-position-center-right-out uk-position-small uk-text-white" href="#" uk-slidenav-next uk-slider-item="next"></a>
			        </div> 
		            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
		        </div>
	        </div>
	    @else
	        No hay ninguna T-Mentoring creada recientemente...
	    @endif
	</div>
@endsection