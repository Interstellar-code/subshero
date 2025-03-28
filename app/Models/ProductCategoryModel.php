<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductCategoryModel extends BaseModel
{
    private const TABLE = 'product_categories';
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
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function get_id($name)
    {
        $data = DB::table(self::TABLE)
            ->where('name', $name)
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
        $data_id = DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));

        return $data_id;
    }

    public static function do_update($id, $data)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);

        return $status;
    }
}
