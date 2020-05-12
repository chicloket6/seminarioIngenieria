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
    <p><strong>Fecha de entrada: </strong>{{ $reservacion->fecha_entrada ? $reservacion->fecha_entrada->format('d/m/Y H:i') . 'Hrs' : '' }}</p>
    <p><strong>Fecha de salida: </strong>{{ $reservacion->fecha_salida ? $reservacion->fecha_salida->format('d/m/Y H:i') . 'Hrs' : '' }}</p>
    <p><strong>Método de pago: </strong>{{ $reservacion->metodoPago->nombre }}</p>
    <p><strong>Total pagado: </strong>{{ number_format($reservacion->costo_total, 2) }}</p>
    <p><strong>Promociones aplicadas: </strong>{{ $reservacion->promocion ? $reservacion->promocion->nombre : 'Ninguna promoción aplicada' }}</p>
    
    @if(count($reservacion->serviciosAdicionales) > 0)
        <p><strong>Servicios adicionales: </strong></p>
        @foreach($reservacion->serviciosAdicionales as $sa)
            <p>{{ $sa->nombre .': '. format_number($sa->costo, 2) }}</p>
        @endforeach
    @endif

    <h4>Fue atendido por: {{ $empleado->name }}</h4>
</body>
</html>