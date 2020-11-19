<div uk-dropdown="pos: bottom" class="notifications_menu">
    <div class="notifications-header">Notificaciones ({{ $cantNotificaciones }})</div>
    <div class="notifications-content">
        <div class="notifications-scroll">
            @foreach ($notificaciones as $key => $notificacion)
                <a @if ($notificacion->status == 1) href="{{ url($notificacion->route) }}" @else href="{{ route('notifications.update', $notificacion->id) }}" @endif>
                    <div class="uk-grid notification-div @if ($notificacion->status == 0)pending-notification @endif">
                        <div class="uk-width-auto div-icon">
                            <i class="{{ $notificacion->icon }} notification-icon"></i>
                        </div>
                        <div class="uk-width-expand uk-text-left div-description">
                            <div class="notification-title">{{ $notificacion->title }}</div>
                            <div class="uk-text-truncate notification-description">{!! $notificacion->description !!}</div>
                            <div class="notification-date">{{ date('d-m-Y H:i A', strtotime($notificacion->date)) }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="uk-text-center notifications-all"><a href="{{ route('notifications.index') }}">Ver Todas</a></div>
</div>