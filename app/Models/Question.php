<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'type_id', 'answers_view_mode','question_text','order', 'optional','with_answers'];

    

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function type()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function translations()
    {
        return $this->hasMany(QuestionTranslation::class);
    }
}
