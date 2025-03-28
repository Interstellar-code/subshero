<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmailTemplate extends BaseModel
{
    private const TABLE = 'email_templates';
    private static $user_id = NULL;

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


    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }

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
