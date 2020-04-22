<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Cambios en la reservación</title>
</head>
<body>
    <h4>Cambios realizados a su reservación</h4>
    <p>Buen día {{ $cliente->nombre }}.</p>
    <p>Su reservación #{{ $reservacion->id }} ha sido modificada en nuestro sistema.</p>
    <p>Le dejamos un resumen de los cambios</p>
    <hr>
    @foreach($cambios as $key => $cambio)
        <p><strong>{{ $key }}</strong>{{ $cambio }}</p>
    @endforeach
    <p>Gracias por vivir la experiencia que <strong>Hotel Honolulu</strong> le ofrece</p>
    <h4>Fue atendido por: {{ $empleado->name }}</h4>
</body>
</html>