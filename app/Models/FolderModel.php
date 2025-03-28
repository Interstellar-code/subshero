<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FolderModel extends BaseModel
{
    private const TABLE = 'folder';
    private static $user_id = NULL;

    // public const _type = ['Unknown', 'Subscription', 'Trial', 'Lifetime', 'Revenue'];
    //

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function getAll()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = Auth::id();
        }
        return DB::table(self::TABLE)
            ->leftJoin('users', 'folder.user_id', '=', 'users.id')
            ->where('folder.user_id', $user_id)
            ->select('folder.*')
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function get_default_folder_id($user_id = NULL)
    {
        $data = DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->where('is_default', 1)
            ->get()
            ->first();

        if (empty($data->id)) {
            return 0;
        } else {
            return $data->id;
        }
    }

    public static function create($data)
    {
        $id = DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
        UsersPlan::update_total_folder();
        return $id;
    }

    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    public static function del($id)
    {
        $deleted = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
        UsersPlan::update_total_folder();
        return $deleted;
    }

    public static function get_or_create($name, $user_id = NULL)
    {
        if (empty($name)) {
            return NULL;
        }

        $data = DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->where('name', $name)
            ->get()
            ->first();

        if (empty($data)) {
            return DB::table(self::TABLE)
                ->insertGetId(parent::_add_created([
                    'user_id' => Auth::id(),
                    'name' => $name,
                    'price_type' => 'All'
                ]));
        } else {
            return $data->id;
        }
    }

    public static function clear_default()
    {
        return DB::table(self::TABLE)
            ->where('user_id', Auth::id())
            ->update([
                'is_default' => 0,
            ]);
    }

    public static function is_using($id)
    {
        $count = DB::table('subscriptions')
            ->where('folder_id', $id)
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
