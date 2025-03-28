<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Library\NotificationEngine;

class UserModel extends BaseModel
{
    private const TABLE = 'users';
    private static $user_id = null;

    // public function __construct()
    // {
    //     self::$me = DB::table(self::TABLE)
    //         ->where('id', Auth::id())
    //         ->get()
    //         ->first();
    // }

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_me()
    {
        return DB::table(self::TABLE)
            ->where('id', Auth::id())
            ->get()
            ->first();
    }

    public static function get_by_email($email)
    {
        return DB::table(self::TABLE)
            ->where('email', $email)
            ->get()
            ->first();
    }

    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public static function me()
    {
        return DB::table(self::TABLE)
            ->where('id', Auth::id())
            ->get()
            ->first();
    }

    public static function get_profile()
    {
        return DB::table('users_profile')
            ->where('user_id', Auth::id())
            ->get()
            ->first();
    }

    public static function get_alert_preference()
    {
        return DB::table('users_alert_preferences')
            ->where('user_id', Auth::id())
            ->get()
            ->first();
    }

    public static function get_plan()
    {
        return DB::table('users_plans')
            ->leftJoin('plans', 'plans.id', '=', 'users_plans.plan_id')
            ->where('users_plans.user_id', Auth::id())
            ->select(
                'plans.*',
                'users_plans.total_subs',
                'users_plans.total_folders',
                'users_plans.total_tags',
                'users_plans.total_contacts',
                'users_plans.total_pmethods',
                'users_plans.total_alert_profiles',
                'users_plans.total_teams',
                'users_plans.total_webhooks',
                'users_plans.total_storage',
            )
            ->get()
            ->first();
    }

    public static function limit_reached($type)
    {
        $user_plan = self::get_plan();

        if (empty($user_plan)) {
            return true;
        }

        if (gettype($type) != 'array') {
            $type = [$type];
        }

        foreach ($type as $val) {
            switch ($val) {
                case 'subscription':
                    if (isset($user_plan->limit_subs) && isset($user_plan->total_subs)) {
                        if ($user_plan->total_subs >= $user_plan->limit_subs) {
                            return true;
                        }
                    }
                    break;

                case 'folder':
                    if (isset($user_plan->limit_folders) && isset($user_plan->total_folders)) {
                        if ($user_plan->total_folders >= $user_plan->limit_folders) {
                            return true;
                        }
                    }
                    break;

                case 'tag':
                    if (isset($user_plan->limit_tags) && isset($user_plan->total_tags)) {
                        if ($user_plan->total_tags >= $user_plan->limit_tags) {
                            return true;
                        }
                    }
                    break;

                case 'contact':
                    if (isset($user_plan->limit_contacts) && isset($user_plan->total_contacts)) {
                        if ($user_plan->total_contacts >= $user_plan->limit_contacts) {
                            return true;
                        }
                    }
                    break;

                case 'payment_method':
                    if (isset($user_plan->limit_pmethods) && isset($user_plan->total_pmethods)) {
                        if ($user_plan->total_pmethods >= $user_plan->limit_pmethods) {
                            return true;
                        }
                    }
                    break;

                case 'alert':
                    if (isset($user_plan->limit_alert_profiles) && isset($user_plan->total_alert_profiles)) {
                        if ($user_plan->total_alert_profiles >= $user_plan->limit_alert_profiles) {
                            return true;
                        }
                    }
                    break;

                case 'webhook':
                    if (isset($user_plan->limit_webhooks) && isset($user_plan->total_webhooks)) {
                        if ($user_plan->total_webhooks >= $user_plan->limit_webhooks) {
                            return true;
                        }
                    }
                    break;

                case 'team':
                    if (isset($user_plan->limit_teams) && isset($user_plan->total_teams)) {
                        if ($user_plan->total_teams >= $user_plan->limit_teams) {
                            return true;
                        }
                    }
                    break;

                case 'storage':
                    if (isset($user_plan->limit_storage) && isset($user_plan->total_storage)) {
                        if ($user_plan->total_storage >= $user_plan->limit_storage) {
                            return true;
                        }
                    }
                    break;

                default:
                    return false;
            }
        }

        return false;
    }

