<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTranslation extends Model
{
    use HasFactory;

    protected $fillable = ['form_id', 'language','enable','start_header', 'start_message','end_header' ,'end_message', 'language_name'];
    
    protected $table="forms_translations";

    protected static $defaultMessages=[
        'start_header'=>'Hi there, your opinion matters!',
        'start_message'=>'Please select the language to begin',
        'end_header'=>'Thank you for your time',
        'end_message'=>'We are glad to hear from you, Have a great day!',
        

    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($formtrans) {
            
            $formtrans->start_header = $formtrans->start_header ?? self::$defaultMessages['start_header'];
            $formtrans->start_message = $formtrans->start_message ?? self::$defaultMessages['start_message'];
            $formtrans->end_header = $formtrans->end_header ?? self::$defaultMessages['end_header'];
            $formtrans->end_message = $formtrans->end_message ?? self::$defaultMessages['end_message'];

            $formtrans->created_at = $formtrans->freshTimestamp();
            $formtrans->updated_at = $formtrans->freshTimestamp();

        });

        static::updating(function ($formtrans) {

            $formtrans->updated_at = $formtrans->freshTimestamp();

        });
    }
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

   

}
