<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * ApiTokenModel - Manages API tokens with additional functions
 *
 * This model extends the basic ApiToken functionality with:
 * - User-specific token retrieval
 * - Default value setting for new tokens
 *
 * @see ApiToken For the base model
 */
class ApiTokenModel extends BaseModel
{
    /** @var string Table name for personal access tokens */
    protected $table = 'personal_access_tokens';
    
    /** @var string Constant for table name */
    private const TABLE = 'personal_access_tokens';
    
    /** @var array Default values for new tokens */
    private static $default = [];
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get an API token by ID
     * @param int $id Token ID
     * @return object|null Token object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get API tokens for a specific user
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of token objects
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('personal_access_tokens.tokenable_type', 'App\Models\Api\User')
            ->where('personal_access_tokens.tokenable_id', self::get_user_id($user_id))
            ->select(
                'personal_access_tokens.*',
            )
            ->get();
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

    /**
     * Get all API tokens
     * @return \Illuminate\Support\Collection Collection of all token objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Create a new API token
     * @param array $data Token data
     * @return int Inserted token ID
     * @note Sets default values before creating
     */
    public static function create($data)
    {
        $data = self::set_default_val($data);

        return DB::table(self::TABLE)
            ->insertGetId($data);
    }

    /**
     * Update an existing API token
     * @param int $id Token ID
     * @param array $data Token data to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete an API token
     * @param int $id Token ID
     * @return int Number of affected rows
     */
    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }

    /**
     * Set default values for a new token
     * @param array $data Token data
     * @return array Token data with default values applied
     */
    private static function set_default_val($data)
    {
        // Default values
        self::$default = [
            'created_at' => date(APP_TIMESTAMP_FORMAT),
        ];

        foreach (self::$default as $key => $val) {
            if (!isset($data[$key])) {
                $data[$key] = $val;
            }
        }

        return $data;
    }
}
