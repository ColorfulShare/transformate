@extends('layouts.student2')

@section('content')
    <div class="uk-container uk-margin-medium-bottom padding-top">
        @if (Session::has('msj-exitoso'))
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <strong>{{ Session::get('msj-exitoso') }}</strong>
            </div>
        @endif

        <div class="uk-flex-inline uk-flex-middle uk-margin-small-bottom"> 
            <i class="fas fa-bell icon-large uk-margin-right"></i> 
            <h4 class="uk-margin-remove"> Notificaciones </h4>                   
        </div>
        
        <div class="uk-background-default"> 
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-hover uk-table-divider">
                    <thead>
                        <tr>
                            <th class="uk-text-center uk-width-small">Fecha</th> 
                            <th class="uk-text-center uk-width-small">Notificación</th> 
                            <th class="uk-text-center uk-table-expand">Descripción</th>
                            <th class="uk-text-center uk-width-small">Estado</th> 
                            <th class="uk-text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notificaciones as $notificacion)
                            <tr @if ($notificacion->status == 0 ) style="background-color: #F0FBFF;" @endif>                                   
                                <td class="uk-text-center">{{ date('d-m-Y H:i A', strtotime("$notificacion->created_at -5 Hours")) }}</td> 
                                <td class="uk-text-center"><i class="{{ $notificacion->icon }}"></i> {{ $notificacion->title }}</td> 
                                <td class="uk-text-center">{!! $notificacion->description !!}</td> 
                                <td class="uk-text-center">
                                    @if ($notificacion->status == 0)
                                        <label class="uk-label uk-label-danger">Sin Leer</label>
                                    @else
                                        <label class="uk-label uk-label-success">Leida</label>
                                    @endif
                                </td>
                                <td class="uk-flex-inline uk-text-center"> 
                                    <a href="{{ url($notificacion->route) }}" class="uk-icon-button uk-button-primary btn-icon" uk-icon="icon: search;" uk-tooltip="Ver Detalles"></a>            
                                </td>                                 
                            </tr>   
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection