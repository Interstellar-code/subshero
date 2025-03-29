<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * EmailType - Manages email types
 *
 * This model handles:
 * - Email type CRUD operations
 * - Retrieval of email types and template names
 */
class EmailType extends BaseModel
{
    /** @var string Database table name */
    private const TABLE = 'email_types';
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get an email type by ID
     * @param int $id Email type ID
     * @return object|null Email type object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all email types
     * @return \Illuminate\Support\Collection Collection of all email type objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->orderBy('field_name', 'asc')
            ->get();
    }

    /**
     * Get all distinct template names
     * @return \Illuminate\Support\Collection Collection of distinct template names
     */
    public static function get_all_template_name()
    {
        return DB::table(self::TABLE)
            ->select('template_name')
            ->distinct()
            ->orderBy('template_name', 'asc')
            ->get();
    }

    /**
     * Get all email templates for a specific user (This method seems misplaced)
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of user's template objects
     * @note This method seems to be related to email templates and might be better placed in EmailTemplateModel
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('email_templates.user_id', self::get_user_id($user_id))
            ->select('email_templates.*')
            ->get();
    }

    /**
     * Create a new email type
     * @param array $data Email type data
     * @return int Inserted email type ID
     */
    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    /**
     * Update an existing email type
     * @param int $id Email type ID
     * @param array $data Email type data to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
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
