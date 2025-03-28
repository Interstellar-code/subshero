<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CurrencyModel extends BaseModel
{
    private const TABLE = 'currency_conversion';

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

    public static function get_by_code($currency_code)
    {
        return DB::table(self::TABLE)
            ->where('currency_code', $currency_code)
            ->get()
            ->first();
    }
}
