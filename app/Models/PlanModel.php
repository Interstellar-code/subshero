<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PlanModel extends BaseModel
{
    private const TABLE = 'plans';

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_by_product_id($product_id, $variation_id = null)
    {
        if ($variation_id === 0) {
            $variation_id = null;
        }
        return DB::table(self::TABLE)
            ->where('product_id', $product_id)
            ->where('variation_id', $variation_id)
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
}
