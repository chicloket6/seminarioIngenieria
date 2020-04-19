<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Reservación exitosa</title>
</head>
<body>
    <h4>Reservación exitosa</h4>
    <p>Buen día {{ $cliente->nombre }}.</p>
    <p>Su reservación #{{ $reservacion->id }} ha sido registrada de manera exitosa en nuestro sistema.</p>
    <p>Le dejamos un resumen de su reservación</p>
    <hr>
    <p><strong># Habitación: </strong>{{ $reservacion->habitacion ? $reservacion->habitacion->numero : '' }}</p>
    <p><strong>Fecha de entrada: </strong>{{ $reservacion->fecha_entrada }}</p>
    <p><strong>Fecha de salida: </strong>{{ $reservacion->fecha_salida }}</p>
    <p><strong>Método de pago: </strong>{{ $reservacion->metodoPago->nombre }}</p>
    <p><strong>Total pagado: </strong>{{ number_format($reservacion->costo_total, 2) }}</p>
    <p><strong>Promociones aplicadas: </strong>{{ $reservacion->promocion ? $reservacion->promocion->nombre : 'Ninguna promoción aplicada' }}</p>

    <h4>Fue atendido por: {{ $empleado->name }}</h4>
</body>
</html>