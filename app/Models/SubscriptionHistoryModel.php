<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubscriptionHistoryModel extends BaseModel
{
    private const TABLE = 'subscriptions_history';
    protected $table = 'subscriptions_history';
    private static $user_id = NULL;
    public $timestamps = false;

    public static function get_by_sub_id($subscription_id)
    {
        return DB::table(self::TABLE)
            ->where('subscription_id', $subscription_id)
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->join('subscriptions', 'subscriptions_history.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->where('subscriptions_history.user_id', self::get_user_id($user_id))
            ->select('subscriptions_history.*', 'subscriptions.product_name', 'subscriptions.folder_id',
            'folder.color as folder_color', 'folder.name as folder_name', 'subscriptions.image',
            'subscriptions.type', 'subscriptions.price_type', 'subscriptions.billing_cycle',
            'subscriptions.recurring', 'subscriptions.alert_type', 'subscriptions.status',
            'subscriptions.sub_addon')
            ->get();
    }

    public static function do_update($id, $data)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);

        return $status;
    }

    public static function del($id)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();

        return $status;
    }

    private static function get_user_id($user_id = NULL)
    {
        if (empty(self::$user_id)) {
            self::$user_id = Auth::id();
        }

        if (empty($user_id)) {
            return self::$user_id;
        } else {
            return $user_id;
        }
    }
}
