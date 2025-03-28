<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Library\NotificationEngine;

class ProductModel extends BaseModel
{
    private const TABLE = 'products';
    protected $table = 'products';
    private static $user_id = NULL;

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('products.id', $id)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get()
            ->first();
    }

    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get();
    }

    public static function get_all_except_default()
    {
        return DB::table(self::TABLE)
            ->where('products.id', '>', PRODUCT_RESERVED_ID)
            ->where('products.status', 1)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get();
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
        $data_id = DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));

        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'admin_id' => Auth::id(),
            'event_type' => 'products',
            'event_type_status' => 'create',
            'event_status' => $data_id ? 1 : 2,
            'table_name' => self::TABLE,
            'table_row_id' => $data_id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);

        return $data_id;
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
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);

        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'admin_id' => Auth::id(),
            'event_type' => 'products',
            'event_type_status' => 'update',
            'event_status' => 1,
            'table_name' => self::TABLE,
            'table_row_id' => $id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);

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

        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'admin_id' => Auth::id(),
            'event_type' => 'products',
            'event_type_status' => 'delete',
            'event_status' => $status ? 1 : 2,
            'table_name' => self::TABLE,
            'table_row_id' => $id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);

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

    public static function get_or_create($name)
    {
        $data = DB::table(self::TABLE)
            ->where('product_name', $name)
            ->get()
            ->first();

        if (empty($data)) {
            return DB::table(self::TABLE)
                ->insertGetId(parent::_add_created([
                    'product_name' => $name,
                ]));
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
}
