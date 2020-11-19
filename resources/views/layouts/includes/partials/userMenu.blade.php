@if ( (Auth::user()->role_id < 3) && (is_null(Auth::user()->membership_id)) )
    <li>
        <a href="#modalCanjearMembresia" uk-toggle>
            <span><i class="fas fa-gift"></i> Canjea tu Membresía </span>
        </a>
    </li>
@endif
@if (Auth::user()->role_id == 1)
    <li>
        <a href="#modalCanjearCodigo" uk-toggle>
            <span><i class="fas fa-gift"></i> Canjea tu Código</span>
        </a>
    </li>
@endif
<li>
    <a href="{{ route('notifications.index') }}">
        <span><i class="fas fa-bell"></i> Notificaciones</span>
    </a>
</li>
@if (Auth::user()->role_id == 1)
    @if(!is_null(Auth::user()->membership_id))
        <li>
            <a href="{{ route('students.membership.index') }}">
                <span><i class="fas fa-gem"></i> Membresía </span>
            </a>
        </li>
    @endif
    <li>
        <a href="{{ route('students.my-content') }}">
            <span><i class="fas fa-video"></i> Mis Cursos </span>
        </a>
    </li>
    <li>
        <a href="{{ route('students.my-gifts') }}">
            <span><i class="fas fa-gift"></i> Mis T-Gifts</span>
        </a>
    </li>
    {{--  <li>
        <a href="{{ route('students.marketplace.my-products') }}">
            <i class="fas fa-store"></i>
            <span>
                Mis Productos
            </span>
        </a>
    </li>--}}
    <li>
        <a href="{{ route('students.purchases.index') }}">
            <span><i class="fas fa-shopping-cart"></i> Historial de Compra </span>
        </a>
    </li>
    <li>
        <a href="{{ route('students.tickets.index') }}">
            <span><i class="fas fa-ticket-alt"></i> Ayuda</span>
        </a>
    </li>
@elseif (Auth::user()->role_id == 2)
    <li>
        <a uk-toggle="target: #modal-code">
            <span><i class="fas fa-users"></i> Mi Código de Mentor</span>
        </a>
    </li>
    <li>
        <a uk-toggle="target: #modal-code-partner">
            <span><i class="fas fa-users"></i> Mi Código T-partner</span>
        </a>
    </li>
    @if(!is_null(Auth::user()->membership_id))
        <li>
            <a href="{{ route('instructors.membership.index') }}">
                <span><i class="fas fa-gem"></i> Membresía </span>
            </a>
        </li>
    @endif
    <li>
        <a href="{{ route('instructors.courses.index') }}">
            <span><i class="fas fa-video"></i> T-Courses</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.certifications.index') }}">
            <span><i class="fas fa-landmark"></i> T-Mentoring</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.podcasts.index') }}">
            <span><i class="fas fa-microphone"></i> T-Books</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.discussions.index') }}">
            <span><i class="fas fa-comment"></i> Foros</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.commissions.index') }}">
            <span><i class="fas fa-comment-dollar"></i> Ganancias</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.liquidations.index') }}">
            <span><i class="fas fa-arrow-alt-circle-right"></i> Cobros</span>
        </a>
    </li>
    {{--  <li>
        <a href="{{ route('instructors.products.index') }}">
            <i class="fas fa-store"></i>
            <span>
                Marketplace
            </span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.products.my-purchased-products') }}">
            <i class="fas fa-shopping-bag"></i>
            <span>
                Mis Productos
            </span>
        </a>
    </li>--}}
    <li>
        <a href="{{ route('instructors.tickets.index') }}">
            <span><i class="fas fa-ticket-alt"></i> Tickets</span>
        </a>
    </li>
    <li>
        <a href="{{ route('instructors.referrals.index') }}">
            <span><i class="fas fa-users"></i> Referidos</span>
        </a>
    </li>
@elseif (Auth::user()->role_id == 4)
    <li>
        <a uk-toggle="target: #modal-code-partner">
            <span><i class="fas fa-users"></i> Mi Código T-partner</span>
        </a>
    </li>
    <li>
        <a href="{{ route('partners.commissions.index') }}">
            <span><i class="fas fa-comment-dollar"></i> Mis Ganancias</span>
        </a>
    </li>
@endif
<li>
    <a @if (Auth::user()->role_id == 1) href="{{ route('students.profile.my-profile') }}" @elseif (Auth::user()->role_id == 2) href="{{ route('instructors.profile.my-profile') }}"  @elseif (Auth::user()->role_id == 4) href="{{ route('partners.profile.my-profile') }}" @else href="{{ route('admins.profile.my-profile') }}" @endif>
        <span><i class="fas fa-user"></i> Mi Cuenta</span>
    </a>
</li>
<li>
    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <span><i class="fas fa-sign-out-alt"></i> {{ trans('Cerrar Sesión') }}</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</li>

<div id="modalCanjearMembresia" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Canjear Membresía de Regalo</h4>
        </div>
        <form action="{{ route('membership.redeem-gift') }}" method="POST">
            @csrf
            <div class="uk-modal-body">
                <div class="uk-margin">
                    <label class="uk-form-label" for="codigo"><b>Ingrese el código de regalo: </b></label>
                    <input class="uk-input uk-form-success" type="text" name="code" required>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                <button class="uk-button uk-button-primary" type="submit">Aplicar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalCanjearCodigo" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-header">
            <h4>Canjear Código de Regalo</h4>
        </div>
        <form action="{{ route('students.redeem-gift-code') }}" method="POST">
            @csrf
            <div class="uk-modal-body">
                <div class="uk-margin">
                    <label class="uk-form-label" for="codigo"><b>Ingrese el código de regalo: </b></label>
                    <input class="uk-input uk-form-success" type="text" name="code" required>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Cancelar</button>
                <button class="uk-button uk-button-primary" type="submit">Aplicar</button>
            </div>
        </form>
    </div>
</div>