@extends('layouts.coursesLanding')

@push('styles')
<style>
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
    .font-promotion-gold{
        background: #c6942b;
        padding: 25px 10px 25px 10px;
    }
    .title-gold{
        color: #ba9d80 !important;
        background: #ffffff;
        width: 50%;
        border: 4px #3798dd solid;
        border-radius: 10%;
        text-align: center !important;
        top: 0px !important;
        z-index: 1;
        font-size: 0.9em;
    }
    .text-price{
        font-size: 3.5em;
        font-weight: 900;
        color: #1f6f97;  
        text-align: end; 
    }
    .text-promotion-title{
        color: #1f6f97;   
        font-weight: 900;  
        font-size: 1.5em;    
        text-align: initial;
        width: auto;
    }
    .btn-courses{
        background: #00568c;
        color: #fff;
        margin: 0 10px 0 100px;
    }
    .btn-route{
        background: #1e87f0;
        color: #00568c;
    }
    .btn-books{
        background: #804a84;
        color: #fff;
        margin: 0 100px 0 10px;
    }
    .text-promotion-content{
        width: 85%;
        font-size: 0.8em;
    }
    .width-content{
        width: 105%;
    }
    .li-acordeon-1{
        background: #d6d6d6;
        padding: 20px 0 20px;
    }
    .li-acordeon-2{
        background: #f1f1f1;
        padding: 20px 0 20px;
    }
    .padding-card{
        position: absolute;
        top: 23px;
    }
    .p-card{
        padding: 0 30px 0 30px;
    }
    .info-text{
        font-size: 1.2em;
    }
    .w-button{
        width: 46%;
        font-size: x-large;
    }
    .uk-accordion-title::after{
        border: 1px solid;
        margin-right: 10px;
    }
    .b-gold{
        color: #565553;
    }

    @media screen and ( device-width: 1024px ) {
        .p-card {
            padding: 0 20px 0 10px;
        }
        .text-promotion-title {
            color: #1f6f97;
            font-weight: 900;
            font-size: 1.2em;
            text-align: initial;
            width: auto;
        }
        .text-price {
            font-size: 3em;
            font-weight: 900;
            color: #1f6f97;
            text-align: end;
        }
    }
</style>
@endpush

