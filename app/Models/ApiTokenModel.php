<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiTokenModel extends BaseModel
{
    protected $table = 'personal_access_tokens';
    private const TABLE = 'personal_access_tokens';
    private static $default = [];
    private static $user_id = NULL;

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

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

    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public static function create($data)
    {
        $data = self::set_default_val($data);

        return DB::table(self::TABLE)
            ->insertGetId($data);
    }

    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }

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
