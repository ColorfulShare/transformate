@extends('layouts.landing')

@push('styles')
   <link rel="stylesheet" href="{{ asset('css/events.css') }}">

   <style>
      #events-title{
         padding: 20px 50px;
      }
      #events-agenda{
         padding: 20px 100px;
      }
      @media (min-width: 320px) and (max-width: 640px) {
         #events-title{
            padding: 15px 10px;
         }
         #events-agenda{
            padding: 10px 10px;
         }
      }
   </style>
@endpush

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });
      });

      var vid = document.getElementById("event-video");
      vid.onplay = function() {
         var url = {{ $www }};
         if (url == 1){
            var path = "https://www.transformatepro.com/t-events/add-video-view-counter/{{$evento->id}}";
         }else{
            var path = "https://transformatepro.com/t-events/add-video-view-counter/{{$evento->id}}";
         }

         //var path = "http://localhost:8000/t-events/add-video-view-counter/{{$evento->id}}";
                        
         $.ajax({
            type:"GET",
            url:path,
            success:function(ans){
               document.getElementById("video-viewers-pc").innerHTML = ans;        
               document.getElementById("video-viewers-movil").innerHTML = ans;               
            }
         });
      };
   </script>

   <script>
      // Set the date we're counting down to
      // var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();
      var fecha = document.getElementById("countdown_limit").value;
      if (fecha != null){
         
         var countDownDate = new Date(fecha).getTime();

         // Update the count down every 1 second
         var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();
           // Find the distance between now and the count down date
           var distance = countDownDate - now;

           // Time calculations for days, hours, minutes and seconds
           var days = Math.floor(distance / (1000 * 60 * 60 * 24));
           var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
           var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
           var seconds = Math.floor((distance % (1000 * 60)) / 1000);

           // Display the result in the element with id="demo"
           document.getElementById("days").innerHTML = days;
           document.getElementById("hours").innerHTML = hours;
           document.getElementById("minutes").innerHTML = minutes;
           document.getElementById("seconds").innerHTML = seconds;

           // If the count down is finished, write some text
            if (distance < 0) {
               clearInterval(x);
               document.getElementById("countdown").style.display = 'none';
            }
         }, 1000);
      }
      
   </script>
@endpush

