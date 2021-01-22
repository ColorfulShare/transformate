<script>
    function buscar($version){
        if ($version == 'movil'){
            $("#search-form-movil").submit();
        }else{
            $("#search-form-pc").submit();
        }
    }

    function prueba(){
        if( $('#switch-label').prop('checked') ) {
            alert('Seleccionado');
        }else{
            alert("Sin Seleccionar");
        }
    }
</script>

<input type="hidden" id="tema" value="ligth">

{{-- Menú Móvil --}}
<nav class="header-background-ligth uk-hidden@m uk-animation-slide-right" uk-navbar id="navbar">
    {{-- Menú Izquierdo --}}
    <div class="uk-navbar-left navbar-left-movil">
        {{-- Icono de Barras Desplegable --}}
        <a href="javascript:;" uk-toggle="target: #offcanvas-menu-bars">
            <span class="font-h3"><i class="fa fa-bars header-icon"></i>  </span>
        </a>
        {{-- Menú Desplegable de Barras --}}
        <div id="offcanvas-menu-bars" uk-offcanvas="mode: push; overlay: true">
            <div class="uk-offcanvas-bar">
                <div class="menu-bars-content header-background-ligth" id="menu-bars-content">
                    <div class="offcanvas-header">
                        <button class="uk-offcanvas-close" type="button" uk-close style="color: white;"></button>
                        <a class="logo-img" href="{{ route('landing.index') }}" >
                            <img src="{{ asset('template/images/logocoloresinvert.png')}}">
                        </a>
                    </div>

                    <ul class="menu">
                        <li>
                            <a href="#" uk-toggle="target: #offcanvas-categories">
                                <span>
                                    <i class="fas fa-video"></i> T-Cursos
                                    <i class="fa fa-arrow-alt-circle-right bars-menu-icon-left"></i>
                                </span>
                            </a>
                            {{-- Menú de Categorías --}}
                            <div id="offcanvas-categories" uk-offcanvas="flip: true; overlay: true">
                                <div class="uk-offcanvas-bar">
                                    <div class="menu-categories-content header-background-ligth" id="menu-categories-content">
                                        <div class="offcanvas-header">
                                            <button class="uk-offcanvas-close" type="button" uk-close style="color: white;"></button>
                                            <a href="javascript:;" uk-toggle="target: #offcanvas-menu-bars">
                                                <span style="color: white;"><b><i class="fa fa-arrow-alt-circle-left bars-menu-icon-right"></i> Categorías</b></span>
                                            </a>
                                        </div>

                                        <ul class="menu">
                                            @foreach ($categorias as $cat)
                                                <li>
                                                    <a href="{{ route('landing.courses', [$cat->slug, $cat->id]) }}">
                                                        <span><i class="{{ $cat->icon }}"></i> {{ $cat->title }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @if (Auth::guest())
                            <li>
                                <a href="{{ route('landing.t-mentor') }}">
                                    <span><i class="fa fa-users"></i> T-Mentor</span>
                                </a>
                            </li>
                        @elseif (Auth::user()->role_id == 2)
                            <li>
                                <a href="{{ route('landing.t-mentor') }}">
                                    <span><i class="fa fa-users"></i> T-Mentor</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('landing.t-member') }}">
                                <span><i class="fa fa-user-tie"></i> T-Member</span>
                            </a>
                        </li>
                    </ul>

                    <div class="bars-menu-buttons-section">
                        @if (Auth::guest())
                            <div class="uk-width-1-1">
                                <a class="transf-button button-register" href="#modal-register" uk-toggle style="color: white;">Crear cuenta</a>
                            </div><br>
                        @endif
                        <div class="uk-width-1-1">
                            <a class="transf-button button-show-courses" href="{{ route('landing.courses') }}" style="color: white;">Ver T-Cursos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Fin Menú Desplegable de Barras --}}

        <a class="logo-principal" href="{{ route('landing.index') }}">
            <img src="{{ asset('template/images/logocoloresinvert.png')}}">
        </a>
    </div>

    {{--Menú Derecho --}}
    <div class="uk-navbar-right">
        <a id="search-icon">
            <span class="font-h3"><i class="fa fa-search header-icon"></i></span>
        </a>
        @if ( (!Auth::guest()) && (Auth::user()->role_id < 3) )
            <a href="{{ route('landing.shopping-cart.index') }}">
                <span class="font-h3"><i class="fa fa-shopping-cart header-icon"></i></span>
            </a>
        @endif
        @if (Auth::guest())
            <a class="header-link-login" href="#modal-login" uk-toggle>
                <span class="font-h2">Entrar</span>
            </a>
        @else
            <a href="javascript:;" uk-toggle="target: #offcanvas-menu-user">
                <span class="font-h3"><i class="fa fa-user header-icon"></i></span>
            </a>
            <div id="offcanvas-menu-user" uk-offcanvas="flip: true; overlay: true">
                <div class="uk-offcanvas-bar">
                    <div class="offcanvas-header">
                        <button class="uk-offcanvas-close" type="button" uk-close style="color: white;"></button>

                        <a class="logo-img" href="{{ route('landing.index') }}" >
                            <img src="{{ asset('template/images/logocoloresinvert.png')}}">
                        </a>
                    </div>

                    {{-- Menú Usuario Loggeado --}}
                    <ul class="menu">
                        @if (Auth::user()->role_id == 1)
                            @if (is_null(Auth::user()->membership_id))
                                <li>
                                    <a href="#modalCanjearMembresia" uk-toggle>
                                        <span><i class="fas fa-gift"></i> Canjea tu Membresía</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('students.membership.index') }}">
                                        <span><i class="fas fa-gem"></i> Membresía</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#modalCanjearCodigo" uk-toggle>
                                    <span><i class="fas fa-gift"></i> Canjea tu Código</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('notifications.index') }}">
                                    <span><i class="fas fa-bell"></i> Notificaciones</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('students.my-content') }}">
                                    <span><i class="fas fa-video"></i> Mis T-Courses</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('students.my-gifts') }}">
                                    <span><i class="fas fa-gift"></i> Mis T-Gifts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('students.purchases.index') }}">
                                    <span><i class="fas fa-shopping-cart"></i> Historial de Compra</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('students.tickets.index') }}">
                                    <span><i class="fas fa-ticket-alt"></i> Ayuda</span>
                                </a>
                            </li>
                        @elseif (Auth::user()->role_id == 2)
                            @if (is_null(Auth::user()->membership_id))
                                <li>
                                    <a href="#modalCanjearMembresia" uk-toggle>
                                        <span><i class="fas fa-gift"></i> Canjea tu Membresía</span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('instructors.membership.index') }}">
                                        <span><i class="fas fa-gem"></i> Membresía</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('notifications.index') }}">
                                    <span><i class="fas fa-bell"></i> Notificaciones</span>
                                </a>
                            </li>
                            <li>
                                <a uk-toggle="target: #modal-code">
                                    <span><i class="fas fa-users"></i> Mi Código de Mentor</span>
                                </a>
                            </li>
                            <li>
                                <a uk-toggle="target: #modal-code-partner">
                                    <span><i class="fas fa-users"></i> Mi Código T-Partner</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('instructors.courses.index') }}">
                                    <span><i class="fas fa-video"></i> T-Courses</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('instructors.certifications.index') }}">
                                    <span><i class="fas fa-landmark"></i> T-Mentorings</span>
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
                                    <i class="fas fa-comment-dollar"></i> Ganancias
                                </a><span></span>
                            </li>
                            <li>
                                <a href="{{ route('instructors.liquidations.index') }}">
                                    <span><i class="fas fa-arrow-alt-circle-right"></i> Cobros</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('instructors.tickets.index') }}">
                                    <span><i class="fas fa-ticket-alt"></i> Ayuda</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('instructors.referrals.index') }}">
                                    <span><i class="fas fa-users"></i> Referidos</span>
                            </li>
                        @elseif (Auth::user()->role_id == 3)
                            <li>
                                <a href="{{ route('landing.courses') }}">
                                    <span><i class="fas fa-video"></i> T-Courses</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing.podcasts') }}">
                                    <span><i class="fas fa-microphone"></i> T-Books</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing.certifications') }}">
                                    <span><i class="fas fa-landmark"></i> T-Mentorings</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a uk-toggle="target: #modal-code-partner">
                                    <span><i class="fas fa-users"></i> Mi Código T-Partner</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('partners.commissions.index') }}">
                                    <i class="fas fa-comment-dollar"></i> Mis Ganancias
                                </a><span></span>
                            </li>
                        @endif
                        <li>
                            <a @if (Auth::user()->role_id == 1) href="{{ route('students.profile.my-profile') }}" @elseif (Auth::user()->role_id == 2) href="{{ route('instructors.profile.my-profile') }}" @elseif (Auth::user()->role_id == 4) href="{{ route('partners.profile.my-profile') }}" @else href="{{ route('admins.profile.my-profile') }}" @endif>
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
                    </ul>
                </div>
            </div>
        @endif
    </div>
