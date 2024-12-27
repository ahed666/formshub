<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTranslation extends Model
{
    use HasFactory;
    protected $fillable = ['answer_id', 'language', 'answer_text'];
     protected $table="answers_translations";
    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
