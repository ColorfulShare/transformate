<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Certificado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <style>
            @font-face {
                font-family: 'Savoye LET Plain';
                src: url('template/fonts/Savoye LET Plain.ttf');
            }
            html{
              margin: 0;
              min-height: 100%;
            }
            body{

                font-family: Helvetica;
            }
            .h3{
                padding-top: 10px;
                font-size: 25px;
                font-weight: normal;
            }
            h2{
                font-weight: bold;
            }
            .content{
                text-align: center;
            }
            .username{
                font-size: 60px;
                color: #0862A9;
                font-weight: normal;
            }
            .course{
                padding-top: 10px;
                padding-left: 25px;
                padding-right: 25px;
                font-size: 25px;
                color: #0094CB;
                font-weight: normal;
            }
            .banner-inferior{
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 40px;
            }
            .duration{
                font-size: 18px;
                /*color: #A1A1A1;*/
                padding-top: 5px;
                font-style: oblique;
            }
            .date{
                text-align: left;
                padding-left: 100px;
                padding-top: 25px;
                /*color: #A1A1A1;*/
                font-size: 18px;
                font-style: oblique;
            }
            .firms{
                padding-left: 230px;
                padding-top: 20px;
            }
            .ceo{
                width: 50%; 
                float: left;
                text-align: center;
                padding-top: 20px;
            }
            .mentor{
                width: 50%; 
                float: right;
                text-align: center;
                padding-top: 20px;
            }
            .mentor-name{
                font-family:'Savoye LET Plain';
                font-weight:normal;
                font-size:42px;
            }
        </style>
    </head>
    <body>
        <div class="banner-superior">
            <img src="template/images/banner_superior_certificado.png" width="100%">
        </div>

        <div class="content">
           
            <div>
                <span class="h3">En nombre del Instituto de Transformación Social y Sostenible</span>

                <h2>CERTIFICA A</h2>
            </div>

            <div class="username">{{ $alumno }}</div>
            
            <div class="h3"> Cumpliendo los requisitos del T-Curso en la plataforma Online exitosamente, <br>se le otorga con derechos de certificado de:</div>

            <div class="course">{{ $curso->title }}</div>

            <div class="duration">Con una intensidad de <b>3</b> horas</div>
        </div>

        <div>
            
        </div>
        <div class="date">
            FECHA: {{ $fecha_fin }}
        </div>

        <div class="banner-inferior">
            <img src="template/images/banner_inferior_certificado.png" width="100%">
        </div>

        <div class="ceo">
           <img src="template/images/firma_sty.png" width="30%"><br>
           <span><b>Stybaliz Castellanos Giovanini</b></span><br>
           <span><b>CEO FUNDADOR</b></span>
        </div>

        <div class="mentor">
            <span class="mentor-name">{{ $mentor }}</span><br>
           <span><b>MENTOR</b></span>
        </div>

       
    </body>
</html>