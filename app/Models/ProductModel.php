<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Library\NotificationEngine;

/**
 * ProductModel - Handles all product-related database operations
 *
 * This model manages the core product data including:
 * - Product CRUD operations
 * - Product type relationships
 * - Tag associations
 * - Audit logging for changes
 * - Product retrieval with joins to related tables
 */
class ProductModel extends BaseModel
{
    /** @var string Database table name for products */
    private const TABLE = 'products';
    
    /** @var string Protected table name for Eloquent compatibility */
    protected $table = 'products';
    
    /** @var int|null Stores the current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get a product by ID with its product type information
     * @param int $id Product ID to retrieve
     * @return object|null Product object with type info or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('products.id', $id)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get()
            ->first();
    }

    /**
     * Get all products with their product type information
     * @return \Illuminate\Support\Collection Collection of all product objects with type info
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get();
    }

    /**
     * Get all active products except default/reserved products
     * @return \Illuminate\Support\Collection Collection of non-default product objects with type info
     * @see PRODUCT_RESERVED_ID Constant defining reserved product IDs
     */
    public static function get_all_except_default()
    {
        return DB::table(self::TABLE)
            ->where('products.id', '>', PRODUCT_RESERVED_ID)
            ->where('products.status', 1)
            ->leftJoin('product_types', 'product_types.id', 'products.product_type')
            ->select('products.*', 'product_types.id as product_type_id', 'product_types.name as product_type_name')
            ->get();
    }

    /**
     * Get products for a specific user with brand and folder info
     * @param int|null $user_id User ID (defaults to current authenticated user)
     * @return \Illuminate\Support\Collection Collection of product objects with brand and folder info
     * @note Joins with brands and folder tables to provide additional context
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.b brand_id', '=', 'brands.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name', 'folder.color as folder_color')
            ->get();
    }

    /**
     * Get products in a specific folder with brand info
     * @param int $folder_id Folder ID to filter by
     * @return \Illuminate\Support\Collection Collection of product objects with brand info
     * @note Joins with brands table to provide brand names
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
     * Create a new product record
     * @param array $data Product data to insert
     * @return int ID of the newly created product
     * @note Automatically adds created_at timestamp and creates audit log
     */
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

    /**
     * Create a single product-tag association
     * @param array $data Tag association data (subscription_id, tag_id, user_id)
     * @return int ID of the newly created tag association
     */
    public static function create_tag($data)
    {
        // Insert single data
        return DB::table('subscriptions_tags')
            ->insertGetId($data);
    }

    /**
     * Create multiple product-tag associations
     * @param array $data_arr Array of tag association data
     * @return bool True if all inserts succeeded
     */
    public static function create_tags($data_arr)
    {
        // Insert multiple data
        return DB::table('subscriptions_tags')
            ->insert($data_arr);
    }

    /**
     * Update an existing product record
     * @param int $id Product ID to update
     * @param array $data Product data to update
     * @return int Number of affected rows
     * @note Creates an audit log entry for the update operation
     */
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

    /**
     * Get all tags associated with a product
     * @param int $subscription_id Product ID
     * @return \Illuminate\Support\Collection Collection of tag objects
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
     * Get all tags associated with a product as key-value array
     * @param int $subscription_id Product ID
     * @return array Associative array of tag IDs to tag names
     * @note Returns a simplified format compared to get_tags()
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
     * Delete a product and its associated tags
     * @param int $id Product ID to delete
     * @return int|null Number of affected rows for tag deletion or null if product deletion failed
     * @note Creates audit log entry and cleans up related tag associations
     */
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

    /**
     * Get products for a user with brand info for event purposes
     * @param int|null $user_id User ID (defaults to current authenticated user)
     * @return \Illuminate\Support\Collection Collection of product objects with brand info
     * @note Similar to get_by_user() but without folder info and optimized for events
     */
    public static function get_event_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name')
            ->get();
    }

    /**
     * Find or create a product by name
     * @param string $name Product name to search/create
     * @return int ID of existing or newly created product
     * @note Implements the common find-or-create pattern to ensure uniqueness
     */
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
