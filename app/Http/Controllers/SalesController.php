<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SalesController extends Controller
{
    public function index()
    {
        try {
            $packageJsonPath = base_path('package.json');
            if (!File::exists($packageJsonPath)) {
                return "El archivo package.json no existe.";
            }

            $packageJson = json_decode(File::get($packageJsonPath), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return "Error al analizar el archivo package.json: " . json_last_error_msg();
            }

            $name = $packageJson['name'] ?? 'N/A';
            $version = $packageJson['version'] ?? 'N/A';
            $description = $packageJson['description'] ?? 'N/A';
            $usuario = $packageJson['usuario'] ?? 'N/A';
            $contrasenia = $packageJson['contrasenia'] ?? 'N/A';
            $impuesto = $packageJson['impuesto'] ?? 'N/A';
            $descuento_maximo = $packageJson['descuento_maximo'] ?? 'N/A';
            $correo = $packageJson['correo'] ?? 'N/A';
            $telefono= $packageJson['telefono'] ?? 'N/A';
            $DB_HOST= $packageJson['DB_HOST'] ?? 'N/A';
            $DB_PORT= $packageJson['DB_PORT'] ?? 'N/A';
            $DB_DATABASE= $packageJson['DB_DATABASE'] ?? 'N/A';

            return view('ventas', compact('name', 'version', 'description', 'usuario', 'contrasenia','impuesto','correo','telefono','descuento_maximo','DB_HOST','DB_PORT','DB_DATABASE' ));
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
