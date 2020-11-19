@extends('layouts.admin')

@section('content')
	<div class="admin-content-inner"> 
		@if (Session::has('msj-exitoso'))
	        <div class="uk-alert-success" uk-alert>
	            <a class="uk-alert-close" uk-close></a>
	            <strong>{{ Session::get('msj-exitoso') }}</strong>
	        </div>
	    @endif

        <div class="uk-card-small uk-card-default"> 
            <div class="uk-card-header uk-text-bold">
                <i class="fas fa-user uk-margin-small-right"></i> Perfil Administrativo
            </div>              
	        <div class="uk-card-body"> 
	            <form action="{{ route('admins.profiles.update') }}" method="POST" class="uk-grid-small" uk-grid> 
	                @csrf
	                <input type="hidden" name="id" value="{{ $perfil->id }}">
	                <div class="uk-width-1-2"> 
	                    <div class="uk-form-label">Nombre del Perfil</div>        
	                    <input class="uk-input" type="text" name="name" placeholder="Nombre del Perfil" value="{{ $perfil->name }}" required> 
	                </div> 

	                <div class="uk-width-1-2"> 
	                    <div class="uk-form-label">Estado del Perfil</div>    
	                    <select class="uk-select" name="status" required>
	                    	<option value="0" @if ($perfil->status == 0) selected @endif>Inactivo</option>
	                    	<option value="1" @if ($perfil->status == 1) selected @endif>Activo</option>
	                    </select>    
	                </div>                                     
	                
	                <div class="uk-width-1-2">
                        <hr>
                        Acceso a Usuarios (*):
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="users" value="0" @if ($perfil->users == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="users" value="1" @if ($perfil->users == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="users" value="2" @if ($perfil->users == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Perfiles (*):
                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="profiles" value="0" @if ($perfil->profiles == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="profiles" value="1" @if ($perfil->profiles == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="profiles" value="2" @if ($perfil->profiles == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Cursos (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="courses" value="0" @if ($perfil->courses == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="courses" value="1" @if ($perfil->courses == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="courses" value="2" @if ($perfil->courses == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Podcasts (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="podcasts" value="0" @if ($perfil->podcasts == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="podcasts" value="1" @if ($perfil->podcasts == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="podcasts" value="2" @if ($perfil->podcasts == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Regalos (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="gifts" value="0" @if ($perfil->gifts == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="gifts" value="1" @if ($perfil->gifts == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="gifts" value="2" @if ($perfil->gifts == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Eventos (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="events" value="0" @if ($perfil->events == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="events" value="1" @if ($perfil->events == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="events" value="2" @if ($perfil->events == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Etiquetas (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="tags" value="0" @if ($perfil->tags == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="tags" value="1" @if ($perfil->tags == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="tags" value="2" @if ($perfil->tags == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                     <div class="uk-width-1-2">
                        <hr>
                        Acceso a Pedidos (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="orders" value="0" @if ($perfil->orders == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="orders" value="1" @if ($perfil->orders == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="orders" value="2" @if ($perfil->orders == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Retiros (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="liquidations" value="0" @if ($perfil->liquidations == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="liquidations" value="1" @if ($perfil->liquidations == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="liquidations" value="2" @if ($perfil->liquidations == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Banco (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="banks" value="0" @if ($perfil->banks == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="banks" value="1" @if ($perfil->banks == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="banks" value="2" @if ($perfil->banks == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Cupones (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="coupons" value="0" @if ($perfil->coupons == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="coupons" value="1" @if ($perfil->coupons == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="coupons" value="2" @if ($perfil->coupons == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Tickets (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="tickets" value="0" @if ($perfil->tickets == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="tickets" value="1" @if ($perfil->tickets == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="tickets" value="2" @if ($perfil->tickets == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                     <div class="uk-width-1-2">
                        <hr>
                        Acceso a Reportes (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="reports" value="0" @if ($perfil->reports == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="reports" value="1" @if ($perfil->reports == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="reports" value="2" @if ($perfil->reports == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-2">
                        <hr>
                        Acceso a Finanzas (*):
                        <div class="uk-margin uk-child-width-auto uk-grid">
                            <label><input class="uk-radio" type="radio" name="finances" value="0" @if ($perfil->finances == 0) checked @endif> Sin Acceso</label>
                            <label><input class="uk-radio" type="radio" name="finances" value="1" @if ($perfil->finances == 1) checked @endif> Solo Lectura</label>
                            <label><input class="uk-radio" type="radio" name="finances" value="2" @if ($perfil->finances == 2) checked @endif> Acceso Total</label>
                        </div>
                    </div>
                    <div class="uk-width-1-1 uk-text-right">
						@if (Auth::user()->profile->profiles == 2)
							<input class="uk-button uk-button-grey uk-margin" type="submit" value="Guardar Cambios"> 
						@else
							<input class="uk-button uk-button-default uk-margin" class="button" value="Guardar Cambios" disabled>
						@endif 
					</div>
	            </form>                        
	        </div>              
        </div>          
    </div>
@endsection