<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time', 'is_active', 'mid_time', 'mid_analysis_run', 'end_analysis_run'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'mid_time' => 'datetime',
        'mid_analysis_run' => 'boolean',
        'end_analysis_run' => 'boolean',
    ];

    public function resenias()
    {
        return $this->hasMany(Resenia::class);
    }
}
