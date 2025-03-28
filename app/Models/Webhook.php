<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [];

    public static function get_by_user($user_id = null)
    {
        return self::where('user_id', $user_id ?? Auth::id())
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getEventsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setEventsAttribute($value)
    {
        $this->attributes['events'] = implode(',', $value);
    }

    public static function send_event(string $event, int $data_id)
    {
        switch ($event) {
            case 'subscription.created':
            case 'subscription.updated':
            case 'subscription.deleted':
            case 'subscription.refunded':
            case 'subscription.canceled':
                $subscription_array = Subscription::getDescribedSubscription($data_id);
                if (!empty($subscription_array['id'])) {
                    lib()->do->send_webhook($event, $subscription_array['user_id'], $subscription_array);
                }
        }
    }
}
