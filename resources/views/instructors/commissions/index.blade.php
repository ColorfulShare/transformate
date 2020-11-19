@extends('layouts.instructor')

@section('content')
	<div class="uk-container">
		<div class="uk-card-default">
	        <div class="uk-card-header">
				@if ($type == 'courses')
					Mis Ganancias T-Courses
				@elseif ($type == 'events')
					Mis Ganancias T-Events
				@else
					Mis Ganancias Globales
				@endif

	            <div class="uk-align-right uk-text-right">
					@if ($type == 'courses')
	            		Saldo Disponible T-Courses: <span style="color: #41a8c4;">COP$ {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
					@elseif ($type == 'events')
						Saldo Disponible T-Events: <span style="color: #6E12FF;">COP$ {{ number_format(Auth::user()->event_balance, 0, ',', '.') }}</span>
					@else
						Saldo Disponible T-Courses: <span style="color: #41a8c4;">COP$ {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span><br>
						Saldo Disponible T-Events: <span style="color: #6E12FF;">COP$ {{ number_format(Auth::user()->event_balance, 0, ',', '.') }}</span>	
					@endif
				</div>
	        </div>  

	        <div class="uk-card-body">
	            <div class="uk-overflow-auto">
	                <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
	                    <thead>
	                        <tr>
	                            <th class="uk-width-small uk-text-center"><b>Fecha</b></th>
	                            <th class="uk-table-shrink uk-text-center"><b>Referido</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Monto</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Tipo</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Status</b></th>
	                            <th class="uk-width-small uk-text-center"><b>Acción</b></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                        @foreach ($ganancias as $ganancia)
	                            <tr>
	                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$ganancia->created_at -5 Hours")) }}</td>
	                                <td class="uk-text-center">
										@if (!is_null($ganancia->referred_id))
											{{ $ganancia->referred->names }} {{ $ganancia->referred->last_names }}
										@else
											{{ $ganancia->event_subscription->names }}
										@endif
									</td>
	                                <td class="uk-text-center">COP$ {{ number_format($ganancia->amount, 0, ',', '.') }} </td> 
	                                <td class="uk-width-small uk-text-center">{{ $ganancia->type }}</td>
	                                <td class="uk-width-small uk-text-center">
	                                    @if ($ganancia->status == 0)
	                                        <span class="uk-label uk-label-danger">Sin Cobrar</span>
	                                    @elseif ($ganancia->status == 1)
	                                        <span class="uk-label uk-label-warning">En Proceso</span>
	                                    @else
	                                        <span class="uk-label uk-label-success">Cobrada</span>
	                                    @endif
	                                </td>
	                                <td class="uk-width-small uk-text-center"><a class="uk-button uk-button-primary" href="{{ route('instructors.commissions.show', $ganancia->id) }}">Ver Más</a></td>
	                            </tr>    
	                        @endforeach
	                    </tbody>
	                </table>
	            </div>
	       </div> 

	       <div class="uk-card-footer uk-text-center">
	       		{{ $ganancias->links() }}
	       </div>
	    </div>  
	</div>
@endsection