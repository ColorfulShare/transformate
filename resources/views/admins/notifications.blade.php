@extends('layouts.admin')

@section('content')
    <div class="admin-content-inner">
        <div class="uk-container uk-margin-medium-top">
            @if (Session::has('msj-exitoso'))
                <div class="uk-alert-success" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <strong>{{ Session::get('msj-exitoso') }}</strong>
                </div>
            @endif

            <div uk-grid>
                <div class="uk-width-4-4@m"> 
                    <div class="uk-card-default">
                        <div class="uk-card-header">
                            <i class="fa fa-shopping-cart i-space-right"></i>
                            {{ $cantNotificaciones }} Notificaciones.
                        </div>  
                        <div class="uk-card-body">
                            @foreach ($notificaciones as $notificacion)
                                <div @if ($notificacion->status == 0 ) style="background-color: #E6E6E6;" @endif class="uk-padding-small uk-card-hover border-radius-6 nobi" uk-grid>
                                    <div class="uk-width-Expand"> 
                                    	<span style="font-size: 25px;"><i class="{{ $notificacion->icon }}"></i></span>
                                        <h4 class="uk-margin-small-bottom"><a @if ($notificacion->status == 1) href="{{ url($notificacion->route) }}"  @else href="{{ route('notifications.update', $notificacion->id) }}" @endif class="uk-link-reset uk-text-bold">{{ $notificacion->title }}</a></h4>
                                        <p class="uk-text-small uk-text0-muted dod">{!! $notificacion->description !!}</p>
                                    </div>
                                    <div class="uk-flex-middle uk-visible@m">
                                    	<p class="lko" style="font-size: 12px;">{{ date("d-m-Y H:i A", strtotime("$notificacion->created_at -5 Hours") )}}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
@endsection