@section('content')

    <div class="col-12 text-right pr-5">
        <a class="btn btn-off-radiu bg-color-light-tr text-white font-weight-bold mr-lg-2 mb-4" href="javascript:;" data-toggle="modal" data-target="#modal-login">
            Entrar    
        </a>
        <a class="btn btn-off-radiu bg-color-dark-tr text-white font-weight-bold ml-lg-2 mb-4" href="javascript:;" data-toggle="modal" data-target="#modal-register">
            Crea Tu Cuenta Gratis 
        </a>
    </div>
    <div class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-secondary uk-card-body background-title">
                <p class="uk-text-large uk-text-white text-title"> T-MEMBERPRO</p> 
            </div>
        </div>        
    </div>

    <div class="uk-child-width-expand@s uk-text-center" uk-grid>
        <div>
            <div class="uk-card uk-card-default content-carousel">
                <div class="uk-child-width-1-2@s uk-text-center" uk-grid>
                    <div class="uk-width-2-3">
                        <div class="uk-child-width-1-2@s uk-text-center" uk-grid>
                                <button class="uk-button font-gold uk-text-black w-button b-gold"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i>  T-MEMBER PRO</button>
                                <button class="uk-button uk-button-primary w-button" style="margin-left: 3%"><i class="fa fa-gift" aria-hidden="true"></i>  Comprar para regalo</button>
                        </div>                      
                        <br>
                        <div class="uk-child-width-1-2@s" uk-grid>
                            <div class="uk-width-1-5">
                                <img class="" src="https://www.transformatepro.com/template/images/web2-20.png" style="width: 60%">
                            </div> 
                            <div class="uk-width-4-5">
                                <p class="uk-text-justify info-text"> Ser un T Member PRO, es ser parte de una de las comunidades de Transformadores más grande a nivel global. 
                                    Son muchos los beneficios a acceder a T Member PRO, son muchos.
                                </p>
                            </div>
                        </div>
                        <br>
                        <p class="uk-text-large uk-text-bold uk-text-left">Preguntas Frecuentes</p>
                        <br>
                        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                            <div >
                                <ul uk-accordion>
                                    <li class="li-acordeon-1">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Como se aplica el porcentaje de descuento ofrecido por la T Member PRO?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">Accediendo a la T MemberPRO en cada una de sus modalidades, tendrás la posibilidad de obtener descuentos adicionales según tu tipo 
                                            de membresía en la compra de tus cursost.El descuento no se aplica en Packs ni en cursos comprados para regalar.</p>
                                        </div>
                                    </li>
                                    <li class="li-acordeon-2">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Cómo se renueva tu T Member PRO?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">La suscripción a la T MemberPRO en cada una de sus modaidades tiene una duración de un año y se renueva automáticamente. 
                                            Antes de que termine el periodo de tu suscripción te enviaremos un recordatorio de renovación automática.</p>
                                        </div>
                                    </li>
                                    <li class="li-acordeon-1">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Qué son los Pack Gratuitos por mes?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">Todos los meses encontrarás uno, dos o más fantásticos Packs disponibles para ti y podrás ver los vídeos de los cursos completos totalmente gratis durante ese mes.</p>
                                        </div>
                                    </li>
                                    <li class="li-acordeon-2">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Te han regalado una T Member PRO en Transformate PRO?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">La suscripción a la T MemberPRO en cada una de sus modaidades tiene una duración de un año y se renueva automáticamente. 
                                            Antes de que termine el periodo de tu suscripción te enviaremos un recordatorio de renovación automática.</p>
                                        </div>
                                    </li>
                                  
                                    <li class="li-acordeon-1">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Qué es un Perfil Gold?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">Al obtener un perfil gold de forma preferencial podras destacarte en la T Community PRO de la plataforma Transformate 
                                            esto te permitirá ser visible en un mayor grado con tus proyectos de impacto y transformadores. Lo que te permitirá atraer muchos aliados y seguidores.</p>
                                        </div>
                                    </li>
                                    <li class="li-acordeon-2"> 
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Qué es un Inversionista Ángel?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">Al obtener un perfil gold de forma preferencial podras acceder a posibles inversionistas ángeles que estén en interesados en el alcance e impacto de tu proyecto transformador.</p>
                                        </div>
                                    </li>
                                    <li class="li-acordeon-1">
                                        <a class="uk-accordion-title uk-text-bold uk-text-left uk-margin-left" href="#">¿Qué es un ser Mentor?</a>
                                        <div class="uk-accordion-content">
                                            <p class="uk-text-bold uk-text-left uk-margin-left">Un mentor en Transfórmate tiene la posiblidad de dar a conocer su talento mediante la creacion de T Courses T Books o T Mentoring en TransformatePRO.com. 
                                            Siendo un T Member Gold podrás acceder de forma preferencial a nuestro equipo de apoyo quien te asesorá paso a paso en el desarrollo de tus potenciales creaciones.</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>     
                    </div>
                    <div class="uk-width-1-3"> 
                        <div class="uk-card font-promotion-gold uk-card-body padding-card">
                            <p class="uk-text-lead uk-text-bold uk-text-left title-gold uk-position-center">T MEMBER PRO</p> 
                                <div class="uk-card uk-card-default p-card">
                                    <div class="uk-child-width-expand@s uk-text-center " uk-grid>
                                        <p class="uk-text-bold text-price"> $99</p>
                                        <p class="text-promotion-title"> Facturado por Año</p>
                                    </div>
                                    <br>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid >
                                        <i class="fa fa-eye fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify uk-width-2-3 ">Perfil Gold. Esto significa mayor visibilización de tu Proyecto Transformador.</span>
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-users fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Posibilidad de inversionistas ÁNGELES para tu proyecto transformador.</span>
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-tags fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Recibe el Newsletter y encontrarás  eventos, promociones, tips y tendencias.</span>
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>                                    
                                        <i class="fa fa-gift fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Accede a TRIPACK de cursos gratuitos. Cada mes!!!</span>
                                    </div>                                
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-percent fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">10% de descuento adicional en todos los cursos, renovable cada mes.</span>                                                            
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-puzzle-piece fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Accederás a eventos TU EXPRO virtual y presencial a conferencias, eventos y webinars. Todo con un 20% de descuento del valor original.</span>                                                                       
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-male fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Accede a ser mentor y recibir retribución por mentoría.</span>                                                                                    
                                    </div>
                                    <div class="uk-child-width-expand@s uk-text-center uk-child-width-1-2@s width-content uk-margin-small-top" uk-grid>
                                        <i class="fa fa-male fa-lg text-promotion-title uk-width-1-3" aria-hidden="true"></i>
                                        <span class="text-promotion-content uk-text-justify">Podrás exhibir y comercializar tus productos y servicios en nuestro T MarketPlace.</span>                                                                                    
                                    </div>
                                </div>
                                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                                    <button class="uk-button font-gold uk-button-large b-gold"><i class="fa fa-shopping-cart uk-margin-right" aria-hidden="true"></i> <span>Comprar T GOLD</span></button>
                                </div>                      
                            </div>
                        </div>
                    </div>                 
                </div>
            </div>                   
        </div>
    </div>
    <br>
    <br>
    <br>
    <div class="uk-child-width-1@s uk-grid">
        <div class="uk-child-width-expand@s uk-text-center" uk-grid>
            <a class="uk-button uk-button-large btn-courses" href="{{ route('landing.courses') }}" >  Ver más T Course</a>
            <a class="uk-button uk-button-large btn-route" href="" >  ¿Como Crear tu Propia Ruta de Transformación?</a>
            <a class="uk-button uk-button-large btn-books" href="{{ route('landing.podcasts') }}" >  Ver más T Book</a>
        </div>
    </div>
    <div class="uk-clearfix boundary-align"> 
        <div class="uk-float-left section-heading none-border"> 
            <h2 class="">Cursos Destacados</h2> 
        </div> 
    </div>  

    <div uk-slider class="content-carousel">
        <div class="uk-position-relative">
            <div class="uk-slider-container uk-light">
                <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
                    @foreach ($courses as $course)                            
                    <li class="card-course">
                        <div class="uk-card uk-card-small uk-card-default">
                            <div class="uk-card-media-top">
                                <img src="{{ asset('uploads/images/courses/'.$course->cover) }}" class="content-image">
                            </div>
                            <div class="uk-card-body">
                                <a href="{{ route('landing.courses.show', [$course->slug, $course->id]) }}">
                                    <h3 class="uk-card-title">{{ $course->title }}</h3>
                                </a>
                                <p class="tile2">Mentor: {{ $course->user->names.' '.$course->user->last_names }}</p>
                                <p class="tile4">{{ strip_tags($course->review) }}</p>                                            
                                <p class="tile5"><span class="desc">{{ $course->price*1.50 }} COP</span><br>                                
                                    <a class="uk-button button-sc" href="{{ route('landing.shopping-cart.store', [$course->id, 'curso']) }}"><i class="fas fa-cart-plus icon-large"></i>  {{ $course->price }} COP</a>
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>              
            </div>
            <div class="uk-hidden@s uk-light">
                <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>
            </div>
            <div class="uk-visible@s">
                <a class="uk-position-center-left-out uk-position-small uk-text-secondary" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
                <a class="uk-position-center-right-out uk-position-small uk-text-secondary" href="#" uk-slidenav-next uk-slider-item="next"></a>
            </div> 
            <ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
        </div>
    </div>
@endsection