<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Response extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'response_translation_id','device_id'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Dubai')->format('Y-m-d H:i:s'); // Format as needed
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($response) {
            
            $response->created_at = $response->freshTimestamp();
            $response->updated_at = $response->freshTimestamp();

        });

        static::updating(function ($response) {

            $response->updated_at = $response->freshTimestamp();

        });
        
    }
    public function questionResponses()
    {
        return $this->hasMany(QuestionResponse::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
