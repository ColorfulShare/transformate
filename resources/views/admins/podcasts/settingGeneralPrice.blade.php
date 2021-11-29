@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner"> 
        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-video icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Establecer Precio General</h4>                          
        </div>  

        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif  
        
        <div class="uk-background-default uk-margin-top uk-padding"> 
            <div class="uk-overflow-auto uk-margin-small-top"> 

                
                <form class="uk-grid-small" uk-grid action="{{ route('admins.podcasts.set-general-price') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="podcasts">
                    <div class="uk-width-1-2">
                        <div class="uk-form-controls">
                            <input class="uk-input" id="price" name="price" type="text" placeholder="Ingrese el precio aquí" required>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <button class="uk-button uk-button-primary">Cambiar Precio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection