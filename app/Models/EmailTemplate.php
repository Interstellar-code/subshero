<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * EmailTemplate - Manages email templates
 *
 * This model handles:
 * - Email template CRUD operations
 * - Retrieval of templates, especially user-specific templates
 * - Clearing default template flags
 */
class EmailTemplate extends BaseModel
{
    /** @var string Database table name */
    private const TABLE = 'email_templates';
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get an email template by ID
     * @param int $id Template ID
     * @return object|null Template object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all email templates
     * @return \Illuminate\Support\Collection Collection of all template objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Get all email templates for a specific user
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of user's template objects
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('email_templates.user_id', self::get_user_id($user_id))
            ->select('email_templates.*')
            ->get();
    }

    /**
     * Create a new email template
     * @param array $data Template data
     * @return int Inserted template ID
     */
    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    /**
     * Update an existing email template
     * @param int $id Template ID
     * @param array $data Template data to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete an email template
     * @param int $id Template ID
     * @return int Number of affected rows
     */
    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }

    /**
     * Clear the default flag for all templates of a given type
     * @param string $type Template type
     * @return bool True if successful, false if type is empty
     */
    public static function clear_default($type)
    {
        if (empty($type)) {
            return false;
        }

        return DB::table(self::TABLE)
            ->where('type', $type)
            ->update([
                'is_default' => 0,
            ]);
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
