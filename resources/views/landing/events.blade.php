@extends('layouts.landing')

@push('scripts')
   <script>
      function cargarModal($evento){
         document.getElementById("event_id").value = $evento;
         document.getElementById("event_type").value = document.getElementById("event-type-"+$evento).value;
         /*if (document.getElementById("event_type").value == 'pay'){
            document.getElementById("info-price").innerHTML = 'El evento tiene un costo de <b>COP$ '+document.getElementById("event-price-"+$evento).value+'</b>';
         }*/
         var modal = UIkit.modal("#modal-event");
         modal.show(); 
      }
      function cargarModalGift($evento){
         document.getElementById("event_id2").value = $evento;
         //document.getElementById("info-price2").innerHTML = 'El evento tiene un costo de <b>COP$ '+document.getElementById("event-price-"+$evento).value+'</b>';
         var modal = UIkit.modal("#modal-gift");
         modal.show(); 
      }
      function cargarModalRedeem($evento){
         document.getElementById("event_id3").value = $evento;
         var modal = UIkit.modal("#modal-apply-gift");
         modal.show(); 
      }
   </script>
@endpush

@section('content')
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
   

   <div class="background-ligth2 text-center" id="events-title" style="padding: 20px 0;">
      <span class="best-sellers-title color-ligth " id="best-sellers-title">T-Events Disponibles</span>

      @if ($cantEventos > 0)
         @foreach ($eventos as $evento)
            <input type="hidden" id="event-type-{{$evento->id}}" value="{{ $evento->type }}">
            <input type="hidden" id="event-price-{{$evento->id}}" value="{{ number_format($evento->price, 0, ',', '.') }}">
            <div class="background-ligth2" id="event-div-{{$evento->id}}" style="padding: 10px 30px; text-align: left;">
               <span class="best-sellers-title color-ligth2 " id="event-title-{{$evento->id}}">{{ $evento->title }}</span>
               <div class="uk-position-relative uk-visible-toggle uk-light uk-visible@s" tabindex="-1" uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
                  <ul class="uk-slider-items uk-grid">
                     <li class="uk-width-1-1">
                        <div class="uk-panel">
                           <img src="{{ asset('uploads/events/images/'.$evento->image) }}" alt="">
                        </div>
                     </li>
                     @foreach ($evento->images as $imagen)
                        @if ($imagen->movil == 0)
                           <li class="uk-width-1-1">
                              <div class="uk-panel">
                                 <img src="{{ asset('uploads/events/images/'.$imagen->image) }}" alt="">
                              </div>
                           </li>
                        @endif
                     @endforeach
                  </ul>
                  <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                  <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
               </div>
               {{-- Versión Móvil --}}
               <div class="uk-position-relative uk-visible-toggle uk-light uk-hidden@s" tabindex="-1" uk-slider="center: true; autoplay: true; autoplay-interval: 3000;">
                  <ul class="uk-slider-items uk-grid">
                     <li class="uk-width-1-1">
                        <div class="uk-panel">
                           <img src="{{ asset('uploads/events/images/'.$evento->image_movil) }}" alt="">
                        </div>
                     </li>
                     @foreach ($evento->images as $imagen2)
                        @if ($imagen2->movil == 1)
                           <li class="uk-width-1-1">
                              <div class="uk-panel">
                                 <img src="{{ asset('uploads/events/images/'.$imagen2->image) }}" alt="">
                              </div>
                           </li>
                        @endif
                     @endforeach
                  </ul>
                  <a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                  <a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" uk-slidenav-next uk-slider-item="next"></a>
               </div>
               <div class="t-mentor-button-div text-center header-background-ligth" id="show-info-event-{{$evento->id}}">
                  <div uk-grid>
                     @if ( ($evento->type == 'pay') && (!is_null($evento->informative_pdf)) )
                        <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                           <a class="courses-button-white" href="javascript:;" onclick="cargarModalGift({{$evento->id}});"><i class="fa fa-gift"></i> Comprar para Regalo</a>
                        </div>
                        <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                           <a class="courses-button-white"  href="javascript:;" onclick="cargarModalRedeem({{$evento->id}});"><i class="fa fa-gift"></i> Canjear T-GIFT</a>
                        </div>
                        <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                           <a class="courses-button-white" style="background-color: #41a8c4; color: white;" href="javascript:;" onclick="cargarModal({{$evento->id}});"><i class="fa fa-check"></i> INSCRIBIRME</a>
                        </div>
                        <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div">
                           <a class="courses-button-white" href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}"><i class="fa fa-search"></i> Ver Evento</a>
                        </div>
                        <div class="uk-width-1-5@l uk-width-1-3@m uk-width-1-1@s course-button-white-div">
                           <a class="courses-button-white" href="{{ asset('uploads/events/documents/'.$evento->informative_pdf) }}" target="_blank"><i class="fa fa-plus-circle"></i>Más Información</a>
                        </div>
                     @else
                        @if ($evento->type == 'free')
                           <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div">
                              <a class="courses-button-white" href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}"><i class="fa fa-search"></i> Ver Evento</a>
                           </div>
                           <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                              <a class="courses-button-white" style="background-color: #41a8c4; color: white;" href="javascript:;" onclick="cargarModal({{$evento->id}});"><i class="fa fa-check"></i> INSCRIBIRME</a>
                           </div>
                           <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-1@s course-button-white-div">
                              <a class="courses-button-white" href="{{ asset('uploads/events/documents/'.$evento->informative_pdf) }}" target="_blank"><i class="fa fa-plus-circle"></i>Más Información</a>
                           </div>
                        @else
                           <div class="uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                              <a class="courses-button-white" href="javascript:;" onclick="cargarModalGift({{$evento->id}});"><i class="fa fa-gift"></i> Comprar para Regalo</a>
                           </div>
                           <div class="uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                              <a class="courses-button-white"  href="javascript:;" onclick="cargarModalRedeem({{$evento->id}});"><i class="fa fa-gift"></i> Canjear T-GIFT</a>
                           </div>
                           <div class="uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div">
                              <a class="courses-button-white" href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}"><i class="fa fa-search"></i> Ver Evento</a>
                           </div>
                           <div class="uk-width-1-4@l uk-width-1-3@m uk-width-1-1@s register-button-blue-div" >
                              <a class="courses-button-white" style="background-color: #41a8c4; color: white;" href="javascript:;" onclick="cargarModal({{$evento->id}});"><i class="fa fa-check"></i> INSCRIBIRME</a>
                           </div>
                        @endif

                     @endif
                  </div>
               </div>
            </div>
         @endforeach
      @else
         <div class="t-mentor-button-div text-center background-ligth" id="no-events">
            <h4>No existen eventos disponibles actualmente...</h4>
         </div>
      @endif
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
                  <div id="info-price"></div>

                  <form method="POST" action="{{ route('landing.events.subscribe') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" id="event_id">
                     <input type="hidden" name="event_type" id="event_type">
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
                  <div id="info-price2"></div>

                  <form method="POST" action="{{ route('landing.events.buy-to-gift') }}">
                     {{ csrf_field() }}
                     <input type="hidden" name="event_id" id="event_id2">
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon" uk-icon="icon: mail"></span>
                           <input class="uk-input" type="email" name="gift_buyer" placeholder="Correo Electrónico (*)" required>
                        </div>
                     </div>
                     <div class="uk-child-width-1-1" uk-grid>
                        <div class="uk-text-center">
                           <input type="submit" class="login-button" value="Continuar y Pagar">
                        </div>
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
                     <input type="hidden" name="event_id" id="event_id3">
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
                     <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                           <span class="uk-form-icon"><i class="fas fa-info-circle"></i></span>
                           <input class="uk-input" type="text" name="reason" placeholder="¿Cómo te enteraste del T-Event?">
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