<!DOCTYPE html>
   <html>
      <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
      <link rel="shortcut icon" href="{{ asset('template/images/favicon_transformate_01.png') }}">
        <!-- Favicon -->
      <link href="{{ asset('template/images/favicon_transformate_01.png') }}" rel="icon" type="image/png">

      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=UA-156492484-1"></script>
      <script>
           window.dataLayer = window.dataLayer || [];
           function gtag(){dataLayer.push(arguments);}
           gtag('js', new Date());

           gtag('config', 'UA-156492484-1');
      </script>
 
    
    
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <meta name="csrf-token" content="{{ csrf_token() }}">
       <link rel="shortcut icon" href="{{ asset('template/images/favicon_transformate-01.svg') }}">
       <meta name="description" content="">
       <title> Transfórmate </title>         
       <!-- Favicon -->
       <link href="{{ asset('template/images/favicon_transformate-01.svg') }}" rel="icon" type="image/svg">
       <!-- Your stylesheet-->
       <link rel="stylesheet" href="{{ asset('css/app.css') }}">
       <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
       <link rel="stylesheet" href="{{ asset('template/css/fontawesome.css') }}">
       <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

       <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css'>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
       <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
       <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    
</head> 
<style>
    

/*=========================
  Icons
 ================= */

/* footer social icons */
ul.social-network {
	list-style: none;
	display: inline;
	margin-left:0 !important;
	padding: 0;
}
ul.social-network li {
	display: inline;
	margin: 0 5px;
}


/* footer social icons */

.social-network a.icoFacebook:hover {
	background-color:#3B5998;
}

.social-network a.icoYoutube:hover {
	background-color:red;
}
.social-network a.icoInstagram:hover {
	background-color:#854192;
}

.social-network a.icoFacebook:hover i, .social-network a.icoGoogle:hover i, .social-network a.icoVimeo:hover i{
	color:#fff;
}
a.socialIcon:hover, .socialHoverClass {
	color:#44BCDD;
}

.social-circle li a {
	display:inline-block;
	position:relative;
	margin:0 auto 0 auto;
	-moz-border-radius:50%;
	-webkit-border-radius:50%;
	border-radius:50%;
	text-align:center;
	width: 50px;
	height: 50px;
	font-size:20px;
}
.social-circle li i {
	margin:0;
	line-height:50px;
	text-align: center;
}

.social-circle li a:hover i, .triggeredHover {
	-moz-transform: rotate(360deg);
	-webkit-transform: rotate(360deg);
	-ms--transform: rotate(360deg);
	transform: rotate(360deg);
	-webkit-transition: all 0.2s;
	-moz-transition: all 0.2s;
	-o-transition: all 0.2s;
	-ms-transition: all 0.2s;
	transition: all 0.2s;
}
.social-circle i {
	color: #fff;
	-webkit-transition: all 0.8s;
	-moz-transition: all 0.8s;
	-o-transition: all 0.8s;
	-ms-transition: all 0.8s;
	transition: all 0.8s;
}

a {
 background-color: #D3D3D3;   
}

.timer {
  padding: 20px;
  font-family: Arial, Helvetica, sans-serif;
  color: #fff;
  display: inline-block;
  font-weight: 600;
  text-align: center;
  font-size: 30px;
  background: #114d89;
}

.timer .dias {
  padding: 10px;
  border-radius: 3px;

  display: inline-block;
  font-family: Gilroy, Arial, sans-serif
  font-size: 30px;
  font-weight: 700;

}

.timer .horas {
  padding: 10px;
  border-radius: 3px;
  
  display: inline-block;
  font-family: Gilroy, Arial, sans-serif
  font-size: 30px;
  font-weight: 700;
 
}
.timer .minutos {
  padding: 10px;
  border-radius: 3px;

  display: inline-block;
  font-family: Gilroy, Arial, sans-serif
  font-size: 30px;
  font-weight: 700;
 
}
.timer .segundos {
  padding: 10px;
  border-radius: 3px;

  display: inline-block;
  font-family: Gilroy, Arial, sans-serif
  font-size: 30px;
  font-weight: 700;
  
}

.timer .smalltext {
  color: white;
  font-size: 12px;
  font-family: 'Montserrat', sans-serif;
  font-weight: 400;
  display: block;
  padding: 0;
  width: auto;
}
.timer #time-up {
  margin: 8px 0 0;
  text-align: left;
  font-size: 20px;
  font-style: normal;
  color: #000000;
  font-weight: 700;
  letter-spacing: 1px;
}
.full-size-head{
    background-color: #32bacb;
    height: auto;
}
.lanzamiento{
   
 font-size: 25px;
 color: white;
 font-family: 'Montserrat', sans-serif;
 margin-top: auto;
 margin-bottom: auto;
 font-weight: 600px;
 width:100%;
}
.ancho{
    padding: 9px;
    height: 100%;
}

