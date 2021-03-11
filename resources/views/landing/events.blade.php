@extends('layouts.landing')

@section('fb-events')
   fbq('track', 'InitiateCheckout');
@endsection

@push('scripts')
   <script>
      function cargarModal($evento){
         document.getElementById("event_id").value = $evento;
         document.getElementById("event_type").value = document.getElementById("event-type-"+$evento).value;
         var modal = UIkit.modal("#modal-event");
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

   <div class="courses-banner">
      <img src="{{ asset('images/courses_banner.jpg') }}" alt="" class="uk-visible@s">
      <img src="{{ asset('images/courses_banner_movil.jpg') }}" alt="" class="uk-hidden@s">
      <div class="courses-banner-text">
         <h1 class="uk-text-bold title">Asiste o regala el poder participar en nuestros eventos</h1>
         <span class="description">Descubre como puedes ser parte de los cursos que están cambiando la manera de ver la vida en nuestros asistentes.</span>
      </div>
   </div>

   <div class="events-content">
      <div uk-grid>
         <div class="uk-width-1-3@xl uk-width-1-3@l uk-width-1-3@m uk-width-1-3@s">
            <div class="courses-category-title">Regala<br> transformación</div>

            <div class="events-text-info">
               Puedes comprar entradas para regalar al darle click en comprar o subscribirte y recibir noticias de futuros eventos

               <br><br>

               <a class="link-course" href="#modal-newsletter" uk-toggle> <span class="btn-course2">Subscríbete</span></a>
            </div>
         </div>
         <div class="uk-width-2-3@xl uk-width-2-3@l uk-width-2-3@m uk-width-2-3@s cards-section">
            <div class="courses-category-selected">Próximos Eventos</div>
               @foreach ($eventos as $evento)
                  <div uk-grid>
                     <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-1@m event-left-side">
                        <img src="{{ asset('uploads/events/images/'.$evento->image) }}" alt="{{ $evento->title }}" style="height: 100%;">
                     </div>
                     <div class="uk-width-1-2@xl uk-width-1-2@l uk-width-1-1@m event-right-side">
                        <div class="event-info">
                           <input type="hidden" id="event-type-{{$evento->id}}" value="{{ $evento->type }}">
                           <div class="event-title">{{ $evento->title }}</div>
                           <div class="event-instructor">Por: {{ $evento->user->names }} {{ $evento->user->last_names }}</div>
                           <div class="event-datetime">{{ $evento->date }} <br> {{ $evento->time }}</div>
                           <div class="event-legend">{{ $evento->legend }}</div>
                           <div class="event-price">
                              @if ($evento->presale == 1)
                                 Preventa: COP {{ number_format($evento->presale_price, 0, ',', '.') }}<br>
                              @endif
                              General: COP {{ number_format($evento->price, 0, ',', '.') }}
                           </div>
                           <div class="event-buttons">
                              <a href="{{ route('landing.events.show', [$evento->slug, $evento->id]) }}" class="link-course" href="#modal-newsletter" uk-toggle> <span class="btn-course2">Leer Más</span></a>
                              <a class="link-course" href="javascript:;" onclick="cargarModal({{$evento->id}});"> <span class="btn-course2">Comprar</span></a>
                           </div>
                        </div>
                     </div>
                  </div>
                  
               @endforeach
            <div>
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
               <div class="uk-width-1-1 event-inscription-div">
                  <div class="event-inscription-title">Ingresa tus datos para pagar tus entradas</div>
                  <div class="event-inscription-subtitle">Verifíca que tus datos sean correctos al momento de enviar el fomulario</div>

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
                              <input type="submit" class="uk-button courses-button-blue" value="Inscribirme">
                           @else
                              <input type="submit" class="uk-button courses-button-blue" value="Continuar y Pagar">
                           @endif
                        </div>
                     </div>
                  </form>
               </div>
           </div>
       </div>
   </div>
@endsection