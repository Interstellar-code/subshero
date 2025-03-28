<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class UsersPlan extends Model
{
    use HasFactory;

    public $timestamps = false;

    public static function update_total_all()
    {
        $methods = get_class_methods(UsersPlan::class);
        foreach ($methods as $method) {
            if (strpos($method, 'update_total_') === 0 && $method != 'update_total_all') {
                UsersPlan::$method();
            }
        }
    }

    public static function update_total_contacts()
    {
        $count_contacts = UsersContact::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_contacts = $count_contacts;
        $users_plan->save();
    }

    public static function update_total_alerts()
    {
        $count_alerts = UserAlert::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_alert_profiles = $count_alerts;
        $users_plan->save();
    }

    public static function update_total_tags()
    {
        $count_tags = Tag::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_tags = $count_tags;
        $users_plan->save();
    }

    public static function update_total_payment()
    {
        $count_payment = PaymentMethod::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_pmethods = $count_payment;
        $users_plan->save();
    }

    public static function update_total_subs()
    {
        $count_subs = Subscription::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_subs = $count_subs;
        $users_plan->save();
    }

    public static function update_total_folder()
    {
        $count_folder = Folder::whereUserId(Auth::id())->count();
        $users_plan = UsersPlan::whereUserId(Auth::id())->first();
        $users_plan->total_folders = $count_folder;
        $users_plan->save();
    }
}
