<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceCode;
use App\Models\Pictures;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\StandbykiosksMedia;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;
class Device extends Model
{
    use HasFactory;
    protected $table="devices";

    protected $fillable = ['code','form_id', 'pin','account_id','name','device_info','app_version','screen_w','screen_h','site','longitude','latitude'];
    
    protected $casts = [
        'screen_w' => 'string',
        'screen_h' => 'string',
    ];

    public static function getAllDeviceBerAccount(){
      return self::where('devices.account_id',Auth::user()->current_account_id)->get();
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }


    public function responses()
    {
        return $this->hasMany(Response::class);
    }


}
