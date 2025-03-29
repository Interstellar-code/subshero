<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * EmailModel - Manages email templates and logging
 *
 * This model handles:
 * - Email template CRUD operations
 * - Email logging
 */
class EmailModel extends BaseModel
{
    /** @var string Database table name for email templates */
    private const TABLE = 'email_templates';
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;
    
    /** @var array Default values for email logs */
    private static $default = [];

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
     * Log an email
     * @param array $data Email log data
     * @return int Inserted log ID
     * @note Sets default values before creating the log
     */
    public static function log($data)
    {
        $data = self::set_default_val($data);

        return DB::table('email_logs')
            ->insertGetId($data);
    }

    /**
     * Update an email log
     * @param int $id Log ID
     * @param array $data Log data to update
     * @return int Number of affected rows
     */
    public static function log_update($id, $data)
    {
        return DB::table('email_logs')
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Set default values for an email log
     * @param array $data Log data
     * @return array Log data with default values applied
     */
    private static function set_default_val($data)
    {
        // Default values
        self::$default = [
            'from_name' => null,
            'from_email' => null,
            'to_name' => null,
            'to_email' => null,
            'subject' => null,
            'body' => null,
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::id(),
            'created_timezone' => date_default_timezone_get(),
        ];

        foreach (self::$default as $key => $val) {
            if (!isset($data[$key])) {
                $data[$key] = $val;
            }
        }

        return $data;
    }

    /**
     * Get all email logs
     * @return \Illuminate\Support\Collection Collection of all log objects
     */
    public static function get_logs()
    {
        return DB::table('email_logs')
            ->get();
    }

    /**
     * Delete all email logs older than the current month
     * @return int Number of deleted logs
     */
    public static function delete_all_logs()
    {
        $this_month = Carbon::now()->startOfMonth();

        return DB::table('email_logs')
            ->whereDate('created_at', '<', $this_month)
            ->delete();
    }
}
