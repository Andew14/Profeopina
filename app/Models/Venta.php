<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';

    // Campos que pueden ser llenados masivamente
    protected $fillable = [
        'nombre_producto',  // ejemplo de campo
        'cantidad',
        'precio',
        'cliente',
        'fecha_venta',
        
    ];
    public $timestamps = false;
}
