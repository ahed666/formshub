<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;  // Import the Str class

class Form extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'slug', 'name', 'logo','enable'];
     
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($form) {
            $form->slug = Str::slug('form-' . Str::random(16));
            $form->logo = '/images/logo/form/default-form-logo.png';
            $form->created_at = $form->freshTimestamp();
            $form->updated_at = $form->freshTimestamp();

        });

        static::updating(function ($form) {

            $form->updated_at = $form->freshTimestamp();

        });
        static::created(function ($form) {
            // Array of default languages for translations
            $langs = [
                ['code' => 'lang1', 'name' => 'Language1','enable'=>true],
                ['code' => 'lang2', 'name' => 'Language2','enable'=>false]
            ];
            foreach ($langs as $lang) {
                FormTranslation::create([
                    'form_id' => $form->id,
                    'language' => $lang['code'],
                    'language_name'=>$lang['name'],
                    'enable'=>$lang['enable']
                ]);
            }
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function translations()
    {
        return $this->hasMany(FormTranslation::class);
    }

}
