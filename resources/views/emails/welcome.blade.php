<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transfórmate</title>
</head>
<body>
	<p>
		Estimado <b>{{ $data['usuario'] }}</b> reciba un cordial saludo. Le damos una cálida bienvenida a la Comunidad TramsformatePRO, una comunidad de Mentores al servicio de tu Tramsformación. <br><br>
		
		Hemos recibido con éxito su registro a nuestra Plataforma de educación para la Transformación.<br><br>
		
		A partir de hoy, iniciaremos y cocrearemos juntos una Ruta de Transformación, en la que podrás potenciar tu poder creador y de esta forma Inspirarte para Transformar-T.<br><br>
		
        Te deseamos mucho gozo y plenitud en este nuevo camino.<br><br>

        @if ($data['tipo_usuario'] == 2)
            Debe esperar que los administradores verifiquen su información y aprueben su ingreso total a la plataforma TransfórmatePRO.<br>
            En pocos días le estaremos notificando la respuesta de su solicitud.<br><br>
        @else 
            @if (!is_null($data['token']))
        	   <span style="color: red;">PARA FINALIZAR EL PROCESO DE REGISTRO Y PUEDAS INGRESAR A LA PLATAFORMA, POR FAVOR VERIFICA TU CORREO ELECTRÓNICO EN EL SIGUIENTE ENLACE <a href="{{ route('landing.verify-email', [$data['id_usuario'], $data['token']]) }}">VERIFICAR MI CORREO ELECTRÓNICO</a></span><br><br>
            @endif
        @endif

        Por favor guarde los datos de su registro para futuros inicios de sesión en la plataforma:<br>
		Correo: <b>{{ $data['correo'] }}</b><br>
        @if ($data['clave'] == "")
            Clave: Debe iniciar sesión por Gmail hasta que asigne una contraseña en la plataforma.
        @else
		    Clave: <b>{{ $data['clave'] }}</b><br>
        @endif

        ¡¡Feliz y Transformado día!!<br>
        Equipo TransfórmatePRO<br>
        24/7 al Servicio de tu Transformación
	</p>
	<br>
</body>
</html>