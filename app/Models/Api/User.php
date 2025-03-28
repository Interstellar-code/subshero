<?php

namespace App\Models\Api;

use App\Models\Plan;
use App\Models\UsersPlan;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's image.
     *
     * @param  string  $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return img_url($value);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function users_plan()
    {
        return $this->hasOne(UsersPlan::class)->withDefault(['plan_id' => FREE_PLAN_ID]);
    }

    public function getPlanNameAttribute()
    {
        $plan = Plan::find($this->users_plan->plan_id);

        if (!empty($plan->id)) {
            return $plan->name;
        }

        return '';
    }
}
