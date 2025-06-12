<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Authenticatable
{
    use HasFactory;

    // Define la tabla si el nombre no es plural de forma estándar
    protected $table = 'students';

    // Indica los campos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Oculta los campos sensibles
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define la relación con el guard
    public function getAuthPassword()
    {
        return $this->password;
    }
}
