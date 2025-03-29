<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * BrandModel - Manages product brands
 * 
 * This model handles:
 * - Brand CRUD operations
 * - Retrieval of system-level and user-specific brands
 */
class BrandModel extends BaseModel
{
    /** @var string Database table name */
    private const TABLE = 'brands';
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get a brand by ID
     * @param int $id Brand ID
     * @return object|null Brand object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all brands
     * @return \Illuminate\Support\Collection Collection of all brand objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Get all system-level brands (user_id = 0)
     * @return \Illuminate\Support\Collection Collection of system brand objects
     */
    public static function get_all_system()
    {
        return DB::table(self::TABLE)
            ->where('user_id', 0)
            ->select('brands.*')
            ->get();
    }

    /**
     * Get all system-level brands as key-value array
     * @return array Associative array of brand IDs to names
     */
    public static function get_all_system_arr()
    {
        return parent::obj_to_key_val_pair(
            DB::table(self::TABLE)
                ->where('user_id', 0)
                ->select('brands.*')
                ->get(),
            'id',
            'name'
        );
    }

    /**
     * Get all brands for a specific user
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of user's brand objects
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->select('brands.*')
            ->get();
    }

    /**
     * Create a new brand
     * @param array $data Brand data
     * @return int Inserted brand ID
     */
    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    /**
     * Update an existing brand
     * @param int $id Brand ID
     * @param array $data Brand data to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete a brand
     * @param int $id Brand ID
     * @return int Number of affected rows
     */
    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }

    /**
     * Get the current user ID
     * @param int|null $user_id User ID (defaults to current user)
     * @return int User ID
     */
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
