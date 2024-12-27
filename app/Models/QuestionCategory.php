<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    use HasFactory;
    protected $fillable = ['category_name'];
    protected $table="questions_categories";
    public function types()
    {
        return $this->hasMany(QuestionType::class,'category_id');
    }
}
