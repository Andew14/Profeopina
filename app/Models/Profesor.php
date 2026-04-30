<?php

// app/Models/Profesor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'apellido', 'descripcion', 'foto', 'institution_id', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function resenias()
    {
        return $this->hasMany(Resenia::class, 'profesor_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
