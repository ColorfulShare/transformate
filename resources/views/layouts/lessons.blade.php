<html lang="en"> 
    <head> 
       <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('template/images/favicon_transformate_01.png') }}">
        <meta name="description" content="">
        <title>Transfórmate | Cursos de Transformación </title>          
        <!-- Favicon -->
        <link href="{{ asset('template/images/favicon_transformate_01.png') }}" rel="icon" type="image/png">
        <!-- Your stylesheet-->
        <link rel="stylesheet" href="{{  asset('template/css/uikit.css') }}"> 
        <link rel="stylesheet" href="{{  asset('template/css/main.css') }}"> 
        <!-- font awesome -->
        <link rel="stylesheet" href="{{  asset('template/css/fontawesome.css') }}">

        @stack('styles')
        <!--  javascript -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{  asset('template/js/simplebar.js') }}"></script>         
        <script src="{{  asset('template/js/uikit.js') }}"></script>

        <!-- Facebook Pixel Code -->
        <script>
          !function(f,b,e,v,n,t,s)
          {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
          'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '160074978728892');
          fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
          src="https://www.facebook.com/tr?id=160074978728892&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->

        @stack('scripts')
    </head>

    <body style="overflow: hidden;">         
        <div class="tm-course-lesson"> 
            <!-- mobile-sidebar  -->
            <i class="fas fa-video icon-large tm-side-right-mobile-icon uk-hidden@m" uk-toggle="target: #filters"></i>
            <!-- mobile-sidebar  -->
            <i class="fas fa-video icon-large tm-side-right-mobile-icon uk-hidden@m" uk-toggle="target: #filters"></i>         

            <!-- Your app page -->             
            <div class="uk-grid-collapse" id="course-fliud" uk-grid>
                <!-- PreLoader -->                 
                <div id="spinneroverlay"> 
                    <div class="spinner"></div>                     
                </div>            
				
				@yield('content')
			</div>
        </div>
                
        <!-- app end -->         
        <!--  Night mood -->         
        <script>
            function cambiarTema(){
            if( $('#theme_check').prop('checked') ) {
            //if (document.getElementById("tema").value == 'ligth'){
                /*document.getElementById('icon-night').style.display = 'none';
                document.getElementById('icon-day').style.display = 'block';*/
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
                    console.log($(this).attr('id'));
                    $("#"+$(this).attr('id')).removeClass("color-ligth");
                    $("#"+$(this).attr('id')).addClass("color-dark");
                });
                $(".color-ligth2").each(function(index) {
                    console.log($(this).attr('id'));
                    $("#"+$(this).attr('id')).removeClass("color-ligth2");
                    $("#"+$(this).attr('id')).addClass("color-dark2");
                });
                $(".background-ligth2").each(function(index) {
                    $("#"+$(this).attr('id')).removeClass("background-ligth2");
                    $("#"+$(this).attr('id')).addClass("background-dark2");
                });
            }else{
                /*document.getElementById('icon-day').style.display = 'none';
                document.getElementById('icon-night').style.display = 'block';*/
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
            }
         }
        (function (window, document, undefined) {
          'use strict';
          if (!('localStorage' in window)) return;
          var nightMode = localStorage.getItem('gmtNightMode');
          if (nightMode) {
              document.documentElement.className += ' night-mode';
          }
        })(window, document);


        (function (window, document, undefined) {

          'use strict';

          // Feature test
          if (!('localStorage' in window)) return;

          // Get our newly insert toggle
          var nightMode = document.querySelector('#night-mode');
          if (!nightMode) return;

          // When clicked, toggle night mode on or off
          nightMode.addEventListener('click', function (event) {
              event.preventDefault();
              document.documentElement.classList.toggle('night-mode');
              if ( document.documentElement.classList.contains('night-mode') ) {
                  localStorage.setItem('gmtNightMode', true);
                  return;
              }
              localStorage.removeItem('gmtNightMode');
          }, false);

        })(window, document);

        // Preloader
        var spinneroverlay = document.getElementById("spinneroverlay");
        window.addEventListener('load', function(){
            spinneroverlay.style.display = 'none';
        });
        

        </script>         
    </body>     
</html>
