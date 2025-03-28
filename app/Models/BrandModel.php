<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BrandModel extends BaseModel
{
    private const TABLE = 'brands';
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

    public static function get_all_system()
    {
        return DB::table(self::TABLE)
            ->where('user_id', 0)
            ->select('brands.*')
            ->get();
    }

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

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->select('brands.*')
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
}
