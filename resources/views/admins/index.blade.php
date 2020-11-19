@extends('layouts.admin')

@push('styles')
	<style>
		#ventas {
		  width: 100%;
		  height: 400px;
		}

		#usuarios {
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
			// Create chart instance
			var chart = am4core.create("ventas", am4charts.XYChart3D);

			var arreglo = <?php echo json_encode($ventas);?>;
			chart.data = arreglo;

			// Create axes
			let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "mes";
			categoryAxis.renderer.labels.template.rotation = 270;
			categoryAxis.renderer.labels.template.hideOversized = false;
			categoryAxis.renderer.minGridDistance = 20;
			categoryAxis.renderer.labels.template.horizontalCenter = "right";
			categoryAxis.renderer.labels.template.verticalCenter = "middle";
			categoryAxis.tooltip.label.rotation = 270;
			categoryAxis.tooltip.label.horizontalCenter = "right";
			categoryAxis.tooltip.label.verticalCenter = "middle";

			let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
			valueAxis.title.text = "Ventas";
			valueAxis.title.fontWeight = "bold";

			// Create series
			var series = chart.series.push(new am4charts.ColumnSeries3D());
			series.dataFields.valueY = "ventas";
			series.dataFields.categoryX = "mes";
			series.name = "Ventas";
			series.tooltipText = "{categoryX}: [bold]{valueY}[/]";
			series.columns.template.fillOpacity = .8;

			var columnTemplate = series.columns.template;
			columnTemplate.strokeWidth = 2;
			columnTemplate.strokeOpacity = 1;
			columnTemplate.stroke = am4core.color("#FFFFFF");

			columnTemplate.adapter.add("fill", function(fill, target) {
			  return chart.colors.getIndex(target.dataItem.index);
			})

			columnTemplate.adapter.add("stroke", function(stroke, target) {
			  return chart.colors.getIndex(target.dataItem.index);
			})

			chart.cursor = new am4charts.XYCursor();
			chart.cursor.lineX.strokeOpacity = 0;
			chart.cursor.lineY.strokeOpacity = 0;
		});
	</script>

	<script>
		am4core.ready(function() {
			am4core.useTheme(am4themes_animated);
			var chart = am4core.create("usuarios", am4charts.XYChart);

			var arreglo = <?php echo json_encode($usuarios);?>;
			chart.data = arreglo;

			var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "usuario";
			categoryAxis.renderer.grid.template.location = 0;
			categoryAxis.renderer.minGridDistance = 30;

			/*categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
			  if (target.dataItem && target.dataItem.index & 2 == 2) {
			    return dy + 25;
			  }
			  return dy;
			});*/

			var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

			// Create series
			var series = chart.series.push(new am4charts.ColumnSeries());
			series.dataFields.valueY = "cantidad";
			series.dataFields.categoryX = "usuario";
			series.name = "Cantidad";
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
	
	<script>
		$(document).ready( function () {
			$('#courses-table').DataTable( {
			    dom: '<Bf<t>ip>',
			    responsive: true,
			    order: [[ 4, "desc" ]],
			    pageLength: 15,
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

			$('#users-table').DataTable({
			    dom: '<Bf<t>ip>',
			    responsive: true,
			    order: [[ 4, "desc" ]],
			    pageLength: 15,
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
	    <div class="uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
	    	<div>
		    	<div class="uk-width-1-1 uk-card uk-card-default">
		    		<div class="uk-card-header">
		    			<h3 class="uk-card-title uk-margin-remove-bottom">Ventas Mensuales</h3>
		    		</div>
		    		<div class="uk-card-body" id="ventas"></div>
		    	</div>
	    	</div>

	    	<div>
	    		<div class="uk-width-1-1 uk-card uk-card-default">
		    		<div class="uk-card-header">
		    			<h3 class="uk-card-title uk-margin-remove-bottom">Usuarios Registrados</h3>
		    		</div>
		    		<div class="uk-card-body" id="usuarios"></div>
		    	</div>
			</div>
		</div>

		<div class="uk-child-width-1-1" uk-grid>
			<div>
		    	<div class="uk-card uk-card-default">
		    		<div class="uk-card-header">
			    		<h3 class="uk-card-title uk-margin-remove-bottom">Cursos Más Comprados</h3>
			    	</div>
			    	<div class="uk-card-body">
			    		<div class="uk-overflow-auto"> 
			            	<table id="courses-table"> 
			                	<thead> 
			                    	<tr class="uk-text-small uk-text-bold"> 
			                        	<th class="uk-text-center">Cover</th> 
			                        	<th class="uk-text-center">Título</th> 
			                        	<th class="uk-text-center">Instructor</th> 
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
				                            <td class="uk-text-center">{{ $curso->course->user->names }} {{ $curso->course->user->last_names }}</td> 
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

	    	<div>
	    		<div class="uk-card uk-card-default">
		    		<div class="uk-card-header">
			    		<h3 class="uk-card-title uk-margin-remove-bottom">Mentores con más ganancias</h3>
			    	</div>
			    	<div class="uk-card-body">
			    		<div class="uk-overflow-auto"> 
			            	<table id="users-table"> 
			                	<thead> 
			                    	<tr class="uk-text-small uk-text-bold"> 
			                        	<th class="uk-text-center">Avatar</th> 
			                        	<th class="uk-text-center">Mentor</th> 
			                        	<th class="uk-text-center">Ubicación</th>
			                        	<th class="uk-text-center">Miembro Desde</th>
			                        	<th class="uk-text-center">Ganancia</th>
			                    	</tr>                             
			                	</thead>                         
			                	<tbody> 
				                    @foreach ($mentoresMasGanancias as $mentor)
				                        <tr>                                 
				                            <td class="uk-text-center">
				                                <img class="uk-preserve-width uk-border-circle user-profile-small" src="{{ asset('uploads/images/users/'.$mentor->user->avatar) }}" width="50">
				                            </td> 
				                            <td class="uk-text-center">{{ $mentor->user->names }} {{ $mentor->user->last_names }}</td> 
				                            <td class="uk-text-center">{{ $mentor->user->state }} ({{ $mentor->user->country }})</td> 
				                            <td class="uk-text-center">{{ date('Y-m-d H:i A', strtotime("$mentor->user->created_at -5 Hours")) }}</td>
				                            <td class="uk-text-center">COP$ {{ $mentor->ganancia }}</td>
				                        </tr>   
				                    @endforeach  
			                	</tbody>                         
			            	</table>                     
			       	 	</div>   
			       	 </div>
			    </div>
	    	</div>
	    </div>
	</div>
@endsection