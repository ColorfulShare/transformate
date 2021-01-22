<div class="admin-side">
   <div class="uk-text-center" style="padding: 15px 0 15px 0; border-bottom: solid white 1px;">
      <a href="{{ route('admins.index') }}">
         <img src="{{ asset('template/images/logocoloresinvert.png') }}" alt="" style="height: 45px; width: 90%;">
      </a>
   </div>
  
   
   <ul class="uk-accordion da" uk-accordion>
      {{-- Usuarios --}}
      @if (Auth::user()->profile->users != 0)
         <li class="uk-margin-remove-top">
            <a href="{{ route('admins.index') }}" class="uk-accordion-title">
               <i class="fas fa-users"></i>Usuarios
            </a>
            <div class="uk-accordion-content uk-margin-remove-top d">
               <a href="{{ route('admins.users.students') }}">Alumnos</a>
               <a href="{{ route('admins.users.instructors') }}">Instructores</a>
               <a href="{{ route('admins.users.instructors.pending-list') }}">Instructores Pendientes</a>
               <a href="{{ route('admins.users.partners') }}">T-Partners</a>
               <a href="{{ route('admins.users.administrators') }}">Administradores</a>  
               @if (Auth::user()->profile->profiles != 0)
                  <a href="{{ route('admins.profiles.index') }}">
                     Perfiles Administrativos
                  </a>
               @endif
            </div>
         </li>
      @endif 

      {{-- Cursos --}}
      @if (Auth::user()->profile->courses != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-video"></i>T-Courses
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d">
               <a href="{{ route('admins.courses.index') }}">Creados</a>
               <a href="{{ route('admins.courses.pending-for-publication') }}">Pendientes Para Publicación</a>
               <a href="{{ route('admins.courses.published') }}">Publicados</a>
               <a href="{{ route('admins.courses.featured') }}">Destacados</a>
               <a href="{{ route('admins.courses.disabled-record') }}">Deshabilitados</a>
               <a href="{{ route('admins.courses.home-cover') }}">Configuraciones</a>
               <a href="{{ route('admins.courses.reports.sales') }}">Reportes</a>
            </div>
         </li>
      @endif

      {{-- Certificaciones 
      @if (Auth::user()->profile->certifications != 0)
         <li class="uk-margin-remove-top ">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-landmark"></i>T-Mentorings
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.certifications.index') }}">Creadas</a>
               <a href="{{ route('admins.certifications.pending-for-publication') }}">Pendientes Para Publicación</a>
               <a href="{{ route('admins.certifications.published') }}">Publicadas</a>
               <a href="{{ route('admins.certifications.disabled-record') }}">Deshabilitadas</a>
               <a href="{{ route('admins.certifications.home-cover') }}">Configuraciones</a>
               <a href="{{ route('admins.certifications.reports.sales') }}">Reportes</a>
            </div>
         </li>
      @endif--}}

      {{-- Podcasts --}}
      @if (Auth::user()->profile->podcasts != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-microphone"></i>T-Books
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.podcasts.index') }}">Creados</a>
               <a href="{{ route('admins.podcasts.pending-for-publication') }}">Pendientes Para Publicación</a>
               <a href="{{ route('admins.podcasts.published') }}">Publicados</a>
               <a href="{{ route('admins.podcasts.disabled-record') }}">Deshabilitados</a>
               <a href="{{ route('admins.podcasts.featured') }}">Gestionar Destacados</a>
               <a href="{{ route('admins.podcasts.reports.sales') }}">Reportes</a>
            </div>
         </li>
      @endif

      {{-- Master Clases --}}
      @if (Auth::user()->profile->master_class != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-microphone"></i>T-Master Clases
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.master-class.create') }}">Nueva Master Class</a>
               <a href="{{ route('admins.master-class.index') }}">Disponibles</a>
               <a href="{{ route('admins.master-class.disabled') }}">Deshabilitadas</a>
            </div>
         </li>
      @endif
      
      {{-- Regalos --}}
      @if (Auth::user()->profile->gifts != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-gift"></i>T-Gifts
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.gifts.index') }}">Administrativos</a>
               <a href="{{ route('admins.gifts.user-purchases') }}">Compras de Usuario</a>
               <a href="{{ route('admins.gifts.events-inscriptions') }}">Inscripciones de Eventos</a>
            </div>
         </li>
      @endif

      {{-- Eventos --}}
      @if (Auth::user()->profile->events != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-microphone"></i>T-Events
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.events.index') }}">Eventos Disponibles</a>
               <a href="{{ route('admins.events.record') }}">Eventos Deshabilitados</a>
            </div>
         </li>
      @endif

      {{-- Newsletters --}}
      @if (Auth::user()->profile->newsletters != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-book-reader"></i> Newsletters
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.newsletters.index') }}">Historial</a>
               <a href="{{ route('admins.newsletters.subscribers') }}">Suscriptores</a>
            </div>
         </li>
      @endif
      
      {{-- Pedidos --}}
      @if (Auth::user()->profile->orders != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-shopping-cart"></i> Pedidos
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d">
               <a href="{{ route('admins.orders.index') }}">Listado</a>
               <a href="{{ route('admins.orders.create') }}"> Generar Orden</a>
            </div>
         </li>
      @endif
   
      {{-- Etiquetas de Contenido --}}
      @if (Auth::user()->profile->tags != 0)
         <li class="uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-history"></i> Etiquetas de Contenido
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d">
               <a href="{{ route('admins.tags.index') }}">Listado</a>
            </div>
         </li>
      @endif

      {{-- Retiros --}}
      @if (Auth::user()->profile->liquidations != 0)
         <li class=" uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-wallet"></i>
                  Retiros
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               @if (Auth::user()->profile->liquidations == 2)
                  <a href="{{ route('admins.liquidations.generate-all') }}"> Generar Retiros</a>
               @endif
               <a href="{{ route('admins.liquidations.index') }}"> Historial de retiros </a>
               <a href="{{ route('admins.liquidations.pending') }}"> Retiros Pendientes</a>
            </div>
         </li>
      @endif

      {{-- BAncos --}}
      @if (Auth::user()->profile->banks != 0)
         <li class=" uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-university"></i>
                  Banco
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.bank.pending-transfers') }}">Transferencias Pendientes</a>
               <a href="{{ route('admins.bank.transfers-record') }}">Historial de Transferencias</a>
               <a href="{{ route('admins.bank.accounts') }}">Cuentas Bancarias</a>
            </div>
         </li>
      @endif

      {{-- Cupones --}}
      @if (Auth::user()->profile->coupons != 0)
         <li class=" uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-receipt"></i>
                  Cupones
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.coupons.index') }}"> Listado</a>
               <a href="{{ route('admins.coupons.applied_coupons') }}"> Cupones Aplicados</a>
            </div>
         </li>
      @endif

      {{-- Tickets --}}
      @if (Auth::user()->profile->tickets != 0)
         <li class=" uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-ticket-alt"></i> Tickets
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.tickets.new-tickets') }}">Tickets Nuevos</a>
               <a href="{{ route('admins.tickets.open-tickets') }}">Tickets Abiertos</a>
               <a href="{{ route('admins.tickets.closed-tickets') }}">Tickets Cerrados</a>
               @if (Auth::user()->profile->tickets == 2)
                  <a href="{{ route('admins.tickets.categories') }}">Gestionar Categorías</a>
               @endif
            </div>
         </li>
      @endif

      {{-- Reportes --}}
      @if (Auth::user()->profile->reports != 0)
         <li class=" uk-margin-remove-top">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-chart-bar"></i> Informes
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.reports.best-selling-courses') }}">Cursos Más Vendidos</a>
               <a href="{{ route('admins.reports.best-rating-courses') }}">Cursos Mejores Valorados</a>
               <a href="{{ route('admins.reports.most-taken-courses') }}">Cursos Más Cursados</a>
               <a href="{{ route('admins.reports.most-recent-courses') }}">Últimos Cursos Subidos</a>
               <a href="{{ route('admins.reports.commissions-by-reference') }}">Ganancias por Referencia</a>
            </div>
         </li>
      @endif
      
      {{-- Reportes Fincanciero --}}
      @if (Auth::user()->profile->finances != 0)
         <li class=" uk-margin-remove-top ">
            <a href="javascript():;" class="uk-accordion-title">
               <i class="fas fa-chart-line"></i> Finanzas
            </a>

            <div class="uk-accordion-content uk-margin-remove-top d" hidden="" aria-hidden="true">
               <a href="{{ route('admins.finances.earnings') }}"> Ingresos</a>
               <a href="{{ route('admins.finances.expenses') }}"> Egresos </a>
               <a href="{{ route('admins.finances.balance') }}"> Balance </a>
            </div>
         </li>
      @endif
   </ul>
</div>