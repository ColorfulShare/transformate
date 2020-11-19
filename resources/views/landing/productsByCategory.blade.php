@extends('layouts.coursesLanding')

@push('styles')
	<link rel="stylesheet" href="{{ asset('template/css/marketplace.css') }}">
@endpush

@section('content')
	<div class="marketplace marketplace-background">
		<div class="uk-grid">
			<div class="uk-width-1-4">
				<div class="categories-filter">
					<center><h5 class="filter-title">Categorías</h5></center><hr>
					<ul uk-accordion>
						@foreach ($categoriasP as $categoria)
							<li @if ($categoria->id == $id_category) class="uk-open" @endif>
								<a class="uk-accordion-title bold" href="#"><i class="{{ $categoria->icon }}"></i> {{ $categoria->title }}</a>
								<div class="uk-accordion-content">
									<ul>
							            @foreach ($categoria->subcategories as $subcategoria)
							            	<li><a href="{{ route('landing.marketplace.products-by-category', [$categoria->slug, $categoria->id, $subcategoria->slug, $subcategoria->id]) }}" @if ($subcategoria->id == $id_subcategory) class="selected" @endif>{{ $subcategoria->title }}</a></li>
							            @endforeach
						        	</ul>
						        </div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
			<div class="uk-width-3-4 categories-products">
				@if ($cantProductos > 0)
					<h4 class="category-title">
						{{ $categoriaSelec->title }} @if (!is_null($subcategoriaSelec)) <span class="show-more">({{ $subcategoriaSelec->title }})</span> @endif
					</h4>
					<div class="uk-child-width-1-4" uk-grid>
						@foreach ($productos as $producto)
							<div class="uk-scrollspy-inview uk-margin-top uk-margin-bottom"> 
								<div class="uk-card-default uk-card-hover uk-card-small uk-inline-clip">
								    <img src="{{asset('uploads/images/products/'.$producto->cover)}}" class="product-img"> 
								    <div class="uk-card-body" style="padding: 5px 15px 10px 15px;">
								        <span>{{ $producto->name }}<br></span>
										<a href="{{ route('landing.shopping-cart.store', [$producto->id, 'producto']) }}" class="uk-button uk-button-primary">
											<span class="pol">
											    <i class="fa fa-shopping-cart"></i>
											    COP$ {{ number_format($producto->price, 0, ',', '.') }}
											</span>
										</a>
									</div>
								</div>            
							</div>
						@endforeach
					</div>
				@else
					<br>
					<center>
						<h4 class="no-product">No existe ningún producto relacionado con su búsqueda...</h4>
					</center>
				@endif
			</div>
		</div>
	</div>
	
@endsection