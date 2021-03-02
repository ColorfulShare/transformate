@extends('layouts.landing')

@push('scripts')
   <script>
      function loadStep($step){
         if ($step == 1){
            document.getElementById("title-step").innerHTML = 'Crea tu Curso';
            document.getElementById("content-step").innerHTML = '<p>HA LLEGADO EL MOMENTO DE MOVILIZAR E INSPIRAR A OTROS</p><p>Hemos diseñado una metodología para la elaboración de tu curso, muy sencilla y te la explicamos paso por paso. Lo más importante es que tengas muy claro, cual de todos los temas es el que más te apasiona.. Comienza por ese, recuerda que luego cuando adquieras la práctica, podrás crear cuantos cursos quieras en TransformatePRO.</p><p>¿Iniciamos de la mano juntos este primer paso de la  Ruta del Mentor?<br> Transformatepro, te acompañará en cada paso de la Ruta del Mentor. Por lo que empieza descargando el modelo del curso que hemos diseñado para tu orientación y guía. Podrás contactarnos en cualquier momento de requerir nuestro soporte. Nuestro equipo, estará muy atento a apoyarte.</p><a type="button" href="{{ asset('documents/modelo_t_curso.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Modelo de T-Curso</a> <hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/modelo_t_mentoring.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Modelo de T-Mentoring</a>';
         }else if ($step == 2){
            document.getElementById("title-step").innerHTML = 'Graba tu Curso Online';
            document.getElementById("content-step").innerHTML = '<p>¡A DAR LO MEJOR DE TI, ACCIÓN!</p><p>Este es un dia muy especial, y debes estar consciente de esto. Recuerda que solo eres un canal para llevar tu mensaje de transformación a toda la humanidad. Por lo que te recomendamos, estudiar muy bien tus lecciones los días previos, preparar tu oratoria y tu gesticulación. Hacerlo frente a un espejo y midiéndote el tiempo de duración de tus lecciones; son dos buenos tips que te servirán muchísimo. Solo necesitarás un Smartphone, Iphone o una cámara de video si tienes, algunas luces o el aprovechamiento de la luz natural, micrófono y listo.</p><p>¿Iniciamos de la mano juntos este segundo paso de la  Ruta del Mentor?<br>Transformatepro, te acompañará en cada paso de la Ruta del Mentor. Por lo que empieza descargando el protocolo de grabación, que hemos diseñado para tu orientación y guía. Podrás contactarnos en cualquier momento de requerir nuestro soporte.</p> <a type="button" href="{{ asset('documents/consideraciones_generales.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Consideraciones Generales</a> <hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/modelo_de_grabacion.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Modelo de Grabación</a> <hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/consideraciones_dia_grabacion.pptx') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-powerpoint"></i> Consideraciones del Día de Grabación</a>';
         }else if ($step == 3){
            document.getElementById("title-step").innerHTML = 'Dale Valor a tu Comunidad';
            document.getElementById("content-step").innerHTML = '<p>¡DA LA MILLA EXTRA SIEMPRE!</p><p>Tus estudiantes y mentoriados, esperan por ti. Les encantará conocer a quien han escuchado y aprendido por horas. .</p><p>Por lo que contarás con un modulo de T-Mentor dentro de Transformatepro, para que puedas participar de forma activa en el foro de estudiantes, les puedas agradecer por haber accedido a tu curso y lo mejor, podrás actualizar el contenido de tu curso para generarle a tus estudiantes, tanto valor como puedas darle. Créenos, tus estudiantes sentirán tu presencia, y se fidelizarán contigo, mejor aún replicarán su experiencia vivida.</p> <a type="button" href="{{ asset('documents/crea_tu_comunidad.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Crea tu Comunidad</a><hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/productos_complementarios.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Productos Complementarios</a>';
         }else if ($step == 4){
            document.getElementById("title-step").innerHTML = 'Acuerdos T-Mentor';
            document.getElementById("content-step").innerHTML = '<p>Y con el fin que nuestra Ruta, se construya desde sus inicios de una forma sólida, basados en el conocimiento de los lineamientos a ser tenidos en cuenta para formalizar nuestra relación T-Mentor y el equipo de Transformatepro; te invitamos a descargar esta información, que respaldará cada una de las acciones que estamos listos para iniciar contigo querido mentor. Así que manos a la obra. Nuestro equipo, estará muy atento a apoyarte, para que esta Ruta del mentor, la  transites de forma extraordinaria.</p> <a type="button" href="{{ asset('documents/filosofia_y_lineamientos.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Filosofía y Lineamientos</a><hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/formato_de_inscripcion.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Formato de Inscripción</a><hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/perfil_del_mentor.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Perfil del Mentor</a><hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/pasos_ruta_del_mentor.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Pasos Ruta del Mentor</a><hr class="uk-hidden@s"> <a type="button" href="{{ asset('documents/servicios_productora.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Servicios Productora</a>';
         }
      }
   </script>
