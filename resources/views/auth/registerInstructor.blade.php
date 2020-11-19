<div id="modal-register-instructor" uk-modal>
    <div class="uk-modal-dialog uk-margin-auto-vertical">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <div class="uk-modal-body" style="padding: 0px 0px;">
            <div class=" uk-child-width-1-1" uk-grid>
                <div style="padding: 0 0;">
                    <div class="uk-width-1-1 uk-text-center slogan-register">
                        <img src="{{ asset('template/images/logo3.png') }}" style="width: 60%;"><br>
                        <div style="padding-top: 15px; font-size: 20px; color: gray; font-weight: bold;"> Construye con los mejores tu ruta de transformación.</div>
                    </div>
                    <div class="uk-width-1-1 email-login">
                        <form class="uk-form-stacked" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="instructor" value="1">
                            <div class="uk-margin">
                                <label class="uk-form-label" for="names"><b>Nombres (*)</b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input class="uk-input" type="text" name="names" placeholder="Nombres" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="last_names"><b>Apellidos (*)</b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input class="uk-input" type="text" name="last_names" placeholder="Apellidos" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="email"><b>Correo Electrónico (*)</b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                    <input class="uk-input" type="email" name="email" placeholder="Correo Electrónico" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="password"><b>Contraseña (*)</b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                    <input class="uk-input" type="password" name="password" placeholder="Contraseña" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="code"><b>Código de Mentor Referido</b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: code"></span>
                                    <input class="uk-input" type="text" name="code" placeholder="Código de Mentor Referido">
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="curriculum"><b>Hoja de Vida (*)  <i class="fa fa-info-circle" uk-tooltip="title: Envíanos tu hoja de vida para contactarte; pos: top-left"></i></b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: link"></span>
                                    <input class="uk-input" type="file" name="curriculum" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="video_presentation"><b>Video de Presentación (Máximo 3 Minutos) (*) <i class="fa fa-info-circle" uk-tooltip="title: Envíanos una breve presentación tuya y de tu curso en formato video; pos: top-left"></i></b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon:video-camera"></span>
                                    <input class="uk-input" type="file" name="video_presentation" required>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <label class="uk-form-label" for="video_presentation"><b>Reseña de tu Curso (Máximo 200 palabras) (*) <i class="fa fa-info-circle" uk-tooltip="title: Envíanos una breve reseña del curso que quieres crear con nosotros; pos: top-left"></i></b></label>
                                <div class="uk-inline uk-width-1-1">
                                    <textarea class="uk-textarea" name="course_review" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1 uk-text-center">
                                    {!! htmlFormSnippet() !!}
                                </div>
                            </div>
                            <div class="uk-margin uk-text-center">
                                <input type="submit" class="login-button" value="Crear Cuenta" id="btn-crear">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>