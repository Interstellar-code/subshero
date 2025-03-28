<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SettingsModel extends BaseModel
{
    private const TABLE = 'config';
    private const DEFAULT_ID = 1;

    public static function get($id = null)
    {
        if (empty($id)) {
            $id = self::DEFAULT_ID;
        }

        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_webhook_logs()
    {
        return DB::table('webhook_logs')
            ->get();
    }

    public static function get_webhook_log($id)
    {
        return DB::table('webhook_logs')
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_arr($id)
    {
        return DB::table('versions')
            ->where('id', $id)
            ->get()
            ->first();
        // ->first()
        // ->toArray();
    }

    public static function get_all()
    {
        return DB::table(self::TABLE)
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
}
