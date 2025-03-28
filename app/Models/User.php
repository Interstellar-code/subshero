<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\PasswordReset;
use Illuminate\Support\Str;
use App\Models\UsersPlan;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCountryShortNameAttribute()
    {
        $country = lib()->get->country($this->country);
        if (empty($country)) {
            return '';
        } else {
            return $country->shortname;
        }
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public static function generateMarketplaceToken(): string
    {
        // Generate random 16 character string which is not in use
        $random_string = '';
        do {
            $random_string = Str::random(16);
        } while (self::where('marketplace_token', 'like', '%' . $random_string . '%')->exists());

        return $random_string;
    }

    public function users_plan()
    {
        return $this->hasOne(UsersPlan::class)->withDefault(['plan_id' => FREE_PLAN_ID]);
    }
}
