@extends('layouts.student2')

@push('styles')
    <style>
        .hide{
            display: none;
        }
        .text-title{
            font-weight: 900; 
            font-size: 90px;
        }
        .background-title{
            background: #1a4c91;
        }
        .button-gold{
            background: #ffbf00;
            color: black;
        }
        .text-price{
            font-size: 3.5em;
            font-weight: 900;
            color: #1f6f97;   
        }
        .text-promotion-title{
            color: #1f6f97;   
            font-weight: 900;  
            font-size: 1.1em;    
        }
        .text-promotion-content{
            color: #3a3838;
            font-weight: 600;
            font-size: 13px;
        }
        .font-promotion-lotus{
            background: #1873a5;
        }
        .font-promotion-gold{
            background: #c6942b;
        }
        .font-gold{
            background: #f9de71;
            background: -moz-radial-gradient(center, ellipse cover, #f9de71 0%, #d5b217 42%, #cca700 53%, #a38524 99%, #f9d94e 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #f9de71), color-stop(42%, #d5b217), color-stop(53%, #cca700), color-stop(99%, #a38524), color-stop(100%, #f9d94e));
            background: -webkit-radial-gradient(center, ellipse cover, #f9de71 0%, #d5b217 42%, #cca700 53%, #a38524 99%, #f9d94e 100%);
            background: -o-radial-gradient(center, ellipse cover, #f9de71 0%, #d5b217 42%, #cca700 53%, #a38524 99%, #f9d94e 100%);
            background: -ms-radial-gradient(center, ellipse cover, #f9de71 0%, #d5b217 42%, #cca700 53%, #a38524 99%, #f9d94e 100%);
            background: radial-gradient(ellipse at center, #f9de71 0%, #d5b217 42%, #cca700 53%, #a38524 99%, #f9d94e 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f9de71', endColorstr='#f9d94e', GradientType=1 );
        }
        .font-silver{
            background: rgba(226,226,226,1);
            background: -moz-radial-gradient(center, ellipse cover, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 25%, rgba(209,209,209,1) 35%, rgba(153,148,153,1) 99%, rgba(152,147,152,1) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(226,226,226,1)), color-stop(25%, rgba(219,219,219,1)), color-stop(35%, rgba(209,209,209,1)), color-stop(99%, rgba(153,148,153,1)), color-stop(100%, rgba(152,147,152,1)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 25%, rgba(209,209,209,1) 35%, rgba(153,148,153,1) 99%, rgba(152,147,152,1) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 25%, rgba(209,209,209,1) 35%, rgba(153,148,153,1) 99%, rgba(152,147,152,1) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 25%, rgba(209,209,209,1) 35%, rgba(153,148,153,1) 99%, rgba(152,147,152,1) 100%);
            background: radial-gradient(ellipse at center, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 25%, rgba(209,209,209,1) 35%, rgba(153,148,153,1) 99%, rgba(152,147,152,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#989398', GradientType=1 );
        }
        .font-blue{
            background: rgba(179,220,237,1);
            background: -moz-radial-gradient(center, ellipse cover, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(82,137,227,1) 100%);
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(179,220,237,1)), color-stop(50%, rgba(41,184,229,1)), color-stop(100%, rgba(82,137,227,1)));
            background: -webkit-radial-gradient(center, ellipse cover, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(82,137,227,1) 100%);
            background: -o-radial-gradient(center, ellipse cover, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(82,137,227,1) 100%);
            background: -ms-radial-gradient(center, ellipse cover, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(82,137,227,1) 100%);
            background: radial-gradient(ellipse at center, rgba(179,220,237,1) 0%, rgba(41,184,229,1) 50%, rgba(82,137,227,1) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b3dced', endColorstr='#5289e3', GradientType=1 );
        }
        .padding-internal{
            padding: 30px 25px;
        }
        .title-lotus{
            background: #ffffff;
            color: #686669;
            width: 50%;
            border: 4px #3798dd solid;
            border-radius: 10%;
            text-align: center !important;
            position: absolute;
            margin-top: -25px;
            margin-left: 0;
            z-index: 1;
        }
        .title-blue{
            color: #2f6d95 !important;
            background: #ffffff;
            width: 50%;
            border: 4px #3798dd solid;
            border-radius: 10%;
            text-align: center !important;
            position: absolute;
            margin-top: -25px;
            margin-left: 0;
            z-index: 1;
        }
        .title-gold{
            color: #ba9d80 !important;
            background: #ffffff;
            width: 50%;
            border: 4px #3798dd solid;
            border-radius: 10%;
            text-align: center !important;
            position: absolute;
            margin-top: -25px;
            margin-left: 0;
            z-index: 1;
        }
        .message-lotus {
            color: #fff !important;
            background: #1a4c91;
            width: 23%;
            text-align: center !important;
            position: absolute;
            margin-top: -31px;
            font-size: 20px;
        }
        .safe-money{
            position: relative;
            margin-left: 12em;
        }
        
    </style>
@endpush

@push('scripts')
<script>
    $(function() {
        $('.billed-month').prop('checked', true);
        $('#content-billed-year').addClass('uk-hidden');

        $('.billed-month').click(function(){
            $('.billed-year').prop('checked', false);
            $('.billed-month').prop('checked', true);
            $('#content-billed-year').addClass('uk-hidden');
            $('#content-billed-month').removeClass('uk-hidden');
        });

        $('.billed-year').click(function(){
            $('.billed-month').prop('checked', false);            
            $('.billed-year').prop('checked', true);            
            $('#content-billed-month').addClass('uk-hidden');
            $('#content-billed-year').removeClass('uk-hidden');
        });

    });
</script>
@endpush

@section('content')
	@include('students.shoppingCart.stripeMethod')

	@if (Session::has('msj-exitoso'))
        <div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <strong>{{ Session::get('msj-exitoso') }}</strong>
        </div>
    @endif

    @if (Session::has('msj-erroneo'))
        <div class="uk-alert-danger" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            <strong>{{ Session::get('msj-erroneo') }}</strong>
        </div>
    @endif

    <div class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-body background-title">
                <p class="uk-text-large uk-text-white text-title"> T-MEMBERSHIP</p> 
            </div>
        </div>        
    </div>


    <div id="content-billed-month" class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <label><input class="uk-radio billed-month" type="radio"> Facturado por mes</label>            
                <label class="uk-margin-left"><input class="uk-radio billed-year" type="radio"> Facturado por año</label><br><br>
                <button class="uk-button font-gold uk-text-black"><i class="fa fa-shopping-cart" aria-hidden="true"></i>  T-Member Gold</button>
                <button class="uk-button uk-button-primary"><i class="fa fa-gift" aria-hidden="true"></i>  Comprar para regalo</button>
                <p>Ser un T Member PRO, es ser parte de una de las comunidades de Transformadores más grande a nivel global. 
                    Son muchos los beneficios a acceder a T MemberShip, son muchos:
                </p>
                <br>
                <br>
                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                    <div>
                        <div class="uk-card uk-card-primary uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-blue">T BLUE</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $19</p>
                                    <p class="text-promotion-title"> Facturado por Mes</p>
                                </div>
                                <br>
                                <i class="fa fa-tags text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás  eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a 1 pack = 2 cursos, de cursos gratuitos.</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">5% de descuento adicional en todos los cursos, renovable cada mes.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-blue uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T BLUE</span></button>
                        </div>     
                    </div>
                    <div>
                        <p class="uk-text-lead uk-text-bold uk-text-left message-lotus">Recomendado</p> 
                        <div class="uk-card font-promotion-lotus uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-lotus">T LOTUS</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $39</p>
                                    <p class="text-promotion-title"> Facturado por Mes</p>
                                </div>
                                <br>
                                <i class="fa fa-tags text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a 2 packs de cursos gratuitos. Cada mes!!!</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">15% de descuento adicional en todos los cursos, renovable cada mes.</span><br><br>                                                                 
                                <i class="fa fa-puzzle-piece fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i>  <span class="text-promotion-content uk-text-justify">Accederás a eventos TU EXPRO virtual y presencial a conferencias, eventos y webinars. Todo con un 20% de descuento del valor original.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-silver uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T LOTUS</span></button>
                        </div>                        
                    </div>
                    <div>
                        <p class="uk-text-lead uk-text-bold uk-text-left message-lotus">+ Más vendido</p> 
                        <div class="uk-card font-promotion-gold uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-gold">T GOLD</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $65</p>
                                    <p class="text-promotion-title"> Facturado por Mes</p>
                                </div>
                                <br>
                                <i class="fa fa-eye fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Perfil Gold. Esto significa mayor visibilización de tu Proyecto Transformador.</span><br><br>
                                <i class="fa fa-users fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Posibilidad de inversionistas ÁNGELES para tu proyecto transformador.</span><br><br>
                                <i class="fa fa-tags fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás  eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a   3 packs de cursos gratuitos. Cada mes!!!</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">30% de descuento adicional en todos los cursos, renovable cada mes.</span><br><br>                                                                 
                                <i class="fa fa-puzzle-piece fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accederás a eventos TU EXPRO virtual y presencial a conferencias, eventos y webinars. Todo con un 30% de descuento del valor original.</span><br><br>                                                                          
                                <i class="fa fa-male fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a ser mentor y recibir retribución por mentoría.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-gold uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T GOLD</span></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <br>     
            <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                <div></div>    
                <div>
                    <ul uk-accordion>
                        <li class="">
                            <a class="uk-accordion-title" href="#">¿Como se aplica el porcentaje de descuento ofrecido por la T MemberShip?</a>
                            <div class="uk-accordion-content">
                                <p>Accediendo a la T MemberShip en cada una de sus modalidades, tendrás la posibilidad de obtener descuentos adicionales según tu tipo 
                                de membresía en la compra de tus cursost.El descuento no se aplica en Packs ni en cursos comprados para regalar.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Cómo se renueva tu T MemberShip?</a>
                            <div class="uk-accordion-content">
                                <p>La suscripción a la T MemberShip en cada una de sus modaidades tiene una duración de un año y se renueva automáticamente. 
                                Antes de que termine el periodo de tu suscripción te enviaremos un recordatorio de renovación automática.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué son los Pack Gratuitos por mes?</a>
                            <div class="uk-accordion-content">
                                <p>Todos los meses encontrarás uno, dos o más fantásticos Packs disponibles para ti y podrás ver los vídeos de los cursos completos totalmente gratis durante ese mes.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un Perfil Gold?</a>
                            <div class="uk-accordion-content">
                                <p>Al obtener un perfil gold de forma preferencial podras destacarte en la T Community PRO de la plataforma Transformate 
                                esto te permitirá ser visible en un mayor grado con tus proyectos de impacto y transformadores. Lo que te permitirá atraer muchos aliados y seguidores.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un Inversionista Ángel?</a>
                            <div class="uk-accordion-content">
                                <p>Al obtener un perfil gold de forma preferencial podras acceder a posibles inversionistas ángeles que estén en interesados en el alcance e impacto de tu proyecto transformador.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un ser Mentor?</a>
                            <div class="uk-accordion-content">
                                <p>Un mentor en Transfórmate tiene la posiblidad de dar a conocer su talento mediante la creacion de T Courses T Books o T Mentoring en TransformatePRO.com. 
                                Siendo un T Member Gold podrás acceder de forma preferencial a nuestro equipo de apoyo quien te asesorá paso a paso en el desarrollo de tus potenciales creaciones.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div></div>
            </div>   
            <br>     
        </div>
    </div>

    <div id="content-billed-year" class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-body">
                <label><input class="uk-radio billed-month" type="radio"> Facturado por mes</label>            
                <label class="uk-margin-left"><input class="uk-radio billed-year" type="radio"> Facturado por año</label><br><br>
                <span class="safe-money"><b>AHORRA EL 50%</b></span><br>
                <button class="uk-button font-gold uk-text-black"><i class="fa fa-shopping-cart" aria-hidden="true"></i>  T-Member Gold</button>
                <button class="uk-button uk-button-primary"><i class="fa fa-gift" aria-hidden="true"></i>  Comprar para regalo</button>
                <p>Ser un T Member PRO, es ser parte de una de las comunidades de Transformadores más grande a nivel global. 
                    Son muchos los beneficios a acceder a T MemberShip, son muchos:
                </p>
                <br>
                <br>
                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                    <div>
                        <div class="uk-card uk-card-primary uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-blue">T BLUE</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $9.50</p>
                                    <div>
                                        <span class="text-promotion-title"> $114</span>
                                        <br>
                                        <span class="text-promotion-title"> Facturado por año</span>
                                    </div>
                                </div>
                                <br>
                                <i class="fa fa-tags text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás  eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a 1 pack = 2 cursos, de cursos gratuitos.</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">5% de descuento adicional en todos los cursos, renovable cada mes.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-blue uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T BLUE</span></button>
                        </div>     
                    </div>
                    <div>
                        <p class="uk-text-lead uk-text-bold uk-text-left message-lotus">Recomendado</p> 
                        <div class="uk-card font-promotion-lotus uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-lotus">T LOTUS</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $19.50</p>
                                    <div>
                                        <span class="text-promotion-title"> $234</span>
                                        <br>
                                        <span class="text-promotion-title"> Facturado por año</span>
                                    </div>
                                </div>
                                <br>
                                <i class="fa fa-tags text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a 2 packs de cursos gratuitos. Cada mes!!!</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">15% de descuento adicional en todos los cursos, renovable cada mes.</span><br><br>                                                                 
                                <i class="fa fa-puzzle-piece fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i>  <span class="text-promotion-content uk-text-justify">Accederás a eventos TU EXPRO virtual y presencial a conferencias, eventos y webinars. Todo con un 20% de descuento del valor original.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-silver uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T LOTUS</span></button>
                        </div>                        
                    </div>
                    <div>
                        <p class="uk-text-lead uk-text-bold uk-text-left message-lotus">+ Más vendido</p> 
                        <div class="uk-card font-promotion-gold uk-card-body padding-internal">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-gold">T GOLD</p> 
                            <div class="uk-card uk-card-default uk-card-body">
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <p class="uk-text-bold text-price"> $32.50</p><div>
                                        <span class="text-promotion-title"> $390</span>
                                        <br>
                                        <span class="text-promotion-title"> Facturado por año</span>
                                    </div>
                                </div>
                                <br>
                                <i class="fa fa-eye fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Perfil Gold. Esto significa mayor visibilización de tu Proyecto Transformador.</span><br><br>
                                <i class="fa fa-users fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Posibilidad de inversionistas ÁNGELES para tu proyecto transformador.</span><br><br>
                                <i class="fa fa-tags fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás  eventos, promociones, tips y tendencias.</span><br><br>
                                <i class="fa fa-gift fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a   3 packs de cursos gratuitos. Cada mes!!!</span><br><br>
                                <i class="fa fa-percent fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">30% de descuento adicional en todos los cursos, renovable cada mes.</span><br><br>                                                                 
                                <i class="fa fa-puzzle-piece fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accederás a eventos TU EXPRO virtual y presencial a conferencias, eventos y webinars. Todo con un 30% de descuento del valor original.</span><br><br>                                                                          
                                <i class="fa fa-male fa-lg text-promotion-title uk-margin-right" aria-hidden="true"></i><span class="text-promotion-content uk-text-justify">Accede a ser mentor y recibir retribución por mentoría.</span>                                                                                    
                            </div>
                        </div>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <button class="uk-button font-gold uk-button-large"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T GOLD</span></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <br>     
            <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                <div></div>    
                <div>
                    <ul uk-accordion>
                        <li class="">
                            <a class="uk-accordion-title" href="#">¿Como se aplica el porcentaje de descuento ofrecido por la T MemberShip?</a>
                            <div class="uk-accordion-content">
                                <p>Accediendo a la T MemberShip en cada una de sus modalidades, tendrás la posibilidad de obtener descuentos adicionales según tu tipo 
                                de membresía en la compra de tus cursost.El descuento no se aplica en Packs ni en cursos comprados para regalar.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Cómo se renueva tu T MemberShip?</a>
                            <div class="uk-accordion-content">
                                <p>La suscripción a la T MemberShip en cada una de sus modaidades tiene una duración de un año y se renueva automáticamente. 
                                Antes de que termine el periodo de tu suscripción te enviaremos un recordatorio de renovación automática.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué son los Pack Gratuitos por mes?</a>
                            <div class="uk-accordion-content">
                                <p>Todos los meses encontrarás uno, dos o más fantásticos Packs disponibles para ti y podrás ver los vídeos de los cursos completos totalmente gratis durante ese mes.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un Perfil Gold?</a>
                            <div class="uk-accordion-content">
                                <p>Al obtener un perfil gold de forma preferencial podras destacarte en la T Community PRO de la plataforma Transformate 
                                esto te permitirá ser visible en un mayor grado con tus proyectos de impacto y transformadores. Lo que te permitirá atraer muchos aliados y seguidores.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un Inversionista Ángel?</a>
                            <div class="uk-accordion-content">
                                <p>Al obtener un perfil gold de forma preferencial podras acceder a posibles inversionistas ángeles que estén en interesados en el alcance e impacto de tu proyecto transformador.</p>
                            </div>
                        </li>
                        <li>
                            <a class="uk-accordion-title" href="#">¿Qué es un ser Mentor?</a>
                            <div class="uk-accordion-content">
                                <p>Un mentor en Transfórmate tiene la posiblidad de dar a conocer su talento mediante la creacion de T Courses T Books o T Mentoring en TransformatePRO.com. 
                                Siendo un T Member Gold podrás acceder de forma preferencial a nuestro equipo de apoyo quien te asesorá paso a paso en el desarrollo de tus potenciales creaciones.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div></div>
            </div>   
            <br>     
        </div>
    </div>


	
@endsection