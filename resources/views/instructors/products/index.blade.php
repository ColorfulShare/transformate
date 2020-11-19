@extends('layouts.instructor')

@push('scripts')
    <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>
    <script>
        function cargarSubcategorias(){
            var categoria = document.getElementById("category_id").value;
            var route = "https://www.transformatepro.com/ajax/cargar-subcategorias/"+categoria;
            //var route = "http://localhost:8000/ajax/cargar-subcategorias/"+categoria;
                        
            $.ajax({
                url:route,
                type:'GET',
                success:function(ans){
                    document.getElementById("subcategory_id").innerHTML = "";
                    document.getElementById("subcategory_id").innerHTML  += '<option value="" selected disabled>Seleccione una subcategoría</option>';
                    for (var i = 0; i < ans.length; i++){
                        document.getElementById("subcategory_id").innerHTML += '<option value="'+ans[i].id+'">'+ans[i].title+'</option>';
                    }
                }
            });
        }
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

        <div class="uk-clearfix boundary-align"> 
            <div class="uk-clearfix boundary-align"> 
                <div class="uk-float-left section-heading none-border coco"> 
                    <h2 class="uk-text-black">Mis Productos</h2> 
                </div> 

                <div class="uk-float-right section-heading none-border"> 
                    <a href="{{ route('instructors.products.create') }}" class="uk-button uk-button-danger"><i class="fa fa-plus-circle"></i> Crear Producto</a>
                </div>     
            </div>                  
        </div>
        
        @if ($cantProductos > 0)
            <div class="uk-child-width-1-2@s uk-child-width-1-4@m uk-grid-match uk-margin uk-grid uk-grid-stack" > 
                @foreach($productos as $producto)                            
                    <div class="uk-scrollspy-inview uk-margin-top uk-margin-bottom"> 
                        <div class="uk-card-default uk-card-hover uk-card-small uk-inline-clip">
                            <img src="{{asset('uploads/images/products/'.$producto->cover)}}" class="course-img"> 
                            <div class="uk-card-body">
                                <h3 class="uk-card-title">{{ $producto->name }}</h3>
                                <div class="uk-grid">
                                    <div class="wt uk-margin-top uk-margin-bottom">
                                        <a href="{{ route('instructors.products.edit', [$producto->slug, $producto->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: pencil;" uk-tooltip="Editar"></a>
                                    </div>
                                    <div class="wt uk-margin-top uk-margin-bottom">
                                        <a href="{{ route('instructors.products.purchases-record', [$producto->slug, $producto->id]) }}" class="uk-icon-button uk-button-danger" uk-icon="icon: cart;" uk-tooltip="Compras"></a>
                                    </div> 
                                </div> 
                            </div>                                                                      
                        </div>                                 
                    </div> 
                @endforeach
            </div>
        @else
            <div class="paper">
                <center>
                    <h5>Usted no posee ningún producto aún. <br>
                    Cree su primer producto a continuación..</h5>
                </center>

                <div class="uk-card-default">
                    <form class="uk-form-horizontal uk-margin-large" action="{{ route('instructors.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf 

                        <div class="uk-card-body">
                            <div class="uk-grid">
                                <div class="uk-width-1-1">
                                    <label class="uk-form-label" for="title"><b>Nombre:</b></label>
                                    <input class="uk-input" name="name" type="text" value="{{ old('name') }}" placeholder="Nombre del Producto"> 
                                </div> 

                                <div class="uk-width-1-3">
                                    <label class="uk-form-label" for="category_id"><b>Categoría:</b></label>
                                    <select class="uk-select" id="category_id" name="category_id" onchange="cargarSubcategorias();">
                                        <option value="" selected disabled>Seleccione una opción...</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="uk-width-1-3">
                                    <label class="uk-form-label" for="subcategory_id"><b>Subcategoría:</b></label>
                                    <select class="uk-select" id="subcategory_id" name="subcategory_id">
                                        <option value="" selected disabled>Seleccione una opción...</option>
                                    </select>
                                </div>

                                <div class="uk-width-1-3">
                                    <label class="uk-form-label" for="price"><b>Precio:</b></label>
                                    <input class="uk-input" id="price" name="price" type="number"> 
                                </div>

                                <div class="uk-width-1-2">
                                    <label class="uk-form-label" for="price"><b>Imagen Referencial:</b></label>
                                    <input class="uk-input" id="cover" name="cover" type="file"> 
                                </div>

                                <div class="uk-width-1-2">
                                    <label class="uk-form-label" for="price"><b>Archivo:</b></label>
                                    <input class="uk-input" id="file" name="file" type="file"> 
                                </div>

                                <label class="uk-form-label" for="description"><b>Descripción:</b></label><br>
                                <div class="uk-margin uk-width-1-1">
                                    <textarea class="ckeditor" name="description" id="description" rows="5">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>  

                        <div class="uk-card-footer uk-text-right">
                            <button type="submit" class="uk-button uk-button-danger">Crear Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
        
        {{ $productos->links() }}
    </div>
@endsection