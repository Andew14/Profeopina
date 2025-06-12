<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/show.css">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <script src="/js/show.js"></script>
</head>
<body>
    <h1>Detalles de la Venta</h1>
    <p><strong>Nombre del Producto:</strong> {{ $venta->nombre_producto }}</p>
    <p><strong>Cantidad:</strong> {{ $venta->cantidad }}</p>
    <p><strong>Precio:</strong> {{ $venta->precio }}</p>
    <p><strong>Cliente:</strong> {{ $venta->cliente }}</p>
    <p><strong>Fecha de Venta:</strong> {{ $venta->fecha_venta }}</p>

    <!-- Botón de Eliminación -->
    <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas eliminar esta venta?');">Eliminar Venta</button>
    </form>

    <!-- Enlace para regresar al índice -->
    <a href="{{ route('ventas.index') }}">Regresar al índice</a>
</body>
</html>
