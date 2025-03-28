<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WebhookLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [];

    public static function get_by_user($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = Auth::id();
        }
        return self::where('user_id', $user_id)
            // ->select('folder.*')
            ->orderBy('name', 'asc')
            ->get();
    }
}