@section('content')
   <input type="hidden" id="countdown_limit" value="{{ $countdown_limit }}">
   @if ($errors->any())
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif

   @if (Session::has('msj-exitoso'))
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-success">
            <strong>{{ Session::get('msj-exitoso') }}</strong>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif

   @if (Session::has('msj-erroneo'))
      <div class="row">
         <div class="col-md-2"></div>
         <div class="col-md-8 alert alert-danger">
            <strong>{{ Session::get('msj-erroneo') }}</strong>
         </div>
         <div class="col-md-2"></div>
      </div>
   @endif
   
   <div class="countdown" id="countdown" @if ($countdown_limit == NULL) style="display: none;" @endif>
      <div uk-grid>
         <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center countdown-normal-price-div">
            <div class="countdown-normal-price-text">Precio Normal: {{ number_format($evento->price, 0, ',', '.') }} COP</div>   
         </div>
         <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center countdown-presale-price-div">
            <div class="countdown-presale-price-text">Precio Preventa: {{ number_format($evento->presale_price, 0, ',', '.') }} COP</div>
         </div>
         <div class="uk-width-1-1 uk-text-center countdown-end-div">
            <div class="countdown-end-text">Esta oferta finalizará en:</div>   
         </div>
         <div class="uk-width-1-1 uk-text-center countdown-div">
            <div uk-grid>
               <div class="uk-width-1-4 uk-text-center">
                  <span class="countdown-time" id="days"></span><br> Días
               </div>
               <div class="uk-width-1-4 uk-text-center">
                  <span class="countdown-time" id="hours"></span><br> Horas
               </div>
               <div class="uk-width-1-4 uk-text-center">
                  <span class="countdown-time" id="minutes"></span><br> Min
               </div>
               <div class="uk-width-1-4 uk-text-center">
                  <span class="countdown-time" id="seconds"></span><br> Seg
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="background-ligth2 text-center" id="events-title">
      {{-- Sección Video e Inscripción --}}
      <div class="pt-20 pb-20">
         <div class="uk-visible@l video-section" uk-grid>
            <div class="uk-width-2-3 uk-text-right">
               <span style="padding-right: 10px;" >
                  <i class="fa fa-users"></i> <span id="video-viewers-pc">{{ $evento->video_view_counter }}</span> 
               </span>
            </div>
            <div class="uk-width-1-3 uk-text-bold">
               REGÍSTRATE AL T-EVENT
            </div>
         </div>
         <div class="uk-hidden@l video-section" uk-grid>
            <div class="uk-width-1-1  uk-text-right">
               <span style="padding-right: 10px;">
                  <i class="fa fa-users"></i> <span id="video-viewers-movil">{{ $evento->video_view_counter }}</span> 
               </span>
            </div>
         </div>
         <div class="mt-10" uk-grid>
            <div class="uk-width-2-3@l uk-width-1-1@m uk-text-right video-responsive">
               @if (!is_null($evento->video))
                  <video src="{{ asset('uploads/events/videos/'.$evento->video) }}" id="event-video" controls controlslist="nodownload"></video>  
               @else
                  Este evento no tiene un video disponible
               @endif
            </div>
            <div class="uk-width-1-1 uk-text-bold video-section uk-hidden@l">
               REGÍSTRATE AL T-EVENT
            </div>
            <div class="uk-width-1-3@l uk-width-1-1@m">
               <div class="uk-width-1-1 email-login">
                  <div class="video-section-event-title">{{ $evento->title }}</div>
                  Déjanos tus datos para tenerte en cuenta...<br>
                  @if ($evento->type == 'pay')
                     El evento tiene un costo de <b>COP$ @if ($evento->presale == 1) {{ number_format($evento->presale_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif</b>
                  @endif

                  <form method="POST" action="{{ route('landing.events.subscribe') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" value="{{ $evento->id }}">
                     <input type="hidden" name="event_type" value="{{ $evento->type }}">
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-user"></i></span>
                           <input class="uk-input" type="text" name="names" placeholder="Tu Nombre y Apellido (*)" required>
                        </div>
                     </div>
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon" uk-icon="icon: mail"></span>
                           <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico (*)" required>
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
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-mobile-alt"></i></span>
                           <input class="uk-input" type="text" name="phone" placeholder="Tu Número de Contacto">
                        </div>
                     </div>
                     <div class="uk-child-width-1-1" uk-grid>
                        <div class="uk-text-center">
                           @if ($evento->type == 'free')
                              <input type="submit" class="login-button" value="Inscribirme">
                           @else
                              <input type="submit" class="login-button" value="Continuar y Pagar">
                           @endif
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div> 

      <a href="#modal-event" uk-toggle><img class="uk-visible@s" src="{{ asset('uploads/events/images/'.$evento->image) }}" /></a>
      <a href="#modal-event" uk-toggle><img class="uk-hidden@s" src="{{ asset('uploads/events/images/'.$evento->image_movil) }}" /></a>

      {{-- SECCIÓN DE BENEFICIOS --}}
      @if (!is_null($evento->benefits))
         <div style="background-color: #6E12FF; margin-top: 20px;">
            <div uk-grid>
               <div class="uk-width-1-3@l uk-width-1-3@m uk-visible@m text-center">
                  <img src="{{ asset('uploads/events/benefits/'.$evento->benefits_img) }}" class="benefits-img" alt="">
               </div>
               <div class="uk-width-2-3@l uk-width-2-3@m uk-width-1-1@s uk-text-bold uk-text-left padding-50 white-text">
                  <div class="benefits-title">BENEFICIOS A LOS QUE ACCEDERÁS</div>

                  <div class="benefits-items">
                     {!! $evento->benefits !!}
                  </div>
               </div>
            </div>
         </div>
      @endif

      {{-- SECCIÓN DE TESTIMONIO --}}
      @if ($evento->testimonies_count > 0)
         <div class="padding-50">
            <div class="testimony-title uk-text-bold">¿POR QUÉ ESTE T-EVENT<br> TRANSFORMARÁ TU VIDA?</div>
            
            @foreach ($evento->testimonies as $testimonio)
               <div uk-grid>
                  <div class="uk-width-expand@l uk-width-expand@m uk-width-1-1@s uk-text-bold uk-text-center">
                     <div class="testimony-text color-ligth" id="testimonio-text-{{$testimonio->id}}">
                        {!! $testimonio->text !!}
                     </div>
                  </div>

                  <div class="uk-width-auto@l uk-width-auto@m uk-width-1-1@s">
                     <img src="{{ asset('uploads/events/testimonies/'.$testimonio->image) }}" class="testimony-img"><br>
                     <div class="testimony-autor">{{ $testimonio->autor }}</div>
                  </div>
               </div>
            @endforeach
         </div>
      @endif

      {{-- SECCIÓN DE AGENDA ACADÉMICA --}}
      <div class="padding-50" style="background-color: #00AAC6;">
         <div uk-grid>
            <div class="uk-width-1-1 uk-text-bold uk-text-center diary-title">
               AGENDA ACADÉMICA<br> T-EVENT
            </div>
            <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-right">
               <img src="{{ asset('template/images/agenda-event.png') }}" class="diary-img">
            </div>
            <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s padding-50" style="color: white;">
                <div uk-grid>
                  @if (!is_null($evento->informative_pdf))
                     <div class="uk-width-1-1 text-center">
                        <a class="event-button event-button-info" href="{{ asset('uploads/events/documents/'.$evento->informative_pdf) }}" target="_blank"><i class="fa fa-plus-circle"></i> MÁS INFORMACIÓN</a>
                     </div>
                  @endif
                  <div class="uk-width-1-1 text-center">
                     <a class="event-button event-button-inscribe" href="#modal-event" uk-toggle><i class="fa fa-check"></i> INSCRIBIRME</a><br>
                  </div>
               </div>
            </div>
         </div>
      </div>

      {{-- SECCIÓN T-GIFT --}}
      <div class="padding-50" uk-grid>
         <div class="uk-width-2-3 uk-text-center uk-text-bold gift-section">
            <div>Invita a tu persona favorita al T-Event</div>
            <div>REGALA TRANSFORMACIÓN</div>
         </div>
         <div class="uk-width-1-3 uk-text-left">
            <img src="{{ asset('template/images/gift-event.gif') }}" class="gift-img">
         </div>
         <div class="uk-width-1-1">
            <div uk-grid>
               <div class="uk-width-1-2@l uk-width-1-1@m gift-button-left">
                  <a class="event-button event-button-info" href="#modal-gift" uk-toggle><i class="fa fa-gift"></i> COMPRAR PARA REGALO</a>
               </div>
               <div class="uk-width-1-2@l uk-width-1-1@m gitf-button-right">
                  <a class="event-button event-button-info" href="#modal-apply-gift" uk-toggle><i class="fa fa-gift"></i> CANJEAR T-GIFT</a><br>
               </div>
            </div>
         </div>
      </div>

      <div style="background-color: #00AAC6; height: 40px; margin-top: 30px;"></div>

      {{-- SECCIÓN CONTACTO --}}
      <div class="padding-50" uk-grid>
         <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center" style="padding-top: 30px;">
            <img src="{{ asset('template/images/linea-atencion-event.png') }}" class="atention-img">
         </div>
         <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center uk-text-bold color-ligth atention-text" id="transformation-line-text">
            <div>Línea de Atención <br> TRANSFÓRMATE PRO</div>
            <div style="padding-top: 20px;">
               <a href="https://wa.me/573016088008" target="_blank"><i class="fab fa-whatsapp"></i> 3016088008</a>
            </div>
            <div class="atention-text-small" style="padding-top: 20px;">
               Al servicio de tu transformación
            </div>
         </div>
      </div>
   </div>

   <div id="modal-event" uk-modal>
      <div class="uk-modal-dialog uk-margin-auto-vertical">
         <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
               <h4>Registro de Evento</h4>
            </div>
            <div class="uk-modal-body" style="padding: 0px 0px;">
               <div class="uk-width-1-1 email-login">
                  Déjanos tus datos para tenerte en cuenta...<br>
                  @if ($evento->type == 'pay')
                     El evento tiene un costo de <b>COP$ @if ($evento->presale == 1) {{ number_format($evento->presale_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif</b>
                  @endif

                  <form method="POST" action="{{ route('landing.events.subscribe') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" value="{{ $evento->id }}">
                     <input type="hidden" name="event_type" value="{{ $evento->type }}">
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-user"></i></span>
                           <input class="uk-input" type="text" name="names" placeholder="Tu Nombre y Apellido (*)" required>
                        </div>
                     </div>
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon" uk-icon="icon: mail"></span>
                           <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico (*)" required>
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
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-mobile-alt"></i></span>
                           <input class="uk-input" type="text" name="phone" placeholder="Tu Número de Contacto">
                        </div>
                     </div>
                     <div class="uk-child-width-1-1" uk-grid>
                        <div class="uk-text-center">
                           @if ($evento->type == 'free')
                              <input type="submit" class="login-button" value="Inscribirme">
                           @else
                              <input type="submit" class="login-button" value="Continuar y Pagar">
                           @endif
                        </div>
                     </div>
                  </form>
               </div>
           </div>
       </div>
   </div>

   <div id="modal-gift" uk-modal>
      <div class="uk-modal-dialog uk-margin-auto-vertical">
         <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
               <h4>T-GIFT para Evento</h4>
            </div>
            <div class="uk-modal-body" style="padding: 0px 0px;">
               <div class="uk-width-1-1 email-login">
                  Por favor, ingresa tu correo electrónico...<br>
                  El evento tiene un costo de <b>COP$ @if ($evento->presale == 1) {{ number_format($evento->presael_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif</b>

                  <form method="POST" action="{{ route('landing.events.buy-to-gift') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" value="{{ $evento->id }}">
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon" uk-icon="icon: mail"></span>
                           <input class="uk-input" type="email" name="gift_buyer" placeholder="Correo Electrónico (*)" required>
                        </div>
                     </div>
                     <div class="uk-child-width-1-1" uk-grid>
                        <input type="submit" class="login-button" value="Continuar y Pagar">
                     </div>
                  </form>
               </div>
           </div>
       </div>
   </div>

   <div id="modal-apply-gift" uk-modal>
      <div class="uk-modal-dialog uk-margin-auto-vertical">
         <button class="uk-modal-close-default" type="button" uk-close></button>
            <div class="uk-modal-header">
               <h4>Canjear T-GIFT</h4>
            </div>
            <div class="uk-modal-body" style="padding: 0px 0px;">
               <div class="uk-width-1-1 email-login">
                  Por favor, ingresa tus datos y tu código T-GIFT...<br>

                  <form method="POST" action="{{ route('landing.events.redeem-gift') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" value="{{ $evento->id }}">
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-user"></i></span>
                           <input class="uk-input" type="text" name="names" placeholder="Tu Nombre y Apellido (*)" required>
                        </div>
                     </div>
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon" uk-icon="icon: mail"></span>
                           <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico (*)" required>
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
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-mobile-alt"></i></span>
                           <input class="uk-input" type="text" name="phone" placeholder="Tu Número de Contacto">
                        </div>
                     </div>
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fa fa-gift"></i></span>
                           <input class="uk-input" type="text" name="gift_code" placeholder="Tu Código T-GIFT">
                        </div>
                     </div>
                     <div class="uk-child-width-1-1" uk-grid>
                        <div class="uk-text-center">
                           <input type="submit" class="login-button" value="Canjear">
                        </div>
                     </div>
                  </form>
               </div>
           </div>
       </div>
   </div>
@endsection