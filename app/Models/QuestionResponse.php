<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class QuestionResponse extends Model
{
    use HasFactory;
    protected $fillable = ['response_id', 'question_id', 'answer_id', 'text_response','skip'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Dubai')->format('Y-m-d H:i:s'); // Format as needed
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($QuestionResponse) {
            
            $QuestionResponse->created_at = $QuestionResponse->freshTimestamp();
            $QuestionResponse->updated_at = $QuestionResponse->freshTimestamp();

        });

        static::updating(function ($QuestionResponse) {

            $QuestionResponse->updated_at = $QuestionResponse->freshTimestamp();

        });
        
    }
    public function response()
    {
        return $this->belongsTo(Response::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
