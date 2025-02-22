<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTranslation extends Model
{
   
    use HasFactory;

    protected $fillable = ['question_id', 'language', 'question_text'];
    protected $table="questions_translations";
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
