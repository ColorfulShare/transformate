<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transfórmate</title>
</head>
<body>
	<p>
		Estimado {{ $data['cliente'] }}, su registro en la plataforma se ha realizado de forma exitosa. Debe esperar que los administradores verifiquen su información y aprueben su ingreso a TransfórmatePRO. En pocos días le notificaremos la respuesta de su solicitud. Por favor guarde los datos de su registro para futuros inicios de sesión en la plataforma:<br><br>

		Correo: <b>{{ $data['correo'] }}</b><br>
		Clave: <b>{{ $data['clave'] }}</b><br>
	</p>
	<br>

	<p>Gracias por su colaboración.<br>
		Equipo de Transfórmate</p>

</body>
</html>