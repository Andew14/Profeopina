<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function profesors()
    {
        return $this->hasMany(Profesor::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
