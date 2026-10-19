<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'institution_id'];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function profesors()
    {
        return $this->belongsToMany(Profesor::class, 'course_profesor', 'course_id', 'profesor_id');
    }
}
