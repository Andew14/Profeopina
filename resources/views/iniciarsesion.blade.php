<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    @extends('auth.login')
    @section('title', __('messages.login_title'))
    @section('titulo', __('messages.login'))
    @section('comentario', __('messages.login_comment'))
    @section('reenvio', __('messages.forgot_password'))
</body>
</html>
