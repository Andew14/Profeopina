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
        if ($this->foto) {
            // Si es un archivo local existente
            if (file_exists(public_path($this->foto))) {
                return asset($this->foto);
            }

            // Si es una URL externa
            if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
                // El servidor de sistemasunas.edu.pe se encuentra caído (Cloudflare 522).
                // Retornar avatar por defecto para que la página cargue al instante sin congelarse.
                if (str_contains($this->foto, 'sistemasunas.edu.pe')) {
                    return asset('imagenes/default-avatar.svg');
                }

                return $this->foto;
            }
        }

        return asset('imagenes/default-avatar.svg');
    }
}