@endpush

@section('content')
	{{-- Sección T-Mentor --}}
   <div>
      <img class="t-mentor-banner" src="{{ asset('images/t_mentor_banner.jpg') }}" />
      <div class="uk-width-1-1 t-mentor-text-banner uk-text-center">
         <h1 class="uk-text-bold title">SÉ PARTE DEL EQUIPO TRANSFORMADOR</h1>
         <span class="description uk-visible@s"><br> Sé parte del Equipo Transformador.<br> Si tienes una  pasión y quieres contribuir con ella a la transformación de esta humanidad, aquí encontrarás la Ruta para hacerlo.</span>
         <span class="description uk-hidden@s">Sé parte del Equipo Transformador.<br> Si tienes una  pasión y quieres contribuir con ella a la transformación de esta humanidad, aquí encontrarás la Ruta para hacerlo.</span>
         <div class="uk-width-1-1 uk-text-center t-mentor-button-div">
            <a class="t-mentor-button-blue" href="#modal-register-instructor" uk-toggle>Conviértete en T-Mentor</a>
         </div>
      </div>
   </div>

   <div class="t-mentor-imagen-info">
      <div class="t-mentor-info" style="background-image: url('http://localhost:8000/images/t_mentor_banner2.jpg');background-repeat: no-repeat;background-size: cover; height: 250px; display: flex; align-items: center;">
         <div>
            Como MENTOR podrás crear video cursos o audio cursos en diferentes temáticas y <br>
            categorías, sobre todo en aquellas donde está tu pasión, identifícala y luego podrás elegir en Transfórmate PRO la categoría de cursos de tu preferencia.
         </div>
      </div>
   </div>

   <div class="t-mentor-route uk-text-center background-ligth2" id="t-mentor-route">
   	<div class="t-mentor-route-title color-ligth2" id="t-mentor-route-title">Sigue tu ruta</div>
   		<div class="uk-child-width-1-2 uk-child-width-1-4@m uk-child-width-1-4@l" uk-grid>
   			<div>
               <a href="javascript:;" onclick="loadStep(1);">
   				  <span class="t-mentor-route-number color-ligth2" id="t-mentor-route-number1">1.</span> <span class="t-mentor-route-text color-ligth2" id="t-mentor-route-text1">Crea tu curso</span><br>
   				  <img src="{{ asset('images/step1.png') }}" class="t-mentor-route-icon">
               </a>
   			</div>
   			<div>
               <a href="javascript:;" onclick="loadStep(2);">
   				  <span class="t-mentor-route-number color-ligth2" id="t-mentor-route-number2">2.</span> <span class="t-mentor-route-text color-ligth2" id="t-mentor-route-text2">Graba tu curso</span><br>
   				  <img src="{{ asset('images/step2.png') }}" class="t-mentor-route-icon">
               </a>
   			</div>
   			<div>
               <a href="javascript:;" onclick="loadStep(3);">
   				  <span class="t-mentor-route-number color-ligth2" id="t-mentor-route-number3">3.</span> <span class="t-mentor-route-text color-ligth2" id="t-mentor-route-text3">Dale valor a tu comunidad</span><br>
   				  <img src="{{ asset('images/step3.png') }}" class="t-mentor-route-icon">
               </a>
   			</div>
   			<div>
               <a href="javascript:;" onclick="loadStep(4);">
   				  <span class="t-mentor-route-number color-ligth2" id="t-mentor-route-number4">4.</span> <span class="t-mentor-route-text color-ligth2" id="t-mentor-route-text4">Lineamientos y acuerdos</span><br>
   				  <img src="{{ asset('images/step4.png') }}" class="t-mentor-route-icon">
               </a>
   			</div>
   		</div>
         
         <div class="t-mentor-route-lineamientos color-ligth2" id="lineamientos">
            <div class="t-mentor-route-lineamientos-title" id="title-step">Crea tu Curso</div>
            
            <div id="content-step">
               <p>HA LLEGADO EL MOMENTO DE MOVILIZAR E INSPIRAR A OTROS.</p>

               <p>Hemos diseñado una metodología para la elaboración de tu curso, muy sencilla y te la explicamos paso por paso. Lo más importante es que tengas muy claro, cual de todos los temas es el que más te apasiona.. Comienza por ese, recuerda que luego cuando adquieras la práctica, podrás crear cuantos cursos quieras en TransformatePRO.</p>

               <p>¿Iniciamos de la mano juntos este primer paso de la  Ruta del Mentor?
               Transformatepro, te acompañará en cada paso de la Ruta del Mentor. Por lo que empieza descargando el modelo del curso que hemos diseñado para tu orientación y guía. Podrás contactarnos en cualquier momento de requerir nuestro soporte. Nuestro equipo, estará muy atento a apoyarte.</p>

               <a type="button" href="{{ asset('documents/modelo_t_curso.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Modelo de T-Curso</a>
               <hr class="uk-hidden@s">
               <a type="button" href="{{ asset('documents/modelo_t_mentoring.pdf') }}" class="uk-button courses-button-blue" target="_blank"><i class="far fa-file-pdf"></i> Modelo de T-Mentoring</a>
            </div>
          
         </div>

         <div class="t-mentor-route-line"></div><br>

         <div class="uk-width-1-1 uk-text-center">
            <a class="t-mentor-button-blue start-now-button" href="#modal-register-instructor" uk-toggle>Empieza Ahora</a>
         </div>
   	</div>
      
      {{--<div class="t-mentor-info">
         <div style="color: black; font-size: 35px; font-weight: bold;">TESTIMONIOS T-MENTOR</div>
      </div>--}}
      
      <div class="t-mentor">
         <img src="https://www.transformatepro.com/template/images/contact-us.png" id="t-mentor-banner-final-img" />
         <div class="uk-width-1-1 t-mentor-banner-final uk-text-center">
            <h1 class="uk-text-bold title">NUESTRO EQUIPO 24/7 PARA TÍ T-MENTOR</h1>
            <div class="uk-visible@s">
               <br>
               <span class="description">Estaremos contigo en cada paso de tu Ruta de Mentor.<br> Disfrutaremos juntos de este camino.</span>
               <!--<div class="uk-width-1-1 uk-text-center t-mentor-button-div">
                  <a class="t-mentor-button-blue start-now-button" href="#modal-register-instructor" uk-toggle>Contáctanos</a>
               </div>-->
            </div>
            <div class="uk-hidden@s">
               <span class="description">Estaremos contigo en cada paso de tu Ruta de Mentor.<br> Disfrutaremos juntos de este camino.</span>
            </div>
         </div>
      </div>
   	
      <div class="t-mentor-final-section">
         <div uk-grid>
            <div class="uk-width-1-1 uk-text-center">
               <h1 class="uk-text-bold title">CONVIÉRTETE EN MENTOR AHORA</h1>
               <span class="description">Sé parte de la transformación de miles de personas.</span>
               <div class="uk-width-1-1 t-mentor-button-div">
                  <a class="t-mentor-button-aqua start-now-button" href="#modal-register-instructor" uk-toggle>Conviértete en T-Mentor</a>
               </div>
            </div>
         </div>
      </div>
@endsection