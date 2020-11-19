<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transfórmate</title>
</head>
<body>
	<p>
		Estimado <b>{{ $data['usuario']->names }}</b> reciba un cordial saludo. Bienvenido a la Comunidad de Educación para la Transformación Transfórmate PRO. <br><br>
		
		Hemos recibido satisfactoriamente su registro en nuestro T Events: <b>{{ $data['evento'] }}</b>.<br><br>
		
		Sin embargo, le recordamos que faltan <b>{{ $data['dias_restantes'] }}</b> para el inicio del evento y aún no has completado tu pago. 
		Para hacer efectiva su inscripción, deberá usted completar el pago del valor de la inscripción, el cual aparece en estado pendiente. <br><br>
		
		Para completar su pago puede ingresar al siguiente enlace <a href="https://www.transformatepro.com/t-events/payment-bill/{{ $data['usuario']->id }}" target="_blank">https://www.transformatepro.com/t-events/payment-bill/{{ $data['usuario']->id }}</a><br><br>
		
		Si usted requiere algún apoyo en el proceso de pago, no dude en comunicarnos. El equipo TransfórmatePRO se encuentra a su disposición 24/7.<br><br>

		Lo invitamos a finalizar su inscripción en nuestro evento <b>{{ $data['evento'] }}</b>.¡¡¡Lo esperamos!!! 

		¡¡Feliz y Transformado día!!

		***Si usted ya realizó su pago, por favor haga caso omiso de este mensaje***
	</p>
	<br>
</body>
</html>