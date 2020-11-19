<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transfórmate</title>
</head>
<body>
	<p>
		Estimado <b>{{ $data['infoSuscripcion']->names }}</b>, bienvenido a la Comunidad TramsformatePRO, una comunidad de Mentores al servicio de tu Transformación.<br><br>
		
		@if ($data['infoSuscripcion']->gift == 0)
			Hemos recibido con éxito el monto de <b>{{ number_format($data['infoSuscripcion']->payment_amount, 0, ',', '.') }}</b>, correspondiente a la inscripción del evento <b>{{ $data['infoSuscripcion']->event->title }}</b> a través del método de pago {{ $data['infoSuscripcion']->payment_method }} con número de transacción {{ $data['infoSuscripcion']->payment_id }}. <br><br>

			Nos sentimos en plenitud al saber que podemos contribuir a potenciar aún más tu luz, una que unidos, nos permitirá cocrear una humanidad más reconciliada, en equilibrio y en paz.<br><br>

			Que éste sea el inicio de grandes cosas juntos, sobre todo conectados con nuestro poder creador, para lograr un equilibrio entre el ser y hacer. <br><br>

			A pocos días de nuestro Evento {{ $data['infoSuscripcion']->event->title }} te estaremos haciendo llegar el link de Zoom para que puedas acceder a tan extraordinario espacio de inspiración y transformación.<br><br>
			
			Para más información ingresa aquí <a href="https://www.transformatepro.com/t-events" target="_blank">https://www.transformatepro.com/t-events</a><br><br>
		@else
			Hemos recibido con éxito el monto de <b>{{ number_format($data['infoSuscripcion']->payment_amount, 0, ',', '.') }}</b>, correspondiente a la compra de una T-GIFT para el evento <b>{{ $data['infoSuscripcion']->event->title }}</b> a través del método de pago {{ $data['infoSuscripcion']->payment_method }} con número de transacción {{ $data['infoSuscripcion']->payment_id }}. <br><br>

			El código de aplicación de tu T-GIFT es el siguiente: <b>{{ $data['infoSuscripcion']->gift_code }}</b>. Este código solo debes compartirlo con la persona a la que deseas obsequiar esta inscripción a nuestro evento {{ $data['infoSuscripcion']->event->title }}, la cual luego podrá ingresar a nuestra página <a href="https://www.transformatepro.com/t-events" target="_blank">https://www.transformatepro.com/t-events</a> para canjear su T-GIFT.<br><br>
		@endif

		¡¡Feliz y Transformado día!!
	</p>
	<br>
</body>
</html>