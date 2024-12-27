<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\SubscribePlan;
use App\Models\TypeSubscribe;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
// implements MustVerifyEmail

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;

    use Notifiable;
    use Billable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password','subscribe','mobile_number','timezone','billing_name','billing_country','billing_city','billing_state','billing_postal_code','billing_trn'
    
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function forms()
    {
        return $this->hasMany(Form::class);
    }
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function allResponses()
    {
        return $this->hasManyThrough(Response::class, Form::class);
    }


    public static function userVerifiy(){
        return self::select(
            DB::raw('CASE WHEN email_verified_at IS NOT NULL THEN "Verified" ELSE "Non-Verified" END AS property'),
            DB::raw('COUNT(*) as count')
        )
        ->groupBy('property')
        ->get();

  }
//   info about counts of emails subscriptions
  public static function userEmailsSubscriptions(){
    return [
        'E.S Notification' =>count(self::whereemail_sub_notification(true)->get()),
        'E.S Events' => count(self::whereemail_sub_offers_events(true)->get()),
        'E.S Security'=>count(self::whereemail_sub_security(true)->get()),
        'E.S Payment'=>count(self::whereemail_sub_payment_subscriptions(true)->get()),
    ];




}
// return all info about users
  public static function userInfo(){
    $userVerifiy=self::userVerifiy();
    $userEmailsSubscriptions=self::userEmailsSubscriptions();
    $userCount=self::all()->count();
    // Create the array
    $users = [
        'userVerifiy' => $userVerifiy,
        'userEmailsSubscriptions'=>$userEmailsSubscriptions,
        'userCount'=>$userCount
    ];
    return $users;
}
}
