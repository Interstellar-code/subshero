<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * CustomerModel - Manages customer data and their plans
 * 
 * This model handles:
 * - Customer retrieval and creation
 * - Linking customers to specific plans
 */
class CustomerModel extends BaseModel
{
    /** @var string Database table name (users) */
    private const TABLE = 'users';
    
    /** @var int Role ID for customers */
    private const ROLE_ID = 2;
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get a customer by ID with their plan information
     * @param int $id Customer ID
     * @return object|null Customer object with plan info or null if not found
     */
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

    /**
     * Get all customers with their plan information and coupons
     * @return \Illuminate\Support\Collection Collection of customer objects with plan and coupon info
     */
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

    /**
     * Get subscriptions for a specific user (This method seems misplaced)
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of subscription objects
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name', 'folder.color as folder_color')
            ->get();
    }

    /**
     * Get subscriptions in a specific folder (This method seems misplaced)
     * @param int $folder_id Folder ID
     * @return \Illuminate\Support\Collection Collection of subscription objects
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
    public static function get_by_folder($folder_id)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->where('subscriptions.folder_id', $folder_id)
            ->select('subscriptions.*', 'brands.name as brand_name')
            ->get();
    }

    /**
     * Create a new customer
     * @param array $data Customer data
     * @return int Inserted customer ID
     */
    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    /**
     * Create a tag for a subscription (This method seems misplaced)
     * @param array $data Tag data
     * @return int Inserted tag ID
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
    public static function create_tag($data)
    {
        // Insert single data
        return DB::table('subscriptions_tags')
            ->insertGetId($data);
    }

    /**
     * Create tags for a subscription (This method seems misplaced)
     * @param array $data_arr Array of tag data
     * @return bool True if all inserts succeeded
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
    public static function create_tags($data_arr)
    {
        // Insert multiple data
        return DB::table('subscriptions_tags')
            ->insert($data_arr);
    }

    /**
     * Update customer data and plan
     * @param int $id Customer ID
     * @param array $data Customer data to update
     * @return int Number of affected rows
     * @note Handles plan ID updates in the users_plans table
     */
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

    /**
     * Get tags for a subscription (This method seems misplaced)
     * @param int $subscription_id Subscription ID
     * @return \Illuminate\Support\Collection Collection of tag objects
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
    public static function get_tags($subscription_id)
    {
        return DB::table('subscriptions_tags')
            ->join('tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
            ->where('subscriptions_tags.user_id', self::get_user_id())
            ->where('subscriptions_tags.subscription_id', $subscription_id)
            ->select('tags.*', 'subscriptions_tags.tag_id')
            ->get();
    }

    /**
     * Get tags for a subscription as key-value array (This method seems misplaced)
     * @param int $subscription_id Subscription ID
     * @return array Associative array of tag IDs to names
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
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

    /**
     * Delete a subscription (This method seems misplaced)
     * @param int $id Subscription ID
     * @return int Number of affected rows
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
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

    /**
     * Get event by user (This method seems misplaced)
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of subscription objects
     * @note This method seems to be related to subscriptions and might be better placed in SubscriptionModel
     */
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
