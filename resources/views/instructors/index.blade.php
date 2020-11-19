@extends('layouts.instructor')

@push('styles')
	<style>
		#ganancias {
		  width: 100%;
		  height: 400px;
		}

		#alumnos {
		  width: 100%;
		  height: 600px;
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
			categoryAxis.dataFields.category = "wallet";
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
			series.dataFields.categoryX = "wallet";
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
	
		<!-- Chart code -->
	<script>
		am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			// Create chart instance
			var chart = am4core.create("alumnos", am4charts.PieChart);

			var arreglo = <?php echo json_encode($alumnos);?>;
			chart.data = arreglo;

			// Add and configure Series
			var pieSeries = chart.series.push(new am4charts.PieSeries());
			pieSeries.dataFields.value = "alumnos";
			pieSeries.dataFields.category = "curso";
			pieSeries.innerRadius = am4core.percent(50);
			pieSeries.ticks.template.disabled = true;
			pieSeries.labels.template.disabled = true;

			var rgm = new am4core.RadialGradientModifier();
			rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);
			pieSeries.slices.template.fillModifier = rgm;
			pieSeries.slices.template.strokeModifier = rgm;
			pieSeries.slices.template.strokeOpacity = 0.4;
			pieSeries.slices.template.strokeWidth = 0;

			chart.legend = new am4charts.Legend();
			chart.legend.position = "bottom";

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
			<div class="uk-width-1-1">
				<div class="uk-card uk-card-default">
			    	<div class="uk-card-header">
			    		<h4>Ganancias Totales</h4>
			    	</div>
			    	<div class="uk-card-body" id="ganancias"></div>
		    	</div>
			</div>
			<div class="uk-width-1-2@m uk-width-1-1@s ">
				<div class="uk-card uk-card-default">
			    	<div class="uk-card-header">
			    		<h4>Saldo en Billetera T-Courses</h4>
			    	</div>
			    	<div class="uk-card-body uk-text-center" style="height: 334px;">
			    		<br>
			    		<p>
			    			<i class="fa fa-wallet" style="font-size: 8em;"></i>
			    		</p>
			    		<p>
			    			<h3 style="color: #41a8c4;">COP$ {{ number_format(Auth::user()->balance, 0, ',', '.') }}</h3>
			    		</p>
			    	</div>
			    	<div class="uk-card-footer uk-text-center">
			    		<a href="{{ route('instructors.commissions.index', 'courses') }}">Ver Detalles</a>
			    	</div>
		    	</div>
			</div>
			<div class="uk-width-1-2@m uk-width-1-1@s ">
				<div class="uk-card uk-card-default">
			    	<div class="uk-card-header">
			    		<h4>Saldo en Billetera T-Events</h4>
			    	</div>
			    	<div class="uk-card-body uk-text-center" style="height: 334px;">
			    		<br>
			    		<p>
			    			<i class="fa fa-wallet" style="font-size: 8em;"></i>
			    		</p>
			    		<p>
			    			<h3 style="color: #6E12FF;">COP$ {{ number_format(Auth::user()->event_balance, 0, ',', '.') }}</h3>
			    		</p>
			    	</div>
			    	<div class="uk-card-footer uk-text-center">
			    		<a href="{{ route('instructors.commissions.index', 'events') }}">Ver Detalles</a>
			    	</div>
		    	</div>
			</div>
		</div>

	

		<div class="uk-child-width-1-1" uk-grid>
			<div class="uk-card uk-card-default">
		    	<div class="uk-card-header">
		    		<h4>Alumnos por Curso</h4>
		    	</div>
		    	<div class="uk-card-body" id="alumnos"></div>
	    	</div>
		</div>

		<div class="uk-child-width-1-1" uk-grid>
		    <div class="uk-card uk-card-default">
		    	<div class="uk-card-header">
			    	<h4>Cursos Más Comprados</h4>
			    </div>
			    <div class="uk-card-body">
			    	<div class="uk-overflow-auto"> 
			           	<table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
			                <thead> 
			                    <tr class="uk-text-small uk-text-bold"> 
			                        <th class="uk-text-center">Cover</th> 
			                        <th class="uk-text-center">Título</th>  
			                        <th class="uk-text-center">Categoría</th>
			                        <th class="uk-text-center">Cantidad de Compras</th>
			                    </tr>                             
			                </thead>                         
			                <tbody> 
				                @foreach ($cursosMasComprados as $curso)
				                    <tr>                                 
				                        <td class="uk-text-center">
				                            <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/courses/'.$curso->course->cover) }}" width="50">
				                        </td>                                 
				                        <td class="uk-text-center">{{ $curso->course->title }}</td>
				                        <td class="uk-text-center">{{ $curso->course->category->title }} ({{ $curso->course->subcategory->title }})</td> 
				                        <td class="uk-text-center">{{ $curso->total }}</td>           
				                    </tr>   
				                @endforeach  
			                </tbody>                         
			            </table>                     
			       	</div>  
			    </div> 
		    </div>
		</div>
	</div>
@endsection