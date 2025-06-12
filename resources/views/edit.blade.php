<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Editar Venta</h1>

    <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="nombre_producto">Nombre del Producto</label>
            <input type="text" name="nombre_producto" id="nombre_producto" value="{{ $venta->nombre_producto }}" required>
        </div>
        <div>
            <label for="cantidad">Cantidad</label>
            <input type="number" name="cantidad" id="cantidad" value="{{ $venta->cantidad }}" required>
        </div>
        <div>
            <label for="precio">Precio</label>
            <input type="text" name="precio" id="precio" value="{{ $venta->precio }}" required>
        </div>
        <div>
            <label for="cliente">Cliente</label>
            <input type="text" name="cliente" id="cliente" value="{{ $venta->cliente }}" required>
        </div>
        <div>
            <label for="fecha_venta">Fecha de Venta</label>
            <input type="date" name="fecha_venta" id="fecha_venta" value="{{ $venta->fecha_venta }}" required>
        </div>
        <div>
            <button type="submit">Actualizar Venta</button>
        </div>
    </form>
</body>
</html>