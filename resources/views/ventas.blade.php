<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ventas</title>
    <link rel="stylesheet" href="/css/ventas.css">
</head>
<body>
    @extends('layouts.navbarsinsesion')
    @section('content')
    <ul>
        <li>{{ __('messages.name') }}: {{ $name }}</li>
        <li>Versión: {{ $version }}</li>
        <li>{{ __('messages.description') }}: {{ $description }}</li>
        <li>{{ __('messages.user') }}: {{ $usuario }}</li>
        <li>{{ __('messages.password') }}: {{ $contrasenia }}</li>
        <li>{{ __('messages.descmax') }}: {{ $descuento_maximo }}</li>
        <li>{{ __('messages.cellphone') }}: {{ $telefono }}</li>
        <li>{{ __('messages.email') }}: {{ $correo }}</li>
        <li>{{ __('messages.tax') }}: {{ $impuesto }}</li>
        <li>{{ __('messages.money') }}</li>
        <li>HOST: {{ $DB_HOST }}</li>
        <li>PORT:{{ $DB_PORT }}</li>
        <li>DATABSE:{{ $DB_DATABASE }}</li>
    </ul>
    @endsection
</body>
</html>