<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TagModel extends BaseModel
{
    private const TABLE = 'tags';
    public $table = 'tags';
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
            ->where('user_id', self::get_user_id($user_id))
            ->select('tags.*')
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function get_by_user_arr($user_id = NULL)
    {
        return parent::obj_to_key_val_pair(
            DB::table(self::TABLE)
                ->where('user_id', self::get_user_id($user_id))
                ->select('tags.*')
                ->get(),
            'id',
            'name'
        );
    }

    public static function create($data)
    {
        $id = DB::table(self::TABLE)
            ->insertGetId($data);
        UsersPlan::update_total_tags();
        return $id;
    }

    public static function do_update($id, $data)
    {
        return DB::table('tags')
            ->where('id', $id)
            ->update($data);
    }

    public static function del($id)
    {
        $deleted = DB::table('tags')->where('id', $id)->delete();
        UsersPlan::update_total_tags();
        return $deleted;
    }

    public static function is_using($id)
    {
        // Subscription tag check
        $count = DB::table('subscriptions_tags')
            ->where('tag_id', $id)
            ->get()
            ->count();

        // Check if using
        if ($count > 0) {
            return true;
        }

        // Return not using
        return false;
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
