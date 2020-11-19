@extends('layouts.instructor')

@push('styles')
	<link rel="stylesheet" href="{{ asset('template/css/marketplace.css') }}">
@endpush

@section('content')
	<div class="marketplace">
		<div class="uk-width-1-1 categories-products">
			@if ($cantProductos > 0)
				<h4 class="category-title">
					Mis Productos
				</h4><br>
				<div class="uk-child-width-1-4" uk-grid>
					@foreach ($productos as $producto)
						<div class="uk-scrollspy-inview uk-margin-top uk-margin-bottom"> 
							<div class="uk-card-default uk-card-hover uk-card-small uk-inline-clip">
								<a href="{{ asset('uploads/products/'.$producto->file) }}">
									<img src="{{asset('uploads/images/products/'.$producto->cover)}}" class="product-img">  
									<div class="uk-card-body" style="padding: 5px 15px 10px 15px;">
								        <span>{{ $producto->name }}<br></span>
									</div>
								</a>
								   
								   
							</div>            
						</div>
					@endforeach
				</div>
			@else
				<br>
				<center>
					<h4 class="no-product">No posee ningún producto comprado aún...</h4><br>
					<h5><a href="{{ route('landing.marketplace') }}">Ir Al Marketplace</a></h5>
					
				</center>
			@endif
		</div>
	</div>
	
@endsection