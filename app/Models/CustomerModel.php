<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerModel extends BaseModel
{
    private const TABLE = 'users';
    private const ROLE_ID = 2;
    private static $user_id = NULL;

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->select('users.*', 'users_plans.plan_id')
            ->join('users_plans', 'users.id', '=', 'users_plans.user_id')
            ->where('role_id', self::ROLE_ID)
            ->where('users.id', $id)
            ->get()
            ->first();
    }

    public static function get_all()
    {
        $customers = DB::table(self::TABLE)
            ->where('users.role_id', self::ROLE_ID)
            ->join('users_plans', 'users.id', '=', 'users_plans.user_id')
            ->leftJoin('plans', 'users_plans.plan_id', '=', 'plans.id')
            ->select('users.*', 'plans.name as plan_name')
            ->get();
        foreach($customers as &$customer) {
            $coupons = PlanCoupon::select('coupon')
                ->where('user_id', $customer->id)
                ->get();
            $couponsList = [];
            foreach($coupons as $coupon) {
                $couponsList[] = $coupon->coupon;
            }
            $customer->coupons = implode(', ', $couponsList);
        }
        return $customers;
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name', 'folder.color as folder_color')
            ->get();
    }

    public static function get_by_folder($folder_id)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->where('subscriptions.folder_id', $folder_id)
            ->select('subscriptions.*', 'brands.name as brand_name')
            ->get();
    }

    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    public static function create_tag($data)
    {
        // Insert single data
        return DB::table('subscriptions_tags')
            ->insertGetId($data);
    }

    public static function create_tags($data_arr)
    {
        // Insert multiple data
        return DB::table('subscriptions_tags')
            ->insert($data_arr);
    }

    public static function do_update($id, $data)
    {
        if (isset($data['plan_id'])) {
            DB::table('users_plans')
                ->where('user_id', $id)
                ->update([
                    'plan_id' => $data['plan_id'],
                ]);
            unset($data['plan_id']);
        }

        $status = DB::table(self::TABLE)
            ->where('role_id', self::ROLE_ID)
            ->where('id', $id)
            ->update($data);

        return $status;
    }

    public static function get_tags($subscription_id)
    {
        return DB::table('subscriptions_tags')
            ->join('tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
            ->where('subscriptions_tags.user_id', self::get_user_id())
            ->where('subscriptions_tags.subscription_id', $subscription_id)
            ->select('tags.*', 'subscriptions_tags.tag_id')
            ->get();
    }

    public static function get_tags_arr($subscription_id)
    {
        $tags = DB::table('subscriptions_tags')
            ->join('tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
            ->where('subscriptions_tags.user_id', self::get_user_id())
            ->where('subscriptions_tags.subscription_id', $subscription_id)
            ->select('tags.*', 'subscriptions_tags.tag_id')
            ->get();

        // Convert to key value paired array
        $data = [];
        if (!empty($tags)) {
            foreach ($tags as $key => $val) {
                $data[$val->id] = $val->name;
            }
        }
        return $data;
    }


    public static function del($id)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();

        // Check if Subscription deleted successfully
        if ($status) {
            return DB::table('subscriptions_tags')
                ->where('subscription_id', $id)
                ->delete();
        }
    }

    public static function get_event_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name')
            ->get();
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
