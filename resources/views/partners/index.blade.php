@extends('layouts.partner')

@push('styles')
	<style>
		#ganancias {
		  	width: 100%;
		  	height: 400px;
		}
	</style>
@endpush

@push('scripts')
	<script src="https://www.amcharts.com/lib/4/core.js"></script>
	<script src="https://www.amcharts.com/lib/4/charts.js"></script>
	<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

	<script>
		am4core.ready(function() {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("ganancias", am4charts.XYChart);

			var arreglo = <?php echo json_encode($ganancias);?>;
			chart.data = arreglo;

			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "type";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.minGridDistance = 30;

			categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
			  if (target.dataItem && target.dataItem.index & 2 == 2) {
			    return dy + 25;
			  }
			  return dy;
			});

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

			// Create series
			var series = chart.series.push(new am4charts.ColumnSeries());
			series.dataFields.valueY = "total";
			series.dataFields.categoryX = "type";
			series.name = "ganancia";
			series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
			series.columns.template.fillOpacity = .8;

			var columnTemplate = series.columns.template;
			columnTemplate.strokeWidth = 2;
			columnTemplate.strokeOpacity = 1;

			columnTemplate.adapter.add("fill", function(fill, target) {
				return chart.colors.getIndex(target.dataItem.index);
			})

			columnTemplate.adapter.add("stroke", function(stroke, target) {
				return chart.colors.getIndex(target.dataItem.index);
			})
		}); // end am4core.ready()
	</script>
@endpush

@section('content')
 	<div class="uk-container uk-margin-medium-top" uk-scrollspy="target: > div; cls:uk-animation-slide-bottom-medium; delay: 200">  
 		@if (Session::has('msj-exitoso'))
	        <div class="uk-alert-success" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-exitoso') }}</strong>
	        </div>
	    @endif

	    @if (Session::has('msj-erroneo'))
	        <div class="uk-alert-danger" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-erroneo') }}</strong>
	        </div>
	    @endif
		<div uk-grid>
			<div class="uk-width-2-3@m uk-width-1-1 ">
				<div class="uk-card uk-card-default">
			    	<div class="uk-card-header">
			    		<h4>Ganancias Totales</h4>
			    	</div>
			    	<div class="uk-card-body" id="ganancias"></div>
		    	</div>
			</div>
			<div class="uk-width-1-3@m uk-width-1-1@s ">
				<div class="uk-card uk-card-default">
			    	<div class="uk-card-header">
			    		<h4>Saldo en Billetera</h4>
			    	</div>
			    	<div class="uk-card-body uk-text-center" style="height: 334px;">
			    		<br>
			    		<p>
			    			<i class="fa fa-wallet" style="font-size: 8em;"></i>
			    		</p>
			    		<p>
			    			<h3>COP$ {{ Auth::user()->balance }}</h3>
			    		</p>
			    	</div>
			    	<div class="uk-card-footer uk-text-center">
			    		<a href="#">Ver Detalles</a>
			    	</div>
		    	</div>
			</div>
		</div>

		<div class="uk-child-width-1-1" uk-grid>
		    <div class="uk-card uk-card-default">
		    	<div class="uk-card-header">
			    	<h4>Mis Ganancias Recientes</h4>
			    </div>
			    <div class="uk-card-body">
			    	<div class="uk-overflow-auto"> 
			           	<table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
			                <thead> 
			                    <tr class="uk-text-small uk-text-bold"> 
			                        <th class="uk-text-center">#</th> 
			                        <th class="uk-text-center">Referido</th>  
			                        <th class="uk-text-center">Monto</th>
			                        <th class="uk-text-center">Tipo de Compra</th>
			                        <th class="uk-text-center">Estado</th>
			                        <th class="uk-text-center">Acciones</th>
			                    </tr>                             
			                </thead>                         
			                <tbody> 
				                @foreach ($gananciasRecientes as $ganancia)
				                    <tr> 
				                    	<td class="uk-text-center">{{ $ganancia->id }}</td>                                  
				                        <td class="uk-text-center">{{ $ganancia->referred->names }} {{ $ganancia->referred->last_names }}</td>
				                        <td class="uk-text-center">COP$ {{ number_format($ganancia->amount, 0, ',', '.') }} </td> 
				                        <td class="uk-text-center">@if (!is_null($ganancia->purchase_detail_id)) Compra Interna @else Compra T-Events @endif</td>
				                        <td class="uk-text-center">
				                        	@if ($ganancia->status == 0)
		                                        <span class="uk-label uk-label-danger">Sin Cobrar</span>
		                                    @elseif ($ganancia->status == 1)
		                                        <span class="uk-label uk-label-warning">En Proceso</span>
		                                    @else
		                                        <span class="uk-label uk-label-success">Cobrada</span>
		                                    @endif
				                        </td>
				                        <td class="uk-text-center"></td>
				                    </tr>   
				                @endforeach  
			                </tbody>   
			                <tfooter>
			                	<tr class="uk-text-small uk-text-bold"> 
			                        <th class="uk-text-center" colspan="6"><a href="{{ route('partners.commissions.index') }}">Ver Más</a></th> 
			                    </tr> 
			                </tfooter>                      
			            </table>                     
			       	</div>
			    </div> 
		    </div>
		</div>
	</div>
@endsection