    public static function limit_set($type)
    {
        $user_plan = self::get_plan();

        if (empty($user_plan)) {
            return true;
        }

        if (gettype($type) != 'array') {
            $type = [$type];
        }

        foreach ($type as $val) {
            switch ($val) {
                case 'subscription':
                    if (isset($user_plan->limit_subs) && isset($user_plan->total_subs)) {
                        if ($user_plan->total_subs >= $user_plan->limit_subs) {
                            return true;
                        }
                    }
                    break;

                case 'folder':
                    if (isset($user_plan->limit_folders) && isset($user_plan->total_folders)) {
                        if ($user_plan->total_folders >= $user_plan->limit_folders) {
                            return true;
                        }
                    }
                    break;

                case 'tag':
                    if (isset($user_plan->limit_tags) && isset($user_plan->total_tags)) {
                        if ($user_plan->total_tags >= $user_plan->limit_tags) {
                            return true;
                        }
                    }
                    break;

                case 'contact':
                    if (isset($user_plan->limit_contacts) && isset($user_plan->total_contacts)) {
                        if ($user_plan->total_contacts >= $user_plan->limit_contacts) {
                            return true;
                        }
                    }
                    break;

                case 'payment_method':
                    if (isset($user_plan->limit_pmethods) && isset($user_plan->total_pmethods)) {
                        if ($user_plan->total_pmethods >= $user_plan->limit_pmethods) {
                            return true;
                        }
                    }
                    break;

                case 'alert':
                    if (isset($user_plan->limit_alert_profiles) && isset($user_plan->total_alert_profiles)) {
                        if ($user_plan->total_alert_profiles >= $user_plan->limit_alert_profiles) {
                            return true;
                        }
                    }
                    break;

                case 'team':
                    if (isset($user_plan->limit_teams) && isset($user_plan->total_teams)) {
                        if ($user_plan->total_teams >= $user_plan->limit_teams) {
                            return true;
                        }
                    }
                    break;

                default:
                    return false;
            }
        }

        return false;
    }

    public static function get_payments()
    {
        return DB::table('users_payment_methods')
            ->where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->get();
    }

    public static function get_contacts()
    {
        $user_plan = self::get_plan();
        return DB::table('users_contacts')
            ->where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->limit($user_plan->limit_contacts ?? 0)
            ->get();
    }

    public static function get_tags()
    {
        return DB::table('tags')
            ->where('user_id', Auth::id())
            ->orderBy('name', 'asc')
            ->get();
    }

