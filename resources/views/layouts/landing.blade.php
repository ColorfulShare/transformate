<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('template/images/favicon_transformate_01.png') }}">
        <meta name="description" content="">
        <title>Transfórmate | Cursos de Transformación </title>
        <!-- Favicon -->
        <link href="{{ asset('template/images/favicon_transformate_01.png') }}" rel="icon" type="image/png">
        <!-- Your stylesheet-->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('css/footer_uikit.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modales_auth.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modal_trailer.css') }}">
        <link rel="stylesheet" href="{{  asset('template/css/uikit.css') }}">
        <link rel="stylesheet" href="{{ asset('template/css/fontawesome.css') }}">


        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css'>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="{{  asset('template/js/uikit.js') }}"></script>
        <script src="{{ asset('template/icon/js/uikit-icons.min.js') }}"></script>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NBKTXRC');</script>
        <!-- End Google Tag Manager -->

        <script>
              //Cuando cargue la p¨¢gina completamente
              $(document).ready(function(){
                //Creamos un evento click para el enlace
                $(".ancla").click(function(evento){
                  //Anulamos la funcionalidad por defecto del evento
                  evento.preventDefault();
                  //Creamos el string del enlace ancla
                  var codigo = "#" + $(this).data("ancla");
                  //Funcionalidad de scroll lento para el enlace ancla en 3 segundos
                  $("html,body").animate({scrollTop: $(codigo).offset().top}, 1000);
                });
              });
        </script>

        <!-- Facebook Pixel Code -->
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '1831887370445429'); 
            fbq('track', 'PageView');
            fbq('track', 'CompleteRegistration');
            fbq('track', 'Contact');
            fbq('track', 'Search');
            
            @yield('fb-events')
        </script>

        <noscript>
            <img height="1" width="1" src="https://www.facebook.com/tr?id=1831887370445429&ev=PageView &noscript=1"/>
        </noscript>
        <!-- End Facebook Pixel Code -->

        {!! htmlScriptTagJsApi() !!}

        @stack('scripts')
        @stack('styles')
    </head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NBKTXRC"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    {{-- modales --}}
    @include('auth.login')
    @include('auth.register')
    @include('auth.registerInstructor')
    @include('auth.recoverModal')
    {{-- header y menu --}}
    @include('layouts.includes.header')

    @if (!Auth::guest())
        <div class="modal" id="modal-code" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Código de Mentor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ Auth::user()->afiliate_code }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-code-partner" uk-modal>
            <div class="uk-modal-dialog uk-modal-body uk-margin-auto-vertical">
                <button class="uk-modal-close-default" type="button" uk-close></button>
                <h2 class="uk-modal-title">Código T-Partner</h2>
                <p>{{ Auth::user()->partner_code }}</p>
            </div>
        </div>
    @endif
        {{-- contenido --}}
    @yield('content')
        {{-- fin contenido --}}
    @include('layouts.includes.footer')

    {{-- Modal Vista Previa --}}
    <div id="modal-preview" uk-modal>
        <div class="uk-modal-dialog" id="preview-video"></div>
    </div>

    {{-- Modal Suscripción a Newsletter --}}
    <div id="modal-newsletter" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-body" style="padding: 0px 0px;">
                <div class=" uk-child-width-1-1" uk-grid>
                    <div style="padding: 0 0;">
                        <div class="uk-width-1-1 uk-text-center slogan-register">
                            <img src="{{ asset('template/images/logo3.png') }}" style="width: 60%;"><br>
                            <div style="padding-top: 15px; padding-bottom: 5px; font-size: 20px; color: gray; font-weight: bold;"> Sé el primero en enterarte sobre las últimas promociones y novedades sobre nosotros</div>
                        </div>
                        <div class="uk-width-1-1 email-login">
                            <form action="{{ route('landing.new-subscriber') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input class="uk-input" type="text" name="name" placeholder="Tu Nombre" required>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                        <input class="uk-input" type="email" name="email" placeholder="Tu Correo Electrónico" required>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: phone"></span>
                                        <input class="uk-input" type="text" name="phone" placeholder="Tu Teléfono" >
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon"><i class="fas fa-globe-americas"></i></span>
                                        <input class="uk-input" type="text" name="country" placeholder="Tu País">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon"><i class="fas fa-sort-numeric-up"></i></span>
                                        <input class="uk-input" type="number" name="age" placeholder="Tu Edad">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon"><i class="fas fa-user-tie"></i></span>
                                        <input class="uk-input" type="text" name="profession" placeholder="Tu Profesión">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1 uk-text-center">
                                        {!! htmlFormSnippet() !!}
                                    </div>
                                </div>
                                <div class="uk-margin uk-text-center">
                                    <input type="submit" class="login-button" value="Suscribirme">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Contáctanos --}}
    <div id="modal-contact-us" uk-modal>
        <div class="uk-modal-dialog uk-margin-auto-vertical">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-body" style="padding: 0px 0px;">
                <div class=" uk-child-width-1-1" uk-grid>
                    <div style="padding: 0 0;">
                        <div class="uk-width-1-1 uk-text-center slogan-register">
                            <img src="{{ asset('template/images/logo3.png') }}" style="width: 60%;"><br>
                            <div style="padding-top: 15px; padding-bottom: 5px; font-size: 20px; color: gray; font-weight: bold;"> Déjanos tu comentario, sugerencia, consulta por aquí.</div>
                        </div>
                        <div class="uk-width-1-1 email-login">
                            <form method="POST" action="{{ route('landing.contact-us') }}">
                                {{ csrf_field() }}
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                                        <input class="uk-input" type="text" name="name" placeholder="Tu Nombre" required>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                        <input class="uk-input" type="email" name="email" placeholder="Tu Correo Electrónico" required>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <span class="uk-form-icon" uk-icon="icon: comment"></span>
                                        <input class="uk-input" type="text" name="subject" placeholder="Asunto" required>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1">
                                        <textarea class="uk-textarea" name="message" rows="5" placeholder="Déjanos tu mensaje aquí..." required></textarea>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-inline uk-width-1-1 uk-text-center">
                                        {!! htmlFormSnippet() !!}
                                    </div>
                                </div>
                                <div class="uk-margin uk-text-center">
                                    <input type="submit" class="login-button" value="Enviar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.js'></script>

    @stack('scripts')
    <script>

        $('.carousel').carousel({
         interval: 5000
        });
        $(function(){
            $("[data-toggle=popover]").popover();
        });

        $('.video-link').magnificPopup({
            type: 'inline',
            callbacks: {
                open: function() {
                    this.content.children('video')[0].play();
                },
                close: function() {
                    this.content.children('video')[0].pause();
                }
            }
        });

        $(function() {
            $('#search-icon').click(function(event) {
                $("#navbar").hide();
                $("#navbar-search").show();
               //
            });
            $('#close-search-icon').click(function(event) {
                $("#navbar-search").hide();
                $("#navbar").show();
            });
        });

        function cambiarTema(){
            if( $('#theme_check').prop('checked') ) {
                document.getElementById("tema").value = 'dark';
                $(".header-background-ligth").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("header-background-ligth");
                    $("#"+$(this).attr('id')).addClass("header-background-dark");
                });
                $(".background-ligth").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("background-ligth");
                    $("#"+$(this).attr('id')).addClass("background-dark");
                });
                $(".color-ligth").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("color-ligth");
                    $("#"+$(this).attr('id')).addClass("color-dark");
                });
                $(".color-ligth2").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("color-ligth2");
                    $("#"+$(this).attr('id')).addClass("color-dark2");
                });
                $(".background-ligth2").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("background-ligth2");
                    $("#"+$(this).attr('id')).addClass("background-dark2");
                });
                $(".card-background-ligth").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("card-background-ligth");
                    $("#"+$(this).attr('id')).addClass("card-background-dark");
                });
                $(".line-ligth").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("line-ligth");
                    $("#"+$(this).attr('id')).addClass("line-dark");
                });
                $("#presentation").css("color", "white");
                $("#temary").css("color", "white");
                $("#ratings").css("color", "white");
                $("#resources").css("color", "white");
                $("#objectives-title").css("color", "white");
                $("#destination-title").css("color", "white");
                $("#material-content-title").css("color", "white");
                $("#requirements-title").css("color", "white");
                $("#inspired-title").css("color", "white");
                $("#importance-title").css("color", "white");
                $("#prologue-title").css("color", "white");
                $("#impact-title").css("color", "white");
                $("#presentation-movil").css("color", "white");
                $("#temary-movil").css("color", "white");
                $("#ratings-movil").css("color", "white");
                $("#resources-movil").css("color", "white");
                $("#objectives-title-movil").css("color", "white");
                $("#destination-title-movil").css("color", "white");
                $("#material-content-title-movil").css("color", "white");
                $("#requirements-title-movil").css("color", "white");
                $("#inspired-title-movil").css("color", "white");
                $("#importance-title-movil").css("color", "white");
                $("#prologue-title-movil").css("color", "white");
                $("#impact-title-movil").css("color", "white");
                $("#module-title-1").css("color", "white");
                $("#module-title-2").css("color", "white");
                $("#module-title-3").css("color", "white");
                $("#module-title-4").css("color", "white");
                $("#module-title-5").css("color", "white");
                $("#module-title-6").css("color", "white");
                $("#module-title-movil-1").css("color", "white");
                $("#module-title-movil-2").css("color", "white");
                $("#module-title-movil-3").css("color", "white");
                $("#module-title-movil-4").css("color", "white");
                $("#module-title-movil-5").css("color", "white");
                $("#module-title-movil-6").css("color", "white");
            }else{
                document.getElementById("tema").value = 'ligth';
                $(".header-background-dark").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("header-background-dark");
                    $("#"+$(this).attr('id')).addClass("header-background-ligth");
                });
                $(".background-dark").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("background-dark");
                    $("#"+$(this).attr('id')).addClass("background-ligth");
                });
                $(".color-dark").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("color-dark");
                    $("#"+$(this).attr('id')).addClass("color-ligth");
                });
                 $(".color-dark2").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("color-dark2");
                    $("#"+$(this).attr('id')).addClass("color-ligth2");
                });
                $(".background-dark2").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("background-dark2");
                    $("#"+$(this).attr('id')).addClass("background-ligth2");
                });
                $(".card-background-dark").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("card-background-dark");
                    $("#"+$(this).attr('id')).addClass("card-background-ligth");
                });
                $(".line-dark").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("line-dark");
                    $("#"+$(this).attr('id')).addClass("line-ligth");
                });
                $("#presentation").css("color", "black");
                $("#temary").css("color", "black");
                $("#ratings").css("color", "black");
                $("#resources").css("color", "black");
                $("#objectives-title").css("color", "black");
                $("#destination-title").css("color", "black");
                $("#material-content-title").css("color", "black");
                $("#requirements-title").css("color", "black");
                $("#inspired-title").css("color", "black");
                $("#importance-title").css("color", "black");
                $("#prologue-title").css("color", "black");
                $("#impact-title").css("color", "black");
                $("#module-title-1").css("color", "black");
                $("#module-title-2").css("color", "black");
                $("#module-title-3").css("color", "black");
                $("#module-title-4").css("color", "black");
                $("#module-title-5").css("color", "black");
                $("#module-title-6").css("color", "black");
                $("#presentation-movil").css("color", "black");
                $("#temary-movil").css("color", "black");
                $("#ratings-movil").css("color", "black");
                $("#resources-movil").css("color", "black");
                $("#objectives-title-movil").css("color", "black");
                $("#destination-title-movil").css("color", "black");
                $("#material-content-title-movil").css("color", "black");
                $("#requirements-title-movil").css("color", "black");
                $("#inspired-title-movil").css("color", "black");
                $("#importance-title-movil").css("color", "black");
                $("#prologue-title-movil").css("color", "black");
                $("#impact-title-movil").css("color", "black");
                $("#module-title-movil-1").css("color", "black");
                $("#module-title-movil-2").css("color", "black");
                $("#module-title-movil-3").css("color", "black");
                $("#module-title-movil-4").css("color", "black");
                $("#module-title-movil-5").css("color", "black");
                $("#module-title-movil-6").css("color", "black");
            }
        }

        // ------------------------------------------------------- //
        // Multi Level dropdowns
        // ------------------------------------------------------ //
        $(function() {
            $("ul.dropdown-menu [data-toggle='dropdown']").on("click", function(event) {
                event.preventDefault();
                event.stopPropagation();

                $(this).siblings().toggleClass("show");

                if (!$(this).next().hasClass('show')) {
                    $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                }

                $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                    $('.dropdown-submenu .show').removeClass("show");
                });
            });
        });

         $(function(){
            $('body').on('click','.view-preview',function(){
               let path = $(this).data('viewpreview')
               $.ajax({
                  type:"GET",
                  url:path,
                  success:function(ans){
                    $("#preview-video").html(ans);
                    if (document.getElementById("tema").value == 'dark'){
                        $(".header-ligth-trailer").each(function(index) {
                            $("#"+$(this).attr('id')).removeClass("header-ligth-trailer");
                            $("#"+$(this).attr('id')).addClass("header-dark-trailer");
                        });
                        $(".color-ligth-trailer").each(function(index) {
                            $("#"+$(this).attr('id')).removeClass("color-ligth-trailer");
                            $("#"+$(this).attr('id')).addClass("color-dark-trailer");
                        });
                        $(".background-ligth-trailer").each(function(index) {
                            $("#"+$(this).attr('id')).removeClass("background-ligth-trailer");
                            $("#"+$(this).attr('id')).addClass("background-dark-trailer");
                        });
                    }
                  }
               });
            });
        });
    </script>
</body>
</html>

