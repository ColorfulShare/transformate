@extends('layouts.landing')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/css/t_member.css') }}">
@endpush

@push('scripts')
    <script>
        function cambiarTab($tab){
            if ($tab == 't-member'){
                document.getElementById('t-mentor-section').style.display = 'none';
                document.getElementById('t-member-section').style.display = 'block';
                $("#button-t-mentor").removeClass("tab-t-mentor");
                $("#button-t-mentor").addClass("tab-t-member");
                $("#button-t-member").removeClass("tab-t-member");
                $("#button-t-member").addClass("tab-t-mentor");
                document.getElementById("link-show-more").href = 'https://www.transformatepro.com/documents/t member pro_transformatepro180520.pdf';
            }else{
                document.getElementById('t-member-section').style.display = 'none';
                document.getElementById('t-mentor-section').style.display = 'block';
                $("#button-t-member").removeClass("tab-t-mentor");
                $("#button-t-member").addClass("tab-t-member");
                $("#button-t-mentor").removeClass("tab-t-member");
                $("#button-t-mentor").addClass("tab-t-mentor");
                document.getElementById("link-show-more").href = 'https://www.transformatepro.com/documents/t MENTOR pro_TRANSFORMATE_180520.pdf';
            }
            
        }
    </script>
@endpush

@section('content')
    <div class="background-ligth2" id="t-member">
        <div class="uk-child-width-1-2" uk-grid>
            <div class="uk-text-right">
                <button class="uk-button uk-button-default tab-t-mentor" type="button" onclick="cambiarTab('t-mentor');" id="button-t-mentor">T-Mentor PRO</button>
            </div>
            <div class="uk-text-left">
                <button class="uk-button uk-button-default tab-t-member" type="button" onclick="cambiarTab('t-member');" id="button-t-member">T-Member PRO</button>
            </div>
        </div>

        <div class="background-ligth uk-text-center" id="t-member-tabs">
            <div id="t-mentor-section">
                <div class="t-member-text color-ligth2" id="t-member-text">
                    Si eres Mentor o Potencial Mentor de TransfórmatePRO, podrás acceder a una<br> MEMBRESÍA PRO DE COP$ {{ number_format($membresia->price, 0, ',', '.') }} o USD$ 29 ANUALES, con la que obtendrás los siguientes beneficios.
                </div>

                <div class="uk-child-width-1-3@m uk-child-width-1-1@s t-member-items" uk-grid>
                    <div class="uk-text-center color-ligth2" id="t-member-item-1">
                        <img src="{{ asset('template/images/Grupo_277.png') }}" class="img-item">
                        <div class="text-item">
                            Un super descuento en el valor de todos los T courses, Mentorings y T Books que curses durante todo el año en nuestra Plataforma.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-2">
                        <img src="{{ asset('template/images/ico8.png') }}" class="img-item">
                        <div class="text-item">
                            Obtendrás un perfil Gold T-Mentor, con el cuál podrás tener mayor visibilidad de tus cursos en la platafotma TransfórmatePRO.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-3">
                        <img src="{{ asset('template/images/Grupo_226.png') }}" class="img-item">
                        <div class="text-item">
                           Un super descuento del 30% en cualquiera de los servicios de nuestra Productora Audiovisual Transformatepro.
                        </div>
                    </div>
                </div>
                <div class="uk-child-width-1-2@m uk-child-width-1-1@s t-member-items-2" uk-grid>
                    <div class="uk-text-center color-ligth2" id="t-member-item-4">
                        <img src="{{ asset('template/images/Grupo_238.png') }}" class="img-item">
                        <div class="text-item">
                            Podrás exhibir y comercializar tus productos y servicios en la T Marketplace.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-5">
                        <img src="{{ asset('template/images/Grupo_218.png') }}" class="img-item">
                        <div class="text-item">
                            Un super descuento del 30% en el valor de todos los T Events (Congresos y Mentorías en directo) que realizamos durante todo el año.
                        </div>
                    </div>
                </div>

                <div class="color-ligth2" id="t-member-observation">
                    <b>SERVICIOS PRODUCTORA TRANSFÓRMATE</b><br>
                    CON LA T-MEMBER DE COP$ {{ number_format($membresia->price, 0, ',', '.') }} o USD$ 29 ANUALES, PODRÁS ACCEDER A CUALQUIER SERVICIO CON UN 30% DE DESCUENTO.
                </div>
            </div>

            <div id="t-member-section" style="display: none;">
                <div class="t-member-text color-ligth2" id="t-member-text2">
                    Si eres Miembro o Potencial Miembro de TransfórmatePRO, podrás acceder a una<br> MEMBRESÍA PRO DE COP$ {{ number_format($membresia->price, 0, ',', '.') }} o USD$ 29 ANUALES, con la que obtendrás los siguientes beneficios.
                </div>

                <div class="uk-child-width-1-3@m uk-child-width-1-1@s t-member-items" uk-grid>
                    <div class="uk-text-center color-ligth2" id="t-member-item-21">
                        <img src="{{ asset('template/images/Grupo_218.png') }}" class="img-item">
                        <div class="text-item">
                            Un super descuento del 30% en el valor de todos los T Events (Congresos y Mentorías en directo) que realizamos durante todo el año.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-22">
                        <img src="{{ asset('template/images/Grupo_277.png') }}" class="img-item">
                        <div class="text-item">
                            Un super descuento en el valor de todos los T courses, Mentorings y T Books que curses durante todo el año en nuestra Plataforma.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-23">
                        <img src="{{ asset('template/images/Grupo_226.png') }}" class="img-item">
                        <div class="text-item">
                           Un super descuento del 30% en cualquiera de los servicios de nuestra Productora Audiovisual Transformatepro.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-24">
                        <img src="{{ asset('template/images/Grupo_228.png') }}" class="img-item">
                        <div class="text-item">
                            Podrás ser Mentor de TransfórmatePRO, si tienes la vocación y pasión para crear un curso online que inspire y transforme vidas.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-25">
                        <img src="{{ asset('template/images/Grupo_238.png') }}" class="img-item">
                        <div class="text-item">
                            Podrás exhibir y comercializar tus productos y servicios en la T Marketllace.
                        </div>
                    </div>
                    <div class="uk-text-center color-ligth2" id="t-member-item-26">
                        <img src="{{ asset('template/images/Grupo_243.png') }}" class="img-item">
                        <div class="text-item">
                            Recibirás nuestro Newsletter con eventos, promociones, tips y tendencias.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="uk-child-width-1-2@m uk-child-width-1-1@s t-member-buttons" uk-grid>
                <div class="uk-width-1-1@s uk-width-1-2@m show-more-div" style="margin-bottom: 10px;">
                    <button class="button-transformate btn-buy-course">
                        <a class="no-link" href="https://www.transformatepro.com/documents/productora-transformate-def.pdf" target="_blank" id="link-show-more"> Ver Más Información</a>
                    </button>
                </div>
                <div class="uk-width-1-1@s uk-width-1-2@m buy-course-div" style="margin-top: 0px;">
                    <button class="button-transformate btn-show-more">
                        <a class="no-link" href="{{ route('landing.shopping-cart.store', [1, 'membresia']) }}">  Adquirir Membresía</a>
                    </button>
                </div>
            </div>

            <div class="uk-child-width-1-3@m uk-child-width-1-1@s three-pack-div" uk-grid>
                <div class="three-pack-item">
                    <div class="three-pack-header" style="background-color: #47CCED;">
                        <span>Asesorías</span>
                    </div>
                    <div>
                        <ul class="uk-list">
                            <li class="background-ligth2 list-item" id="list-item11">
                                <span class="color-ligth2" id="list-item-span11">Asesoría Virtual Creación Proyecto (emprendimiento, negocios, digital)</span>
                            </li>
                            <li class="list-item" id="list-item12">
                                <span class="color-ligth2" id="list-item-span12">Asesoría Virtual en la creación del contenido de tu curso</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item13">
                                <span class="color-ligth2" id="list-item-span13">Asesoría Virtual en la creación del contenido mentoría</span>
                            </li>
                            <li class="list-item" id="list-item14">
                                <span class="color-ligth2" id="list-item-span14">Asesoría Virtual Grabación de tu curso online (3 horas)</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item15">
                                <span class="color-ligth2" id="list-item-span15">Asesoría Virtual Grabación de mentoría</span>
                            </li>
                            <li class="list-item" id="list-item16">
                                <span class="color-ligth2" id="list-item-span16">Asesoría Virtual Grabación de tu Podcast</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item17">
                                <span class="color-ligth2" id="list-item-span17">Asesoría Virtual Grabación de tu audiolibro</span>
                            </li>
                            <li class="list-item" id="list-item18">
                                <span class="color-ligth2" id="list-item-span18">Asesoría Virtual en Storytelling y Marketing de Contenidos</span>
                            </li>
                        </ul>
                    </div>
                </div>

               <div class="three-pack-item">
                    <div class="three-pack-header" style="background-color: #0B3AEC;">
                        <span>Producción <br> Audiovisual</span>
                    </div>
                    <div>
                        <ul class="uk-list">
                            <li class="background-ligth2 list-item" id="list-item21">
                                <span class="color-ligth2" id="list-item-span21">Grabación de tu curso</span>
                            </li>
                            <li class="list-item" id="list-item22">
                                <span class="color-ligth2" id="list-item-span22">Grabación de tu mentoría</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item23">
                                <span class="color-ligth2" id="list-item-span23">Grabación de tu podcast</span>
                            </li>
                            <li class="list-item" id="list-item24">
                                <span class="color-ligth2" id="list-item-span24">Grabación de tu audiolibro</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item25">
                                <span class="color-ligth2" id="list-item-span25">Edición Curso Online</span>
                            </li>
                            <li class="list-item" id="list-item26">
                                <span class="color-ligth2" id="list-item-span26">Edición de tu Podcast</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item27">
                                <span class="color-ligth2" id="list-item-span27">Edición de tu audiolibro</span>
                            </li>
                            <li class="list-item" id="list-item28">
                                <span class="color-ligth2" id="list-item-span28">Grabación de Video Trailer</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="three-pack-item">
                    <div class="three-pack-header" style="background-color: #D40BEC;">
                        <span>Marketing de<br> Contenido y Emociones</span>
                    </div>
                    <div>
                        <ul class="uk-list">
                            <li class="background-ligth2 list-item" id="list-item31">
                                <span class="color-ligth2" id="list-item-span31">Creación y diseño de piezas gráficas</span>
                            </li>
                            <li class="list-item" id="list-item32">
                                <span class="color-ligth2" id="list-item-span32">Creación Logo</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item33">
                                <span class="color-ligth2" id="list-item-span33">Diseño y diagramación ebook</span>
                            </li>
                            <li class="list-item" id="list-item34">
                                <span class="color-ligth2" id="list-item-span34">Ilustración ebook</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item35">
                                <span class="color-ligth2" id="list-item-span35">Corrección estilo ebook</span>
                            </li>
                            <li class="list-item" id="list-item36">
                                <span class="color-ligth2" id="list-item-span36">Traducción ebook de Español - Inglés, Inglés - Español</span>
                            </li>
                            <li class="background-ligth2 list-item" id="list-item37">
                                <span class="color-ligth2" id="list-item-span37">Landing Page con Dominio</span>
                            </li>
                            <li class="list-item" id="list-item38">
                                <span class="color-ligth2" id="list-item-span38">Blog Personal</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
