<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <h1>Listado de Ventas</h1>
    <a href="{{ route('ventas.create') }}">Crear Nueva Venta</a>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->nombre_producto }}</td>
                    <td>{{ $venta->cantidad }}</td>
                    <td>{{ $venta->precio }}</td>
                    <td>{{ $venta->cliente }}</td>
                    <td>{{ $venta->fecha_venta }}</td>
                    <td>
                        <a href="{{ route('ventas.show', $venta->id) }}">Ver</a>
                        <a href="{{ route('ventas.edit', $venta->id) }}">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>