    // public static function get_billing()
    // {
    //     return DB::table('users_billing')
    //         ->leftJoin('plans', 'plans.id', '=', 'users_plans.plan_id')
    //         ->where('users_plans.user_id', Auth::id())
    //         ->select('plans.*')
    //         ->get()
    //         ->first();
    // }

    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId($data);
    }

    public static function do_update($id, $data, $event_type = null, $other = null)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);

        if ($other === null) {
            // Create event logs
            NotificationEngine::staticModel('event')::create([
                'user_id' => Auth::id() ? Auth::id() : $id,
                'event_type' => $event_type ? $event_type : 'user_settings',
                'event_type_status' => 'update',
                'event_status' => 1,
                'table_name' => self::TABLE,
                'table_row_id' => $id,
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
            ]);
        }

        return $status;
    }

    public static function create_profile_default($user_id, $event_type = null)
    {
        DB::table('users_alert_preferences')->insert([
            'user_id' => $user_id,
            'time_period' => 7,
            'time_cycle' => 1,
            'time' => '09:00:00',
            'monthly_report' => 0,
            'monthly_report_time' => '00:00:00',
        ]);
        DB::table('users_billing')->insert([
            'user_id' => $user_id,
        ]);
        DB::table('users_calendar')->insert([
            'user_id' => $user_id,
        ]);
        DB::table('users_payment_methods')->insert([
            [
                'user_id' => $user_id,
                'payment_type' => 'PayPal',
                'name' => 'PayPal',
            ],
            [
                'user_id' => $user_id,
                'payment_type' => 'Credit Card',
                'name' => 'Credit Card',
            ],
            [
                'user_id' => $user_id,
                'payment_type' => 'Others',
                'name' => 'Others',
            ],
        ]);
        DB::table('users_plans')->insert([
            'user_id' => $user_id,
            'plan_id' => FREE_PLAN_ID,
        ]);
        DB::table('users_profile')->insert([
            'user_id' => $user_id,
            'timezone' => 'America/New_York',
            'currency' => 'USD',
            'language' => 'english',
        ]);
        DB::table('users_report')->insert([
            'user_id' => $user_id,
        ]);
        DB::table('users_tour_status')->insert([
            'user_id' => $user_id,
        ]);

        DB::table(self::TABLE)
            ->where('id', $user_id)
            ->update([
                'status' => 0,
            ]);

        self::set_default_picture($user_id);

        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'user_id' => Auth::id() ? Auth::id() : $user_id,
            'event_type' => $event_type ? $event_type : 'user_registration',
            'event_type_status' => 'create',
            'event_status' => 1,
            'table_name' => self::TABLE,
            'table_row_id' => $user_id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);

        return true;
    }

    public static function update_alert_preference($user_id, $data)
    {
        return DB::table('users_alert_preferences')
            ->where('user_id', $user_id)
            ->update($data);
    }

    public static function update_profile($user_id, $data)
    {
        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'user_id' => Auth::id(),
            'event_type' => 'user_settings',
            'event_type_status' => 'update',
            'event_status' => 1,
            'table_name' => 'users_profile',
            'table_row_id' => $user_id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);

        return DB::table('users_profile')
            ->where('user_id', $user_id)
            ->update($data);
    }

    public static function create_payment($data)
    {
        return DB::table('users_payment_methods')
            ->insertGetId($data);
    }

    public static function delete_payment($user_id = null)
    {
        return DB::table('users_payment_methods')->where('user_id', parent::_get_user_id($user_id))->delete();
    }

    public static function contact_create($data)
    {
        $contact_id = DB::table('users_contacts')
            ->insertGetId($data);
        UsersPlan::update_total_contacts();
        return $contact_id;
    }

    public static function contact_get($contact_id)
    {
        return DB::table('users_contacts')
            ->where('id', $contact_id)
            ->get()
            ->first();
    }

    public static function contact_update($id, $data)
    {
        return DB::table('users_contacts')
            ->where('id', $id)
            ->update($data);
    }

    public static function contact_delete($id)
    {
        $status = DB::table('users_contacts')->where('id', $id)->delete();
        UsersPlan::update_total_contacts();
        return $status;
    }

    public static function set_plan($user_id, $plan_id)
    {
        $user = self::get($user_id);
        $plan = PlanModel::get($plan_id);

        if (empty($user) || empty($plan)) {
            return false;
        }

        self::do_update($user_id, [
            'team_user_id' => null,
        ], 'user_plan');

        $user_plan = DB::table('users_plans')
            ->where('user_id', $user->id)
            ->get()
            ->first();

        if (empty($user_plan)) {
            DB::table('users_plans')
                ->insertGetId([
                    'user_id' => $user_id,
                    'plan_id' => $plan_id,
                ]);
        } else {
            DB::table('users_plans')
                ->where('user_id', $user_id)
                ->update([
                    'plan_id' => $plan_id,
                ]);
        }

        return true;
    }

    public static function set_default_picture($user_id)
    {
        // Get files
        $file_all = array_filter(Storage::disk('local')->files('system/user/avatars'), function ($item) {
            return strpos($item, '.png');
        });

        if (!count($file_all)) {
            return false;
        }

        // Randomize
        $index = mt_rand(0, count($file_all) - 1);
        if (isset($file_all[$index])) {

            // Get file information
            $source = $file_all[$index];
            $extension = pathinfo($source, PATHINFO_EXTENSION);
            // $name = pathinfo($file, PATHINFO_FILENAME);
            $name = Str::random(40);
            $destination = "client/1/user/$user_id/avatar.jpg";

            // Copy file and set as profile picture
            if (Storage::disk('local')->missing($destination)) {
                Storage::disk('local')->copy($source, $destination);

                UserModel::do_update($user_id, [
                    'image' => $destination,
                ], null, false);
            }
        }
    }

    public static function tour_finished($user_id = null)
    {
        $users_tour_status = DB::table('users_tour_status')
            ->where('user_id', self::get_user_id($user_id))
            ->get()
            ->first();

        if (empty($users_tour_status->id)) {
            return DB::table('users_tour_status')->insertGetId([
                'user_id' => self::get_user_id($user_id),
                'status' => 1,
                'updated_at' => lib()->do->timezone_convert(),
            ]);
        } else {
            return DB::table('users_tour_status')
                ->where('id', $users_tour_status->id)
                ->update([
                    'status' => 1,
                    'updated_at' => lib()->do->timezone_convert(),
                ]);
        }
    }

    public static function get_tour_status($user_id = null)
    {
        $users_tour_status = DB::table('users_tour_status')
            ->where('user_id', self::get_user_id($user_id))
            ->get()
            ->first();

        if (empty($users_tour_status->id)) {
            return 0;
        } else {
            return $users_tour_status->status;
        }
    }

    private static function get_user_id($user_id = null)
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

    public static function reset($user_id)
    {
        // Create event logs
        NotificationEngine::staticModel('event')::create([
            'user_id' => $user_id,
            'event_type' => 'account_reset',
            'event_type_status' => 'create',
            'event_status' => 1,
            'table_name' => 'users',
            'table_row_id' => $user_id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
        ]);
    }
}
