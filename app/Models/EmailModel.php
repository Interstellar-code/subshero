<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmailModel extends BaseModel
{
    private const TABLE = 'email_templates';
    private static $user_id = NULL;
    private static $default = [];

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
            ->where('email_templates.user_id', self::get_user_id($user_id))
            ->select('email_templates.*')
            ->get();
    }

    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
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

    public static function log($data)
    {
        $data = self::set_default_val($data);

        return DB::table('email_logs')
            ->insertGetId($data);
    }

    public static function log_update($id, $data)
    {
        return DB::table('email_logs')
            ->where('id', $id)
            ->update($data);
    }

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

    public static function get_logs()
    {
        return DB::table('email_logs')
            ->get();
    }

    public static function delete_all_logs()
    {
        $this_month = Carbon::now()->startOfMonth();

        return DB::table('email_logs')
            ->whereDate('created_at', '<', $this_month)
            ->delete();
    }
}
