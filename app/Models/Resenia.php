<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resenia extends Model
{
    use HasFactory;

    protected $fillable = ['contenido', 'calificacion', 'profesor_id', 'period_id', 'oculto', 'denunciada', 'curso', 'procesada'];

    protected $casts = [
        'calificacion' => 'integer',
        'oculto' => 'boolean',
        'denunciada' => 'boolean',
        'procesada' => 'boolean',
    ];

    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'users_resenias', 'resenia_id', 'user_id');
    }
}
