<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resenia extends Model
{
    use HasFactory;

    protected $fillable = ['contenido', 'calificacion', 'profesor_id'];

    
    public function profesor()
    {
        return $this->belongsTo(Profesor::class , 'resenia_id');
    }
}