</nav>

{{-- Menú Búsqueda en Versión Móvil--}}
<nav class="header-background-ligth uk-hidden@m uk-animation-slide-left" uk-navbar style="display: none;" id="navbar-search">
    <div class="uk-navbar-left search-nav-left">
        <div class="uk-navbar-item">
            <form action="{{ route('landing.search') }}" method="GET" id="search-form-movil">
                <div class="uk-inline">
                    <a class="uk-form-icon" href="#" uk-icon="icon: search;" onclick="buscar('movil');"></a>
                    <input class="uk-input search-input" type="text" name="busqueda" placeholder="Buscar cursos..." style="width: 300px;">
                </div>
            </form>
        </div>
    </div>
    <div class="uk-navbar-right search-nav-right">
        <a id="close-search-icon">
            <i class="fa fa-times header-close-icon"></i>
        </a>
    </div>
</nav>

{{-- Menú PC --}}
<nav class="header-background-ligth uk-visible@m" uk-navbar id="navbar-large">
    <div class="uk-navbar-left header-large-left">
        @if (Auth::guest())
            <a href="{{ route('landing.index') }}" class="logo-pc">
                <img src="{{ asset('template/images/logocoloresinvert.png')}}">
            </a>
        @else
            @if (Auth::user()->role_id != 3)
                <a href="{{ route('landing.index') }}" class="logo-pc">
                    <img src="{{ asset('template/images/logocoloresinvert.png') }}">
                </a>
            @endif
        @endif

        <div class="menu-links-reduced">
            <a href="#" class="link-reduced">
                <span class="font-h3">...</span>
            </a>
            <div uk-dropdown="pos: bottom" style="padding: 0 0;">
                <div class="header-background-ligth" style="padding: 10px 15px;">
                    <ul class="menu">
                        @if ( (!Auth::guest()) && (Auth::user()->role_id == 1) )
                            <li>
                                <a href="{{ route('students.my-content') }}">
                                    <span><i class="fas fa-video"></i> Mis Cursos</span>
                                </a>
                            </li>
                        @endif
                        @if (Auth::guest())
                            <li>
                                <a href="{{ route('landing.t-mentor') }}">
                                    <span><i class="fa fa-users"></i> T-Mentor</span>
                                </a>
                            </li>
                        @elseif (Auth::user()->role_id == 2)
                            <li>
                                <a href="{{ route('landing.t-mentor') }}">
                                    <span><i class="fa fa-users"></i> T-Mentor</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('landing.t-member') }}">
                                <span><i class="fa fa-user-tie"></i> T-Member</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Menú de Opciones--}}
    <div class="uk-navbar-right header-large-right">

        <a href="#" class="header-link">
            <span class="font-h3">T-Cursos <i class="fa fa-chevron-down"></i></span>
        </a>
        <div uk-dropdown="pos: bottom" class="categories_dropdown">
            <div class="uk-grid uk-child-width-1-2 no-padding">
              <!-- Columna numero 1 menú "Educacion online"-->
                <div class="no-padding">
                    <ul class="categories_menu">
                        <span class="t-cursos">T-Cursos<hr class="hr"></span>
                        <li style="font-size:14px">
                            <a href="{{ route('landing.courses', ['t-master-class', 100]) }}">
                                <span><i class="fab fa-tumblr"></i> T-Master Class</span>
                            </a>
                        </li>
                        <li style="font-size:14px">
                            <a href="{{ route('landing.courses', ['t-books', 0]) }}">
                                <span><i class="fas fa-book"></i> T-Books</span>
                            </a>
                        </li>
                        @foreach ($categorias as $categoria)
                            <li style="font-size:14px">
                                <a href="{{ route('landing.courses', [$categoria->slug, $categoria->id]) }}">
                                    <span><i class="{{ $categoria->icon }}"></i> {{ $categoria->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                <a class="logo-img" href="{{ route('landing.t-member') }}">
                    <img src="{{ asset('template/images/promo.png')}}" alt="">
                </a>
                </div>
            </div>
        </div>

        <div class="menu-links" style="border-right: 4px solid #fff;margin-right: 15px;">
            <a href="{{ route('landing.events') }}" class="header-link">
                <span class="font-h3">T-Eventos</span>
            </a>
        </div>

        @if ( (!Auth::guest()) && (Auth::user()->role_id < 3) )
            <a href="{{ route('landing.shopping-cart.index') }}" class="header-icon">
                <span class="font-h3"><i class="fas fa-cart-plus"></i></span>
            </a>
        @endif
        @if (Auth::guest())
            <a class="login-link-header" href="#modal-login" uk-toggle>
                Entrar
            </a>
            <a class="transf-button register-button-header" href="#modal-register" uk-toggle>Crear Cuenta</a>
        @else
            <a href="javascript:;" class="header-icon">
                <span class="font-h3"> <i class="fas fa-bell"></i></span>
                @if ($cantNotificaciones > 0)
                    <span class="badge">{{ $cantNotificaciones }}</span>
                @endif
            </a>
            @include('layouts.includes.partials.notifications')

            <a href="javascript:;" class="header-icon">
                <span>
                    <img class="uk-border-circle" src="{{ asset('uploads/images/users/'.Auth::user()->avatar) }}" style="width: 35px; height: 35px;">
                </span>
            </a>
            <div uk-dropdown="pos: bottom" style="padding: 0 0;">
                <div class="header-background-ligth" style="padding: 10px 15px;">
                    <ul class="menu">
                        @include('layouts.includes.partials.userMenu')
                    </ul>
                </div>
            </div>
        @endif
    </div>
</nav>
