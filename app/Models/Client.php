<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Client extends BaseModel
{
    // public const _type = ['Unknown', 'Subscription', 'Trial', 'Lifetime', 'Revenue'];
    //

    public static function get($id)
    {
        return DB::table('clients')
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_all()
    {
        return DB::table('clients')
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = Auth::id();
        }
        return DB::table('clients')
            ->where('user_id', $user_id)
            ->select('clients.*', 'clients.name as client_name')
            ->get();
    }
}
