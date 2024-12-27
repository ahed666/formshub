<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'type_text'];
    protected $table="questions_types";
    public function category()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
