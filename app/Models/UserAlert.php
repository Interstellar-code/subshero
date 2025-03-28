<?php

namespace App\Models;

use App\BaseModel;
use App\Models\Traits\AlertTypesSetTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;

class UserAlert extends BaseModel
{
    use AlertTypesSetTrait;
    public $table = 'users_alert';
    private static $user_id = NULL;
    protected $guarded = [];
    public static $sortableColumns = 'id,alert_name,is_default,created_at';

    protected static function booted(): void
    {
        static::created(function() {
            UsersPlan::update_total_alerts();
        });
    }

    public static function get_by_user($user_id = NULL)
    {
        return self::whereUserId($user_id ?? Auth::id())
            ->orderBy('alert_name')
            ->get();
    }

    public static function get_by_name($name, $user_id = NULL)
    {
        return self::whereIn('user_id', [0, $user_id ?? Auth::id()])
            ->whereAlertName($name)
            ->first();
    }

    public static function get_id($name, $user_id = NULL)
    {
        $data = self::get_by_name($name, $user_id);
        return $data->id ?? 0;
    }

    public static function do_update($id, $data)
    {
        $user_alert = self::find($id);
        if ($user_alert) {
            return $user_alert->update($data);
        } else {
            return false;
        }
    }

    public static function del($id)
    {
        $user_alert = self::find($id);
        if ($user_alert) {
            $user_alert->delete();
            UsersPlan::update_total_alerts();
            return true;
        } else {
            return false;
        }
    }

    public static function is_using($id, $user_id = NULL)
    {
        // Subscription tag check
        $count = Subscription::where([
            'alert_id' => $id,
            'user_id' => $user_id ?? Auth::id(),
        ])->count();

        return $count > 0;
    }

    public static function clear_default($alert_subs_type, $user_id = NULL)
    {
        if (!$alert_subs_type) {
            return false;
        }

        return self::where([
            'alert_subs_type' => $alert_subs_type,
            'user_id' => $user_id ?? Auth::id(),
        ])->update([
            'is_default' => 0,
        ]);
    }
}
