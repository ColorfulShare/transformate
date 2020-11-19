<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>TransfórmatePRO</title>
	</head>
	<body>
		<p>
			Estimado <b>{{ $data['usuario'] }}</b>, bienvenido a la Comunidad TramsformatePRO, una comunidad de Mentores al servicio de tu Tramsformación.<br><br>

			Hemos recibido con éxito el monto de <b>{{ number_format($data['infoCompra']->amount, 0, ',', '.') }}</b> a través del método de pago {{ $data['infoCompra']->payment_method }} con número de transacción {{ $data['infoCompra']->payment_id }}, correspondiente a la compra de los siguientes contenidos:<br>
			@foreach ($data['productos'] as $producto)
				@if (!is_null($producto->course_id))
					*<b>{{ $producto->course->title }}</b> @if (!is_null($producto->gift)) (Código para Regalo: {{ $producto->gift->code }}) @endif<br>
				@elseif (!is_null($producto->podcast_id))
					*<b>{{ $producto->podcast->title }}</b> @if (!is_null($producto->gift)) (Código para Regalo: {{ $producto->gift->code }}) @endif<br>
				@elseif (!is_null($producto->membership_id))
					*<b>{{ $producto->membership->name }}</b><br>
				@elseif (!is_null($producto->product_id))
					*<b>{{ $producto->market_product->name }}</b><br>
				@endif
			@endforeach  <br><br>

			Nos sentimos en plenitud al saber que podemos contribuir a potenciar aún más tu luz, una que unidos, nos permitirá cocrear una humanidad más reconciliada, en equilibrio y en paz.<br><br>

			Que éste sea el inicio de grandes cosas juntos, sobre todo conectados con nuestro poder creador, para lograr un equilibrio entre el ser y hacer. <br><br>

			A continuación te indicamos los pasos a seguir para que puedas disfrutar de los T-Courses adquiridos:<br>
			1. Ingresa a la plataforma con tu usuario y contraseña.<br>
			2. En la parte superior derecha darle click al avatar de tu perfil.<br>
			3. En este menú, dar click en Mis cursos<br>
			4. Una vez estando en el curso, click en ir a lecciones.<br><br>

			Igualmente puedes acceder a este link una vez inicies sesión en tu cuenta para ir directamente a tus T-Courses adquiridos: <a href="https://www.transformatepro.com/students/my-content">https://www.transformatepro.com/students/my-content</a><br><bR>

			En caso de que hayas comprado algún contenido para regalar, puedes acceder a este link para ver los detalles del regalo: <a href="https://www.transformatepro.com/students/my-gifts">https://www.transformatepro.com/students/my-gifts</a>
			Y listo ahora si, a transformarte para transformar.<br><br>

			Recuerda que el equipo de Transformatepro.com  te apoyará en esta Ruta de Transformación todo el tiempo que lo necesites.<br><br>

			Bienvenida a nuestra Comunidad que se inspira para Transformar-T<br><br>

			¡¡Feliz y Transformado día!!
		</p>
	</body>
</html>