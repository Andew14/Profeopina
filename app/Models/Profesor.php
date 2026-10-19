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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_profesor', 'profesor_id', 'course_id');
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto && (file_exists(public_path($this->foto)) || str_starts_with($this->foto, 'http'))) {
            return str_starts_with($this->foto, 'http') ? $this->foto : asset($this->foto);
        }
        return asset('imagenes/default-avatar.svg');
    }
}
