@extends('layouts.landing')

@push('styles')
   <link rel="stylesheet" href="{{ asset('css/events.css') }}">
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
                    document.getElementById("video-viewers").innerHTML = ans;                  
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

    <!-- Contador de Preventa -->
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
                    <div class="uk-width-1-4 uk-text-center countdown-time-div">
                        <div class="countdown-time-div-background">
                            <span class="countdown-time" id="days"></span><br> Días
                        </div>
                    </div>
                    <div class="uk-width-1-4 uk-text-center countdown-time-div">
                        <div class="countdown-time-div-background">
                            <span class="countdown-time" id="hours"></span><br> Horas
                        </div>
                    </div>
                    <div class="uk-width-1-4 uk-text-center countdown-time-div">
                        <div class="countdown-time-div-background">
                            <span class="countdown-time" id="minutes"></span><br> Min
                        </div>
                    </div>
                    <div class="uk-width-1-4 uk-text-center countdown-time-div">
                        <div class="countdown-time-div-background">
                            <span class="countdown-time" id="seconds"></span><br> Seg
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contador de Preventa -->
    <div >
        <div class="main-content-event">
            <hr style="background-color: gray;">

            <!-- Descripción del Evento -->
            @if (!is_null($evento->description))
                <div class="uk-text-center">
                    {!! $evento->description !!}

                    <hr style="background-color: gray;">
                </div>
            @endif
            <!-- Descripción del Evento -->

            <!-- Video y Formulario de Inscripción -->
            <div uk-grid style="padding: 20px 30px;">
                <div class="uk-width-2-3@l uk-width-1-1@m uk-text-right video-responsive video-section">
                    <span>
                        <i class="fa fa-users"></i> <span id="video-viewers">{{ $evento->video_view_counter }}</span> 
                    </span>   
                    @if (!is_null($evento->video))
                        <video src="{{ asset('uploads/events/videos/'.$evento->video) }}" id="event-video" controls controlslist="nodownload" style="height: 90% !important;"></video>  
                    @else
                        Este evento no tiene un video disponible
                    @endif
                </div>
                <div class="uk-width-1-3@l uk-width-1-1@m">
                    <div class="uk-width-1-1 uk-text-bold video-section uk-text-center">
                        REGÍSTRATE AL T-EVENT
                    </div>
                    <div class="uk-width-1-1 email-login" style="padding-bottom: 20px;">
                        <div class="video-section-event-title">{{ $evento->title }}</div>
                        <div class="uk-text-center video-section-event-price">
                            Déjanos tus datos para tenerte en cuenta...<br>
                            @if ($evento->type == 'pay')
                                El evento tiene un costo de <b>COP$ @if ($evento->presale == 1) {{ number_format($evento->presale_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif</b>
                            @endif
                        </div>

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
            <!-- Fin de Video y Formulario de Inscripción -->
            <br>
            <hr style="background-color: gray;">

            <!-- Imagen Banner -->
            <div style="padding: 20px 30px;">
                <a href="#modal-event" uk-toggle><img class="uk-visible@s" src="{{ asset('uploads/events/images/'.$evento->image) }}" /></a>
                <a href="#modal-event" uk-toggle><img class="uk-hidden@s" src="{{ asset('uploads/events/images/'.$evento->image_movil) }}" /></a>
            </div>
            <!-- Imagen Banner -->
            
            <hr style="background-color: gray;">

            <!-- Beneficios -->
            @if (!is_null($evento->benefits))
                <div style="padding: 20px 30px 0px 30px;">
                    <div class="mentor-section-title">¿Qué beneficios obtendrás?</div>
                    <br>
                    {!! $evento->benefits !!}    
                </div>
                
                <hr style="background-color: gray;">
            @endif
            <!-- Beneficios -->

            <!-- Mentor -->
            @if (!is_null($evento->mentor_section_title))
                <div uk-grid style="padding: 20px 0px !important;">
                    <div class="uk-width-1-1 mentor-section-title">
                        {{ $evento->mentor_section_title }} 
                    </div>
                    @if ($cantImagenesMentores > 0)
                        <div class="uk-width-1-1 uk-text-center">
                            {!! $evento->mentor_section !!}
                        </div>
                        <div class="uk-width-1-1 uk-text-center">
                            <div uk-slider>
                                <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1">
                                    <ul class="uk-slider-items uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m">
                                        <li>
                                            <img src="{{ asset('uploads/events/images/'.$evento->mentor_section_img) }}" alt="" style="height: 350px !important;">
                                        </li>
                                        @foreach ($evento->images as $imagen)
                                            @if ($imagen->instructor_section == 1)
                                                <li>
                                                    <img src="{{ asset('uploads/events/images/'.$imagen->image) }}" alt="" style="height: 350px !important;">
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>

                                    <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous" style="color: #6E12FF;"></a>
                                    <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next" style="color: #6E12FF;"></a>
                                </div>

                                <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
                            </div>
            
                        </div>
                    @else
                        <div class="uk-width-2-3@l uk-width-1-1@m">
                            {!! $evento->mentor_section !!}
                        </div>
                        <div class="uk-width-1-3@l uk-width-1-1@m uk-text-center" style="display: flex; align-items: center;">
                            <div>
                                <img src="{{ asset('uploads/events/images/'.$evento->mentor_section_img) }}">
                            </div>
                        </div>
                    @endif
                </div>
                
                <hr style="background-color: gray;">
            @endif
            <!-- Mentor -->

            <!-- Testimonios -->
            @if ($evento->testimonies_count > 0)
                <div class="testimony-content">
                    <div class="uk-width-1-1 mentor-section-title" style="padding-bottom: 15px;">Vidas Transformadas</div>

                    @foreach ($evento->testimonies as $testimonio)
                        <div uk-grid class="uk-card uk-card-default uk-card-body testimony-card">
                            <div class="uk-width-1-3@l uk-width-1-3@m uk-width-1-1@s">
                                <img src="{{ asset('uploads/events/testimonies/'.$testimonio->image) }}" class="testimony-img"><br>
                            </div>
                            <div class="uk-width-2-3@l uk-width-2-3@m uk-width-1-1@s uk-text-bold">
                                <div class="color-ligth" id="testimonio-text-{{$testimonio->id}}">
                                    {!! $testimonio->text !!}
                                </div>
                                <div class="testimony-autor">{{ $testimonio->autor }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr style="background-color: gray;">
            @endif
            <!-- Fin de Testimonios -->

            <!-- Créditos -->
            @if (!is_null($evento->credits_title))
                <div uk-grid style="padding: 20px 0px !important;">
                    <div class="uk-width-1-1 mentor-section-title">
                        {{ $evento->credits_title }} 
                    </div>
                    <div class="uk-width-2-3@l uk-width-1-1@m">
                        {!! $evento->credits !!}
                    </div>
                    <div class="uk-width-1-3@l uk-width-1-1@m uk-text-center" style="display: flex; align-items: center;">
                        <div>
                            <img src="{{ asset('uploads/events/images/'.$evento->credits_img) }}">
                        </div>
                    </div>
                </div>
                
                <hr style="background-color: gray;">
            @endif
            <!-- Fin de Créditos -->

            <!-- Agenda Académica -->
            <div uk-grid class="diary-content">
                <div class="uk-width-1-1 mentor-section-title">Nuestra Agenda Académica</div>

                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-right">
                    <img src="{{ asset('template/images/agenda-event.png') }}">
                </div>
                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s padding-50" style="color: white;">
                    <div uk-grid style="padding-top: 40px;">
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
            <!-- Fin de Agenda Académica -->
           
            <hr style="background-color: gray;">

            <!-- Regalo T-Gift -->
            <div uk-grid class="gift-content">
                <div class="uk-width-2-3 uk-text-bold gift-section uk-text-center">
                    <div>Invita a tu persona favorita al T-Event</div>
                    <div>REGALA TRANSFORMACIÓN</div>
                    <div uk-grid style="padding-top: 20px;">
                        <div class="uk-width-1-1">
                            <a class="event-button event-button-info" href="#modal-gift" uk-toggle><i class="fa fa-gift"></i> COMPRAR PARA REGALO</a>
                        </div>
                        <div class="uk-width-1-1">
                            <a class="event-button event-button-info" href="#modal-apply-gift" uk-toggle><i class="fa fa-gift"></i> CANJEAR T-GIFT</a><br>
                        </div>
                    </div>
                </div>
                <div class="uk-width-1-3 uk-text-left">
                    <img src="{{ asset('template/images/gift-event.png') }}">
                </div>
            </div>
            <!-- Fin de Regalo T-Gift -->
            <br>
            <hr style="background-color: gray;">

            <!-- Inscríbete Ahora -->
            <div uk-grid class="now-section">
                <div class="uk-width-1-1 uk-text-bold mentor-section-title">
                    Inscríbete Ahora.<br>
                    ¡Una extraordinaria oportunidad para tí!
                </div>
                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center video-section" style="display: flex; align-items: center;">
                    <div>
                        <img src="{{ asset('uploads/events/images/'.$evento->image_movil) }}" >
                    </div>
                </div>
                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-center">
                    <div class="uk-width-1-1 uk-text-bold video-section uk-text-center">
                        REGÍSTRATE AL T-EVENT
                    </div>
                    <div class="uk-width-1-1 email-login" style="padding-bottom: 20px;">
                        <div class="video-section-event-title">{{ $evento->title }}</div>
                        <div class="uk-text-center" style="font-size: 15px;">
                            Déjanos tus datos para tenerte en cuenta...<br>
                            @if ($evento->type == 'pay')
                                El evento tiene un costo de <b>COP$ @if ($evento->presale == 1) {{ number_format($evento->presale_price, 0, ',', '.') }} @else {{ number_format($evento->price, 0, ',', '.') }} @endif</b>
                            @endif
                        </div>

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
            <!-- Fin de Inscríbete Ahora -->
            
            <br>
            <hr style="background-color: gray;">
            <!-- Línea de Atención -->
            <div uk-grid style="padding: 20px 30px !important;">
                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-right" style="padding-top: 30px;">
                    <img src="{{ asset('template/images/linea-atencion-event.png') }}">
                </div>
                <div class="uk-width-1-2@l uk-width-1-2@m uk-width-1-1@s uk-text-left uk-text-bold color-ligth atention-text" id="transformation-line-text">
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
                           <input type="submit" class="login-button" value="Canjear">
                        </div>
                     </div>
                  </form>
               </div>
           </div>
       </div>
   </div>
@endsection