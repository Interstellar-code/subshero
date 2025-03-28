<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TokenModel extends BaseModel
{
    private const TABLE = 'tokens';
    private static $default = [];

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_by_email($email, $type)
    {
        return DB::table(self::TABLE)
            ->where('email', $email)
            ->where('type', $type)
            ->get()
            ->last();
    }

    public static function retrieve($token)
    {
        return DB::table(self::TABLE)
            ->where('token', Hash::make($token))
            ->get()
            ->first();
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

    public static function used($id, $user_id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update([
                'used_at' => date(APP_TIMESTAMP_FORMAT),
                'used_by' => $user_id,
                'status' => 2,
            ]);
    }

    public static function validate($token_type, $token_value, $email)
    {
        $token = self::get_by_email($email, $token_type);

        if (empty($token) || empty($token->expire_at)) {
            return false;
        }

        if (Carbon::createFromFormat(APP_TIMESTAMP_FORMAT, $token->expire_at)->isPast()) {
            return false;
        }

        if ($token->status != 1) {
            return false;
        }

        if (!Hash::check($token_value, $token->token)) {
            return false;
        }

        return $token;
    }

    private static function set_default_val($data)
    {
        // Default values
        self::$default = [
            'created_at' => date(APP_TIMESTAMP_FORMAT),
            'created_by' => $data['table_row_id'],
        ];

        foreach (self::$default as $key => $val) {
            if (!isset($data[$key])) {
                $data[$key] = $val;
            }
        }

        return $data;
    }
}
