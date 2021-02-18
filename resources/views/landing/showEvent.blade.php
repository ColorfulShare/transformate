@extends('layouts.landing')

@push('scripts')
   <script>
      $(function(){    
         $('.video-responsive').bind('contextmenu',function() { return false; });
      });
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

            // Display the result in the element with id="demo"
            document.getElementById("countdown-time").innerHTML = days+" días "+hours+" horas "+minutes+" minutos";

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

  <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid>
      <div class="event-details-div">
         <a href="{{ route('landing.events') }}" class="link-back-to-courses">Volver a T-Eventos</a>
         <div class="event-details-title">{{ $evento->title }}</div>
         <div class="event-details-legend">{{ $evento->legend }}</div>
         <div class="event-details-instructor">Por: {{ $evento->user->names }} {{ $evento->user->last_names }}</div>
         <div class="event-details-price">
            @if ($evento->presale == 1)
               Preventa: COP {{ number_format($evento->presale_price, 0, '.', ',') }}<br>
            @endif
            General: COP {{ number_format($evento->price, 0, '.', ',') }}
         </div>
         @if ($evento->presale == 1)
            <div class="event-details-presale-time" id="countdown">
               La preventa finaliza en <span id="countdown-time"></span>
            </div>
         @endif
      </div>

      <div class="course-preview-div">
         @if (!is_null($evento->video))
            <div class="video-responsive">
               <video src="{{ asset('uploads/events/videos/'.$evento->video) }}" type="video/mp4" controls controlslist="nodownload" class="course-preview-video"></video>
            </div>
         @else
            Este evento no posee un video resumen...
         @endif
      </div>
   </div>

   <div class="uk-child-width-1-2@xl uk-child-width-1-2@l uk-child-width-1-2@m uk-child-width-1-1@s" uk-grid style="background-color: #E5E5E5;">
      <div class="course-content-div">
         <div class="event-content-legend">{!! $evento->legend !!}</div>

         <div class="event-content-about-title"><i class="fas fa-info-circle"></i> Sobre el evento</div>
         <div class="event-content-about-text">{!! $evento->description !!}</div>

         <div class="event-content-about-title"><i class="fas fa-user-circle"></i> Sobre @if ($evento->user->gender == 'F') la instructora @else el instructor @endif</div>
         <div class="event-content-about-text">
            <div style="font-weight: 700;">{{ $evento->user->names }} {{ $evento->user->last_names }}</div>
            {{ $evento->user->profession }}<br>
            {!! $evento->user->review !!}
         </div>

         <div style="padding-top: 30px;">
            <a href="{{ route('landing.events') }}" class="link-back-to-courses"><b>Volver a T-Eventos</b></a>
         </div>
      </div>
      
      <div class="event-content-inscription">
         <div class="uk-card uk-card-default uk-card-body event-inscription-card">
            <ul uk-tab uk-grid class="event-inscription-tabs">
               <li class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-3@s uk-width-1-1@xs event-inscription-tab" style=""><a href="#" class="event-inscription-link">Comprar para mi</a></li>
               <li class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-2@m uk-width-1-3@s uk-width-1-1@xs event-inscription-tab"><a href="#" class="event-inscription-link">Comprar para regalo</a></li>
               <li class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-1@m uk-width-1-3@s uk-width-1-1@xs event-inscription-tab"><a href="#" class="event-inscription-link">Canjear T-Gift</a></li>
            </ul>

            <ul class="uk-switcher uk-margin">
               <li>
                  <div class="uk-width-1-1 event-inscription-div">
                     <div class="event-inscription-title">Ingresa tus datos para pagar tus entradas</div>
                     <div class="event-inscription-subtitle">Verifíca que tus datos sean correctos al momento de enviar el fomulario</div>

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
                        <div class="uk-margin">
                           <div class="uk-inline uk-width-1-1">
                               <span class="uk-form-icon"><i class="fas fa-info-circle"></i></span>
                               <input class="uk-input" type="text" name="reason" placeholder="¿Cómo te enteraste del T-Event?">
                           </div>
                       </div>
                        <div class="uk-child-width-1-1" uk-grid>
                           <div class="uk-text-center">
                              @if ($evento->type == 'free')
                                 <input type="submit" class="uk-button courses-button-blue" value="Inscribirme">
                              @else
                                 <input type="submit" class="uk-button courses-button-blue" value="Continuar y Pagar">
                              @endif
                           </div>
                        </div>
                     </form>
                  </div>
               </li>
               <li>
                  <div class="uk-width-1-1 event-inscription-div">
                     <div class="event-inscription-title">Por favor ingresa tu correo electrónico</div>

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
                           <input type="submit" class="uk-button courses-button-blue" value="Continuar y Pagar">
                        </div>
                     </form>
                  </div>
               </li>
               <li>
                  <div class="uk-width-1-1 event-inscription-div">
                     <div class="event-inscription-title">Por favor, ingresa tus datos y tu código T-GIFT</div>

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
                               <span class="uk-form-icon"><i class="fas fa-info-circle"></i></span>
                               <input class="uk-input" type="text" name="reason" placeholder="¿Cómo te enteraste del T-Event?">
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
                              <input type="submit" class="uk-button courses-button-blue" value="Canjear">
                           </div>
                        </div>
                     </form>
                  </div>
               </li>
            </ul>
         </div>
      </div>
   </div>
@endsection