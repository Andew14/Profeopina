<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['resenia_id', 'question_id', 'numeric_value', 'text_value'];

    public function resenia()
    {
        return $this->belongsTo(Resenia::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
