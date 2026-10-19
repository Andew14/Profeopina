@extends('layouts.main')

@section('titulo', 'Página no encontrada - ProfeOpina')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; background: #f5f5f5; padding: 20px; font-family: sans-serif;">
    <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 50px 30px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); text-align: center; max-width: 500px; width: 100%;">
        <div style="font-size: 6em; font-weight: 800; color: #353564; line-height: 1; margin-bottom: 20px;">404</div>
        
        <h1 style="font-size: 1.8em; color: #353564; margin-bottom: 15px; font-weight: 700;">¡Página no encontrada!</h1>
        
        <p style="color: #666; margin-bottom: 30px; line-height: 1.6; font-size: 1em;">
            Lo sentimos, el enlace que has seguido puede estar roto, haber expirado o la página a la que intentas acceder ya no existe.
        </p>
        
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @auth('student')
                <a href="{{ route('iniciologueado') }}" style="background: #353564; color: #d9bc27; font-weight: bold; border: none; padding: 12px 25px; border-radius: 8px; text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    Ir al Inicio
                </a>
            @elseauth('web')
                <a href="{{ route('admin.dashboard') }}" style="background: #353564; color: #d9bc27; font-weight: bold; border: none; padding: 12px 25px; border-radius: 8px; text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    Ir al Dashboard Admin
                </a>
            @else
                <a href="{{ route('inicio') }}" style="background: #353564; color: #d9bc27; font-weight: bold; border: none; padding: 12px 25px; border-radius: 8px; text-decoration: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    Ir al Inicio
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
