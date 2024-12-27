<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCode extends Model
{
    use HasFactory;
    protected $table="devices_codes";
    
    protected $fillable = ['code', 'pin','device_info','app_version','screen_w','screen_h'];

    protected $casts = [
        'screen_w' => 'string',
        'screen_h' => 'string',
    ];

    public static function checkPinAvailable($pin){
        
       return self::wherepin($pin)->first();

    }


}
