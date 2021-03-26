<footer class="footer-section header-background-ligth" id="footer">
   <div class="uk-child-width-1-3@m uk-child-width-1-1@s" uk-grid >
      <div class="uk-text-center" style="color: white;">
         <div class="uk-child-width-1-1" uk-grid>
            <div>
               <span style="font-size: 18px; font-weight: 500;">¿Quieres recibir noticias sobre nosotros?</span><br>
               <span style="font-size: 14px;">Sé el primero en enterarte sobre las últimas promociones y novedades sobre nosotros</span><br>
               <button type="submit" class="uk-button button-suscription" href="#modal-newsletter" uk-toggle>Suscríbete</button>
            </div>
            <div  style="margin-top: 10px !important;">
               <span style="font-size: 18px; font-weight: 500;">¿Quieres dejarnos un mensaje?</span><br>
               <span style="font-size: 14px;">Déjanos tu comentario, sugerencia o pregunta por aquí</span><br>
               <button type="button" class="uk-button button-suscription"  href="#modal-contact-us" uk-toggle>Contáctanos</button>
            </div>
            <!--<div style="margin-top: 5px !important;">
               <form action="{{ route('landing.new-subscriber') }}" method="POST">
                  @csrf
                  <div class="uk-margin">
                     <div class="uk-inline">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input type="email" class="uk-input uk-width-medium" name="email" placeholder="Ingresa tu Email..." style="border-radius: 50px;">
                     </div>
                  </div>
                  <div class="uk-margin">
                     <button type="submit" class="uk-button button-suscription">Suscribirme</button>
                  </div>
               </form>
            </div>-->
         </div>
      </div>

      <div style="color: white;">
         <ul class="uk-list uk-list-divider footer-list">
            <li><a href="{{ route('landing.courses') }}" style="color: white;">Buscar T-Cursos</a></li>
            <li><a href="#modal-register" uk-toggle style="color: white;">Regístrate para recibir correos electrónicos</a></li>
            <li><a href="#modal-register-instructor" uk-toggle style="color: white;">Hazte T-Mentor</a></li>
         </ul>
      </div>

      <div class="third-column">
         {{--<div class="uk-child-width-1-2" uk-grid>
            <div>
               <div class="uk-margin">
                  <div uk-form-custom="target: > * > span:last-child" style="background-color: #c6c6c6; padding: 5px 15px; margin-right: 0.5px;">
                     <select id="theme" onchange="cambiarTema();">
                        <option value="dark">Claro</option>
                        <option value="ligth">Oscuro</option>
                     </select>
                     <span class="uk-link" style="color: #6E6E6E; font-weight: bold;">
                        <span uk-icon="icon: tv"></span>
                        <span></span>
                     </span>
                  </div>
               </div>
            </div>
            <div>
               <div class="uk-margin">
                  <div uk-form-custom="target: > * > span:last-child" style="background-color: #c6c6c6; padding: 5px 15px; margin-right: 0.5px;">
                     <select>
                        <option value="1">Español</option>
                        <option value="2">Inglés</option>
                     </select>
                     <span class="uk-link" style="color: #6E6E6E; font-weight: bold;">
                        <span uk-icon="icon: world"></span>
                        <span></span>
                     </span>
                  </div>

                  <div uk-form-custom="target: > * > span:last-child" style="background-color: #c6c6c6; padding: 5px 15px;">
                     <select>
                        <option value="1">USD</option>
                        <option value="2">COP</option>
                     </select>
                     <span class="uk-link" style="color: #6E6E6E; font-weight: bold;">
                        <i class="fas fa-coins"></i>
                        <span></span>
                     </span>
                  </div>
               </div>
            </div>
         </div>--}}

        
         {{--<div class="uk-margin"> 
            <span style="color: white; font-size: 18px;">Idioma y Moneda</span><br>
            <div uk-form-custom="target: > * > span:last-child" style="background-color: #c6c6c6; padding: 5px 15px; margin-right: 0px;">
               <select>
                  <option value="1">Español</option>
                  <option value="2">Inglés</option>
               </select>
               <span class="uk-link" style="color: #6E6E6E; font-weight: bold;">
                  <span uk-icon="icon: world"></span>
                  <span></span>
               </span>
            </div>

            <div uk-form-custom="target: > * > span:last-child" style="background-color: #c6c6c6; padding: 5px 15px; margin-left: 0px;">
               <select>
                  <option value="1">COP</option>
                  <option value="2">USD</option>
               </select>
               <span class="uk-link" style="color: #6E6E6E; font-weight: bold;">
                  <i class="fas fa-coins"></i>
                  <span></span>
               </span>
            </div>
         </div>--}}

         <!-- Rounded switch -->
         <div style="display: inline-flex; align-items: center;">
            <label for=""><i class="fas fa-sun" style="font-size: 22px; margin-right: 5px;"></i></label>
            <label class="switch">
              <input type="checkbox"  onclick="cambiarTema();" id="theme_check">
              <span class="slider2 round"></span>
            </label>
            <label><i class="fas fa-moon" style="font-size: 22px; margin-left: 5px;"></i></label>
         </div> 
      
         <div >
            <img src="{{ asset('template/images/loto-contorno.png')}}" style="width: 15%;">
            <img src="{{ asset('template/images/logopngblanco.png')}}" style="width: 70%;">
         </div>

         <div class="d-inline-flex">
            <style>
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
               .social-network a.icoLinkedin:hover {
                  background-color:#0B94FF;
               }
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
            </style>
                  
            <ul class="social-network social-circle d-flex">
               <li><a href="https://www.facebook.com/TransformatePro/" target="_blank" class="icoLinkedin" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
               <li><a href="https://www.facebook.com/TransformatePro/" target="_blank" class="icoFacebook" title="Facebook"><i class="fab fa-facebook"></i></a></li>
               <li><a href="https://www.instagram.com/transformatepro/?hl=es-la" class="icoInstagram"  target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
               <li><a href="https://www.youtube.com/channel/UCdr-Ig698cKvfiKtRTfxf0A" class="icoYoutube" target="_blank" title="Youtube"><i class="fab fa-youtube"></i></a></li>
            </ul>
         </div>

         <div class="uk-text-white">
            &copy; Transfórmatepro 2020 Todos los derechos reservados<br>
            Políticas de privacidad.
         </div>
      </div>
   </div>
</footer>
