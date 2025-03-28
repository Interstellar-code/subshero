<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentMethodModel extends BaseModel
{
    private const TABLE = 'users_payment_methods';
    private static $user_id = NULL;

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function get_by_name($name, $user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('name', $name)
            ->where('user_id', self::get_user_id($user_id))
            ->get()
            ->first();
    }

    public static function create($data)
    {
        $id = DB::table(self::TABLE)
            ->insertGetId($data);
        UsersPlan::update_total_payment();
        return $id;
    }

    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    public static function del($id)
    {
        $id = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
        UsersPlan::update_total_payment();
        return $id;
    }

    public static function get_or_create($name, $user_id = NULL)
    {
        $data = DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->where('name', $name)
            ->get()
            ->first();

        if (empty($data->id)) {
            return self::create([
                'user_id' => self::get_user_id($user_id),
                'payment_type' => 'Others',
                'name' => $name,
            ]);
        } else {
            return $data->id;
        }
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

    public static function is_using($id)
    {
        // Subscription check
        $subscription = DB::table('subscriptions')
            ->where('payment_mode_id', $id)
            ->get()
            ->first();

        if (!empty($subscription->id)) {
            return true;
        }

        // Subscription -> History check
        $subscription_history = DB::table('subscriptions_history')
            ->where('payment_mode_id', $id)
            ->get()
            ->first();

        if (!empty($subscription_history->id)) {
            return true;
        }

        // Return not using
        return false;
    }
}
