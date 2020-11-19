@extends('layouts.instructor')

@section('content')
    <div class="uk-container uk-margin-medium-top" uk-scrollspy="target: > div; cls:uk-animation-slide-bottom-medium; delay: 200">  
        <div class="uk-clearfix boundary-align"> 
            <div class="uk-clearfix boundary-align"> 
                <div class="section-heading none-border coco text-center"> 
                    <h3><i>Sigue tu ruta de Transformación</i></h3> 
                </div> 
            </div>                     
        </div>

        <div class="uk-grid" style="margin-top: 25px;">
            <div class="uk-width-1-2@m uk-width-1-1@s uk-text-center" style="padding: 30px; ">
                <a href="{{ route('instructors.courses.create') }}">
                    <div class="uk-card uk-card-hover uk-card-body" style="border: solid black 1px; border-radius: 20px;">
                        <h3><i>Paso 1<br> Crea tu curso</i></h3>
                        <img src="https://www.transformatepro.com/template/images/crea_tu_curso.png" style="width: 400px; height: 300px;">
                    </div>
                </a>
            </div>

            <div class="uk-width-1-2@m uk-width-1-1@s uk-text-center" style="padding: 30px;">
                <a href="">
                    <div class="uk-card uk-card-hover uk-card-body" style="border: solid black 1px; border-radius: 20px;">
                        <h3><i>Paso 2<br> Graba tu curso</i></h3>
                        <img src="https://www.transformatepro.com/template/images/graba_tu_curso.png" style="width: 400px; height: 300px;">
                    </div> 
                </a>
            </div>
            <div class="uk-width-1-2@m uk-width-1-1@s uk-text-center" style="padding: 30px;">
                <a href="">
                    <div class="uk-card uk-card-hover uk-card-body" style="border: solid black 1px; border-radius: 20px;">
                        <h3><i>Paso 3<br> Dale valor a tu comunidad</i></h3>
                        <img src="https://www.transformatepro.com/template/images/dale_valor_a_tu_comunidad.png" style="width: 400px; height: 300px;">
                    </div>
                </a>
            </div>
            <div class="uk-width-1-2@m uk-width-1-1@s uk-text-center" style="padding: 30px;">
                <a href="">
                    <div class="uk-card uk-card-hover uk-card-body" style="border: solid black 1px; border-radius: 20px;">
                        <h3><i>Paso 4<br> Lineamientos y acuerdos</i></h3>
                        <img src="https://www.transformatepro.com/template/images/lineamientos_y_acuerdos.png" style="width: 400px; height: 300px;">
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection