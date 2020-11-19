<html lang="en"> 
    <head> 
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}">
        <meta name="description" content="">
        <title>Transfórmate | Cursos de Transformación </title>       
        <!-- Favicon -->
        <link href="{{ asset('template/images/favicon.png') }}" rel="icon" type="image/png">
        
        <!-- Your stylesheet-->
        <link rel="stylesheet" href="{{ asset('template/css/uikit.css') }}"> 
        <link rel="stylesheet" href="{{ asset('template/css/main.css') }}"> 

        {{-- me permite traer los css que se colocan en las paginas aqui en el encabezado --}}
        @stack('css')


        <!-- font awesome -->                  
        <link rel="stylesheet" href="{{ asset('template/css/fontawesome.css') }}">
        
        <!--  javascript -->
        <script src="{{ asset('template/js/simplebar.js') }}"></script>         
        <script src="{{ asset('template/js/uikit.js') }}"></script>  

        {{-- me permite traer los script que se colocan en las paginas aqui en el encabezado --}}
        @stack('script')
    </head>         
    <body> 
        <div uk-height-viewport="offset-top: true; offset-bottom: true" class="uk-flex uk-flex-middle">
            <div class="uk-width-2-3@m uk-width-1-2@s uk-margin-auto  border-radius-6 ">
                <div class="uk-child-width-1-2@m uk-background-grey uk-grid-collapse" uk-grid>
                    <div class="uk-text-middle uk-margin-auto-vertical uk-text-center uk-padding-small uk-animation-scale-up">
                        <p> <i class="fa fa-graduation-cap uk-text-white" style="font-size:60px"></i> </p>
                        <h1 class="uk-text-white uk-margin-small"> Transformate </h1>
                        <h5 class="uk-margin-small uk-text-muted uk-text-bold uk-text-nowrap"> El lugar donde puedes aprender lo que sea. </h5>
                    </div>

                    <div>
                        <div class="uk-card-default uk-padding uk-card-small">
                            @yield('content')
                        </div>                         
                    </div>
                </div>
            </div>
        </div>

        <script>
		// Listen for clicks in the document
		document.addEventListener('click', function (event) {

			// Check if a password selector was clicked
			var selector = event.target.getAttribute('data-show-pw');
			if (!selector) return;

			// Get the passwords
			var passwords = document.querySelectorAll(selector);

			// Toggle visibility
			Array.from(passwords).forEach(function (password) {
				if (event.target.checked === true) {
					password.type = 'text';
				} else {
					password.type = 'password';
				}
			});
		}, false);
	    </script>
    </body>     
</html>
