<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_history';
    public $timestamps = false;

    public static function create_history($subscription) {
        $subscriptionHistory = new self;
        $subscriptionHistory->subscription_id = $subscription->id;
        $subscriptionHistory->user_id = $subscription->user_id;
        $subscriptionHistory->price = $subscription->price;
        $subscriptionHistory->payment_date = $subscription->payment_date;
        $subscriptionHistory->next_payment_date = $subscription->next_payment_date;
        $subscriptionHistory->payment_date_upd = $subscription->payment_date_upd;
        $subscriptionHistory->payment_mode = $subscription->payment_mode;
        $subscriptionHistory->payment_mode_id = $subscription->payment_mode_id;
        $subscriptionHistory->save();
        return $subscriptionHistory->id;
    }

    public static function find_subscription_ids_for_null_next_payment_date($user_id) {
		$query = SubscriptionHistory::whereNull('next_payment_date');
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $subscription_ids = $query
            ->distinct('subscription_id')
            ->pluck('subscription_id')
            ->toArray();
        return $subscription_ids;
    }
}
