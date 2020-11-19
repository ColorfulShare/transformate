<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transfórmate</title>
	<link rel="stylesheet" href="{{ asset('template/css/fontawesome.css') }}">
</head>
<body>
	<p>
		@if ($data['regalo'] == false)
			Usted acaba de compra una membresía de regalo en TransfórmatePRO.com. A continuación le facilitamos el código de aplicación para que pueda hacer uso de la misma y los detalles de su compra. 
		@else
			Le acaban de enviar una membresía de regalo de TransfórmatePRO.com.  A continuación le facilitamos el código de aplicación para que pueda hacer uso de la misma.
		@endif
	</p>
	<br>
	
	@if ($data['regalo'] == false)
		<p>
			<h3>Detalles de Compra</h3><br>

	        # de Orden: <b>{{ $data['datosPago']->id }}</b><br>
	        Forma de Pago: <b> {{ $data['datosPago']->payment_method }}</b><br>
	        ID de Transacción: <b>{{ $data['datosPago']->payment_id }}</b><br>
	        Fecha de Compra: <b>{{ date('d-m-Y H:i A', strtotime("$data['datosPago']->created_at -5 Hours")) }}</b><br>
	        Monto Pagado: <b>COP$ {{ number_format($data['datosPago']->amount, 0, ',', '.') }}</b>
		</p><br>
	@endif
	
	<p>
		<h3>Membresía de Regalo</h3><br>
	    <b>{{ $data['membresia'] }}</b><br>
	    Código de Aplicación: {{ $data['codigo'] }}
	</p>
	<br>
	
	@if ($data['regalo'] == false)
		<p>Gracias por su compra. <br>Equipo de Transfórmate</p>
	@else
		<p>Equio de Transfórmate</p>
	@endif

</body>
</html>