</style>

<script>
    var deadline = new Date("march 7, 2020 14:00:00").getTime();             
var x = setInterval(function() {
   var currentTime = new Date().getTime();                
   var t = deadline - currentTime; 
   var days = Math.floor(t / (1000 * 60 * 60 * 24)); 
   var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60)); 
   var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60)); 
   var seconds = Math.floor((t % (1000 * 60)) / 1000); 
   document.getElementById("day").innerHTML =days ; 
   document.getElementById("hour").innerHTML =hours; 
   document.getElementById("minute").innerHTML = minutes; 
   document.getElementById("second").innerHTML =seconds; 
   if (t < 0) {
      clearInterval(x); 
      document.getElementById("time-up").innerHTML = "TIME UP"; 
      document.getElementById("day").innerHTML ='0'; 
      document.getElementById("hour").innerHTML ='0'; 
      document.getElementById("minute").innerHTML ='0' ; 
      document.getElementById("second").innerHTML = '0'; 
   } 
}, 1000);  

</script>

<body>
   
<div class="full-size-head">
   <div class="row ancho">
    <div class="col-lg-4 col-sm-12 lanzamiento d-flex flex-row">
        <span class="mx-auto"><img src="template/images/banner-19.png" style="width:100%"> </img></span>
    </div>
    
    <div class="col-sm-12 col-lg-4  d-flex flex-row">
         <div class="timer mx-auto">
             
            <div class="dias">
               <span class="days" id="day"></span> :
               <div class="smalltext">DIAS</div>
            </div>
            <div class="horas">
               <span class="hours" id="hour"></span> :
               <div class="smalltext">HRS</div>
            </div>
            <div class="minutos">
               <span class="minutes" id="minute"></span> :
               <div class="smalltext">MIN</div>
            </div>
            <div class="segundos">
               <span class="seconds" id="second"></span> 
               <div class="smalltext">SEG
                </div>
            </div>
            <p id="time-up"></p>
         </div>
    </div>
    
    <div class="col-lg-4 col-sm-12 lanzamiento d-flex flex-row">
        <span class="mx-auto"><img src="template/images/banner-29.png" style="width:100%"> </img></span>
    </div>

   </div>
   
</div>
</div>
<div class="row">
    <div class="col-lg-12" > 
    <img width="100%" src="template/images/pieza web 2-04.png">
    </img> 
    </div>
    
 
</div>

       <!-- <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8"> 
                
                   <center>
                        <a class="navbar-brand" href="#"><img src="{{ asset('template/images/logo3.png') }}"  style="width: 350px;" alt=""></a>
                        <img src="{{ asset('template/images/espera.jpg')}}">
                    </center>
                    
                </div>
                <div class="col-md-2"></div>
            </div>
        </div> -->

    <footer class="py-5 footer-section">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <center>
                        <h4 class=" font-size-13-tr font-weight-bold text-color-dark-tr">Síguenos en nuestras Redes Sociales:</h4>
                        
                       <div class="col-md-12">
                    <ul class="social-network social-circle">
                        
                        <li><a href="https://www.facebook.com/TransformatePro/" target="_blank" class="icoFacebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://www.instagram.com/transformatepro/?hl=es-la" class="icoInstagram"  target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="https://www.youtube.com/channel/UCdr-Ig698cKvfiKtRTfxf0A" class="icoYoutube" target="_blank" title="Youtube"><i class="fab fa-youtube"></i></a></li>

                    </ul>				
				</div>
                       
                       
                    
                                                     <!--<div style="color: white;" class="d-inline-flex mt-2">
                                
                                                        <div class="mr-1 d-flex justify-content-center align-items-center  bg-color-light-tr social-div-tr" >
                                                            <a href="https://www.facebook.com/TransformatePro/" target="_blank"><i class="fab fa-facebook"></i></a>
                                                        </div>
                                
                                                        <div  class="ml-1 d-flex justify-content-center align-items-center bg-color-light-tr social-div-tr">
                                                            <a href="#"><i class="fab fa-instagram"></i></a>
                                                        </div>
                                                    </div> -->
                                                    
                                                    
                    <p class="mt-4">Transfórmate. &copy;2020 Todos los derechos reservados | TÃ©rminos y condiciones</p>
                    </center>
                    
                </div>
            </div>
        </div>
    </footer>
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

        function cargar($lista){
            console.log($lista);
        }

        function desactivarModal($modal){
            if ($modal == 'register'){
                $("#modal-register").modal('hide');
                $("#modal-login").modal('show');
            }else if ($modal == 'login'){
                $("#modal-login").modal('hide');
                $("#modal-register").modal('show');
            }else if ($modal == 'recover'){
                $("#modal-login").modal('hide');
                $("#modal-recover").modal('show');
            }
        }
    </script>
</body>
</html>

