<table>
    <thead>
    <tr>
        <th><strong>Cliente</strong></th>
        <th><strong># Habitación</strong></th>
        <th><strong>Fecha de entrada</strong></th>
        <th><strong>Fecha de salida</strong></th>
        <th><strong>Costo de reservación</strong></th>
        <th><strong>Método de pago</strong></th>
        <th><strong>Promoción aplicada</strong></th>
        <th><strong>Servicios adicionales</strong></th>
        <!-- <th><strong>Costo de servicios adicionales</strong></th> -->
        <th><strong>Total</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservaciones as $reservacion)
        <tr>
            <td>{{ $reservacion->cliente ? $reservacion->cliente->nombre : '' }}</td>
            <td>{{ $reservacion->habitacion ? $reservacion->habitacion->numero : '' }}</td>
            <td>{{ $reservacion->fecha_entrada }}</td>
            <td>{{ $reservacion->fecha_salida }}</td>
            <td>{{ $reservacion->costo_total }}</td>
            <td>{{ $reservacion->metodoPago ? $reservacion->metodoPago->nombre : '' }}</td>
            <td>{{ $reservacion->promocion ? $reservacion->promocion->nombre : 'Ninguna promoción aplicada' }}</td>
            <td>{{ $reservacion->getServiciosAdicionales() }}</td>
            <td>{{ $reservacion->getTotalGastado() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>