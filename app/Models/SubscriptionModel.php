<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Library\NotificationEngine;

class SubscriptionModel extends BaseModel
{
    private const TABLE = 'subscriptions';
    protected $table = 'subscriptions';
    private static $user_id = NULL;
    public $timestamps = false;

    // public const _type = ['Unknown', 'Subscription', 'Trial', 'Lifetime', 'Revenue'];
    //

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

    public static function get_all_for_cron()
    {
        return DB::table(self::TABLE)
            ->where('type', 1)
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            // ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'subscriptions.category_id', '=', 'product_categories.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->leftJoin('users_payment_methods', 'subscriptions.payment_mode_id', '=', 'users_payment_methods.id')
            ->leftJoin('users_alert', 'subscriptions.alert_id', '=', 'users_alert.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select(
                'subscriptions.*',
                'products.product_name as brand_name',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'folder.color as folder_color',
                'folder.name as folder_name',
                'users_payment_methods.name as payment_method_name',
                'users_alert.alert_name as alert_name',
            )
            ->get();
    }

    public static function get_by_folder($folder_id, $user_id = NULL)
    {
        return DB::table(self::TABLE)
            // ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->leftJoin('products', 'subscriptions.brand_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type', '=', 'product_types.id')
            ->leftJoin('product_categories', 'subscriptions.category_id', '=', 'product_categories.id')
            ->leftJoin('folder', 'subscriptions.folder_id', '=', 'folder.id')
            ->leftJoin('users_payment_methods', 'subscriptions.payment_mode_id', '=', 'users_payment_methods.id')
            ->leftJoin('users_alert', 'subscriptions.alert_id', '=', 'users_alert.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->where('subscriptions.folder_id', $folder_id)
            ->select(
                'subscriptions.*',
                'products.product_name as brand_name',
                'product_types.name as product_type_name',
                'product_categories.name as product_category_name',
                'folder.color as folder_color',
                'folder.name as folder_name',
                'users_payment_methods.name as payment_method_name',
                'users_alert.alert_name as alert_name',
            )
            ->get();
    }

    // create a function get_by_user_folder
    public static function get_by_folder_user($folder_id = null, $user_id = NULL)
    {
        if (empty($folder_id)) {
            return self::get_by_user($user_id);
        } else {
            return self::get_by_folder($folder_id, $user_id);
        }
    }

    public static function get_last_history($subscription_id, $type)
    {
        if ($type != 3) {
            return DB::table('subscriptions_history')
                ->where('subscription_id', $subscription_id)
                ->orderBy('id', 'desc')
                ->get()
                ->first();
        }
    }

    public static function create($data)
    {
        $id = DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
        UsersPlan::update_total_subs();
        return $id;
    }

    public static function create_tag($data)
    {
        // Insert single data
        return DB::table('subscriptions_tags')
            ->insertGetId($data);
    }

    public static function create_tags($data_arr)
    {
        // Insert multiple data
        return DB::table('subscriptions_tags')
            ->insert($data_arr);
    }

    public static function delete_tags($subscription_id)
    {
        // Delete multiple data
        return DB::table('subscriptions_tags')
            ->where('user_id', Auth::id())
            ->where('subscription_id', $subscription_id)
            ->delete();
    }

    public static function do_update($id, $data)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);

        return $status;
    }

    public static function get_tags($subscription_id)
    {
        return DB::table('subscriptions_tags')
            ->join('tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
            ->where('subscriptions_tags.user_id', self::get_user_id())
            ->where('subscriptions_tags.subscription_id', $subscription_id)
            ->select('tags.*', 'subscriptions_tags.tag_id')
            ->get();
    }

    public static function get_tags_arr($subscription_id)
    {
        $tags = DB::table('subscriptions_tags')
            ->join('tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
            ->where('subscriptions_tags.user_id', self::get_user_id())
            ->where('subscriptions_tags.subscription_id', $subscription_id)
            ->select('tags.*', 'subscriptions_tags.tag_id')
            ->get();

        // Convert to key value paired array
        $data = [];
        if (!empty($tags)) {
            foreach ($tags as $key => $val) {
                $data[$val->id] = $val->name;
            }
        }
        return $data;
    }


    public static function del($id)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
        if ($status) {
            DB::table('subscriptions_tags')
                ->where('subscription_id', $id)
                ->delete();
            UsersPlan::update_total_subs();
            UsersPlan::update_total_tags();
        }
        return $status;
    }


    public static function refund($id)
    {
        DB::table(self::TABLE)
            ->where('id', $id)
            ->update([
                'status' => 3,
            ]);

        DB::table('subscriptions_history')
            ->where('subscription_id', $id)
            ->delete();

        return true;
    }


    public static function cancel($id)
    {
        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->update([
                'status' => 2,
            ]);


        $subscription = self::get($id);
        if (empty($subscription)) {
            return false;
        }

        // Add into history
        else {
            SubscriptionHistory::create_history($subscription);
            return true;
        }
    }

    public static function get_event_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('brands', 'subscriptions.brand_id', '=', 'brands.id')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->select('subscriptions.*', 'brands.name as brand_name')
            ->get();
    }

    public static function get_subs_chart_by_folder($folder_id, $user_id = NULL)
    {
        return DB::table('subscriptions')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->where('subscriptions.folder_id', $folder_id)
            ->where('type', 1)

            // Display from last 1 year data
            ->whereBetween('payment_date', [
                date('Y-m-d', strtotime('-12 month')),
                date('Y-m-d'),
            ])

            ->select('subscriptions.*')
            ->orderBy('subscriptions.payment_date')
            ->get();
    }

    public static function get_ltd_chart_by_folder($folder_id, $user_id = NULL)
    {
        return DB::table('subscriptions')
            ->where('subscriptions.user_id', self::get_user_id($user_id))
            ->where('subscriptions.folder_id', $folder_id)
            ->where('type', 3)

            // Display from last 1 year data
            ->whereBetween('payment_date', [
                date('Y-m-d', strtotime('-12 month')),
                date('Y-m-d'),
            ])

            ->select('subscriptions.*')
            ->orderBy('subscriptions.payment_date')
            ->get();
    }


    public static function set_refund_date($subscription_id)
    {
        $subscription = DB::table(self::TABLE)
            ->where('id', $subscription_id)
            ->get()
            ->first();

        if (!empty($subscription->id)) {
            $flag = true;

            // Check for status
            if ($subscription->status != 1) {
                return false;
            }

            $user_alert_found = false;

            if (empty($subscription->alert_id)) {
                return false;
            } else {

                $user_profile = DB::table('users_profile')
                    ->where('user_id', $subscription->user_id)
                    ->get()
                    ->first();

                $subscription_user_alert = UserAlert::whereId($subscription->alert_id)
                    ->whereFindInSetOr('alert_types', ['email', 'browser'])
                    ->whereIn('alert_condition', [1, 3])
                    ->first();

                if (empty($subscription_user_alert->id)) {
                    return false;
                } else {
                    $user_alert_found = true;
                    $user_alert = $subscription_user_alert;
                    $user_profile->timezone = $user_alert->timezone;
                }
            }

            if (!empty($subscription->refund_date)) {

                // Set default data
                $alert_timezone = APP_TIMEZONE;
                $alert_time_period = 0;
                $alert_time_at = '09:00:00';


                // Day Before Refund date
                if (empty($user_profile->id) || empty($user_alert->id)) {
                    $flag = false;
                    $subscription->refund_date = null;
                }

                if ($flag) {

                    // Check for empty value and get user timezone
                    if (!empty($user_profile->timezone)) {
                        $alert_timezone = $user_profile->timezone;
                    }

                    // Check for empty value and get user time period for email alerts
                    if (!empty($user_alert->time_period)) {
                        $alert_time_period = $user_alert->time_period;
                    }

                    // Check for empty value and get user time period for email alerts
                    if (!empty($user_alert->time)) {
                        $alert_time_at = $user_alert->time;
                    }

                    $refund_date = date('Y-m-d', strtotime($subscription->refund_date . ' - ' . $user_alert->time_period . ' days'));

                    $schedule_datetime = Carbon::createFromFormat('Y-m-d', $refund_date, $subscription->timezone);
                    $schedule_datetime->setTimezone($alert_timezone);
                    $schedule_datetime->setTimeFromTimeString($alert_time_at);
                }
            }
            if (isset($alert_timezone)) {
                NotificationEngine::refund_create_or_update([
                    'subscription' => $subscription,
                    'alert_timezone' => $alert_timezone,
                    'schedule_datetime' => $schedule_datetime,
                ]);
            }
        }
    }




    private static function get_user_id($user_id = NULL)
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

    public static function create_new_history($subscription_id)
    {
        // Add history
        $subscription = SubscriptionModel::find($subscription_id);
        if (!$subscription) {
            return false;
        }
        $subscription->next_payment_date = $subscription->payment_date;
        $subscription->payment_date_upd = $subscription->payment_date;
        $subscription->save();

        if (!empty($subscription->status) && $subscription->status == 1) {

            if ($subscription->type != 3) {
                $history = SubscriptionHistoryModel::get_by_sub_id($subscription_id);

                if (empty($history->id)) {
                    return SubscriptionHistory::create_history($subscription);
                } else {
                    return $history->id;
                }
            }
        }
    }








    // ----------------------------------------------------- Subscription page top chart data -----------------------------------------------------
    public static function get_subscription_area_chart(int $days = 0, $folder_id = null)
    {
        $data = [];
        $data['days'] = 30;
        $data['folder_id'] = $folder_id;
        $all_schedules = self::_get_subscription_area_chart_data($folder_id);
        $today = date('Y-m-d');

        // dd($all_schedules);
        // dd(array_reverse($all_schedules));

        // sort $all_schedules by payment_date
        $all_schedules = collect($all_schedules)->sortBy('calc_next_payment_date');

        // Check for all years
        if ($days == 0) {

            // foreach ($all_schedules as &$schedule) {
            //     $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
            // }

            $output = clone ($all_schedules);
            // return $all_schedules;
        }

        // Limit to specific days
        else {
            // Only 30, 90 days
            if ($days > 0) {
                $days--;
            }

            $start_date = date('Y-m-d', strtotime("-$days days"));
            $end_date = date('Y-m-d');

            $output = [];

            foreach ($all_schedules as $schedule) {
                if (!empty($schedule->calc_next_payment_date) && $schedule->calc_next_payment_date >= $start_date && $schedule->calc_next_payment_date <= $end_date) {
                    $schedule->month = $schedule->calc_next_payment_date;
                    // $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
                    $output[] = $schedule;
                }
            }

            // return $output;
        }



        $subscription_total_count = 0;
        $subscription_total_value = 0;
        $subscription_monthly_value = 0;
        $subscription_id_all = [];

        foreach ($output as &$schedule) {
            $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
            $subscription_total_value += $schedule->price;

            if (!in_array($schedule->id, $subscription_id_all)) {
                $subscription_id_all[] = $schedule->id;
                $subscription_total_count++;
            }
        }

        // Sorting for KoolReport
        $area_chart_data_sorted = [];
        foreach ($output as $val) {
            $area_chart_data_sorted[] = $val;
        }

        $output = $area_chart_data_sorted;





        // Subscription top section calculation

        $subscription_output = clone ($all_schedules);

        $subscription_total_count = 0;
        $subscription_total_value = 0;
        $subscription_monthly_value = 0;
        $monthly_array_cost = [];
        $subscription_id_all = [];

        foreach ($subscription_output as &$schedule) {
            $schedule->calc_next_payment_date_formatted = date('d M', strtotime($schedule->calc_next_payment_date));
            $subscription_total_value += $schedule->price;

            if (!in_array($schedule->id, $subscription_id_all)) {
                $subscription_id_all[] = $schedule->id;
                $subscription_total_count++;
            }

            // Calculate monthly value
            // $schedule->monthly_value = SubscriptionModel::get_monthly_value($schedule);
            // $subscription_monthly_value += $schedule->monthly_value;


            // Check if once
            if ($schedule->recurring == 0) {
                $monthly_array_cost[$schedule->id] = $schedule->price;
            }

            // Check if recurring
            else if ($schedule->recurring == 1) {
                // Calculate monthly value
                // Daily billing cycle
                if ($schedule->billing_cycle == 1) {

                    // Daily -> Monthly cost
                    if (!isset($monthly_array_cost[$schedule->id])) {
                        $price = $schedule->price;
                        $frequency = $schedule->billing_frequency;
                        $monthly_price = ($price / $frequency) * 30;
                        $monthly_array_cost[$schedule->id] = $monthly_price;
                    }
                }

                // Weekly billing cycle
                else if ($schedule->billing_cycle == 2) {

                    // Weekly -> Monthly cost
                    if (!isset($monthly_array_cost[$schedule->id])) {
                        $price = $schedule->price;
                        $frequency = $schedule->billing_frequency;
                        $monthly_price = ($price / ($frequency * 7)) * 30;
                        $monthly_array_cost[$schedule->id] = $monthly_price;
                    }
                }

                // Monthly billing cycle
                else if ($schedule->billing_cycle == 3) {

                    // Monthly -> Monthly cost
                    if (!isset($monthly_array_cost[$schedule->id])) {
                        $price = $schedule->price;
                        $frequency = $schedule->billing_frequency;
                        $monthly_price = $price / $frequency;
                        $monthly_array_cost[$schedule->id] = $monthly_price;
                    }
                }

                // Yearly billing cycle
                else if ($schedule->billing_cycle == 4) {

                    // Yearly -> Monthly cost
                    if (!isset($monthly_array_cost[$schedule->id])) {
                        $price = $schedule->price;
                        $frequency = $schedule->billing_frequency;
                        $monthly_price = $price / ($frequency * 12);
                        $monthly_array_cost[$schedule->id] = $monthly_price;
                    }
                }
            }
        }

        $subscription_monthly_value = number_format((float)(array_sum($monthly_array_cost)), 2, '.', '');



        // Top section calculation

        // count total months in $subscription_output
        // if (count($subscription_output) > 0) {
        // // $subscription_monthly_value = $subscription_total_value / count($subscription_output);

        // $to = Carbon::createFromFormat('Y-m-d', $subscription_output[0]->calc_next_payment_date);
        // $from = Carbon::createFromFormat('Y-m-d', $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
        // $diff_in_days = $to->diffInDays($from);
        // // $diff_in_months = $to->diffInMonths($from);
        // $diff_in_months = $diff_in_days / 30;
        // // print_r($diff_in_months); // Output: 1
        // // dd($diff_in_months);

        // // dd($subscription_output[0]->calc_next_payment_date, $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
        // // dd($diff_in_days, $diff_in_months, $subscription_total_value, number_format((float)($subscription_total_value / $diff_in_months), 2, '.', ''));

        // if ($diff_in_months < 1) {
        //     $diff_in_months = 1;
        // }

        // // $diff_in_months = round($diff_in_months);

        // if ($subscription_total_value > 0 && $diff_in_months > 0) {
        //     $subscription_monthly_value = number_format((float)($subscription_total_value / $diff_in_months), 2, '.', '');
        // } else if ($diff_in_months == 0) {
        //     $subscription_monthly_value = $subscription_total_value;
        // }

        // if ($subscription_monthly_value < 0) {
        //     $subscription_monthly_value = 0;
        // }



        // }

        $_SESSION['subscription_monthly_price'] = $subscription_monthly_value;
        $_SESSION['subscription_total_price'] = $subscription_total_value;
        $_SESSION['subscription_total_count'] = $subscription_total_count;

        // return $area_chart_data_sorted;

        // dd($output);
        return $output;
    }

    public static function get_monthly_value($subscription)
    {
        // Calculate for Active and Recurring Subscription
        if ($subscription->type == 1 && $subscription->status == 1 && $subscription->recurring == 1) {

            // Daily
            if ($subscription->billing_cycle == 1 && $subscription->billing_frequency > 0) {
                return ($subscription->price / $subscription->billing_frequency) * 30;
            }

            // Weekly
            else if ($subscription->billing_cycle == 2 && $subscription->billing_frequency > 0) {
                return ($subscription->price / ($subscription->billing_frequency * 7)) * 30;
            }

            // Monthly
            else if ($subscription->billing_cycle == 3 && $subscription->billing_frequency > 0) {
                return ($subscription->price / $subscription->billing_frequency);
            }

            // Yearly
            else if ($subscription->billing_cycle == 4 && $subscription->billing_frequency > 0) {
                return $subscription->price / ($subscription->billing_frequency * 12);
            }
        }
        return 0;
    }

    private static function _get_subscription_area_chart_data($folder_id = null)
    {
        if (empty(local('subscription_folder_id'))) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('type', 1)
                ->where('status', 1)
                ->orderBy('payment_date', 'desc')
                ->get();
        } else {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('folder_id', local('subscription_folder_id'))
                ->where('type', 1)
                ->where('status', 1)
                ->orderBy('payment_date', 'desc')
                ->get();
        }


        $result = [];
        $today = date('Y-m-d');

        $count = 0;
        $j = 0;

        foreach ($data as $subscription) {
            $count++;


            $subscription->next_payment_date = null;
            $subscription->calc_next_payment_date = null;
            $subscription_this = clone ($subscription);


            $first_subscription = clone ($subscription);
            $first_subscription->calc_next_payment_date = $subscription->payment_date;
            $result[] = clone ($first_subscription);


            // Get current datetime in app timezone
            $now = lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]);


            // Supported cycle days
            $cycle_days = [
                1 => 1,
                2 => 7,
                3 => 30,
                4 => 365,
            ];

            $total_days = $cycle_days[$subscription->billing_cycle] * $subscription->billing_frequency;


            // Check if upcoming date is empty then set default
            if (empty($subscription->next_payment_date)) {
                $subscription->next_payment_date = $subscription->payment_date;
                // $subscription->next_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->payment_date, $subscription->timezone)
                //     ->format('Y-m-d H:i:s');
            }

            if (empty($new_payment_date)) {
                $new_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->next_payment_date, $subscription->timezone);
            }


            $new_payment_date_str = $subscription->payment_date;


            if ($subscription->recurring) {
                $j = 0;

                // Check if next payment date is less than current date
                while ($new_payment_date_str < date('Y-m-d')) {
                    $j++;

                    // Calculate by date
                    if (
                        $subscription->billing_type == 2 &&

                        // Month or Year
                        ($subscription->billing_cycle == 3 || $subscription->billing_cycle == 4)
                    ) {

                        $date_1 = $subscription_this->payment_date;
                        // $date_2 = $subscription->payment_date;

                        if (empty($subscription_this->next_payment_date)) {
                            $date_2 = $subscription->payment_date;
                        } else {
                            $date_2 = $subscription->next_payment_date;
                        }

                        $carbon_1 = Carbon::createFromFormat('Y-m-d', $date_1, $subscription->timezone);
                        $carbon_2 = Carbon::createFromFormat('Y-m-d', $date_2, $subscription->timezone);

                        $date_1_day_number = date('d', strtotime($date_1));
                        $date_1_month_number = date('m', strtotime($date_1));
                        $date_1_year = date('Y', strtotime($date_1));
                        $date_1_days = cal_days_in_month(CAL_GREGORIAN, $date_1_month_number, $date_1_year);


                        $date_2_day_number = date('d', strtotime($date_2));
                        $date_2_month_number = date('m', strtotime($date_2));


                        // Month
                        if ($subscription->billing_cycle == 3) {

                            $carbon_temp = clone ($carbon_2);
                            $carbon_temp->endOfMonth();

                            if ($date_1_day_number > 27) {
                                $carbon_temp->addDays(4);
                            }

                            $date_3__add_months = $carbon_temp->diffInMonths($carbon_1);
                            $date_3__add_months += $subscription->billing_frequency;

                            $carbon_3 = clone ($carbon_1);
                            $carbon_3->addMonthsNoOverflow($date_3__add_months);
                            $date_3 = $carbon_3->format('Y-m-d');


                            $date_3_day_number = date('d', strtotime($date_3));
                            $date_3_month_number = date('m', strtotime($date_3));
                            $date_3_year = date('Y', strtotime($date_3));
                            $date_3_days = cal_days_in_month(CAL_GREGORIAN, $date_3_month_number, $date_3_year);


                            if ($date_1_day_number > 27) {

                                if ($date_1_day_number > $date_3_day_number) {
                                    if ($date_1_days > $date_3_days) {
                                        $date_4 = $carbon_3->endOfMonth()->format('Y-m-d');
                                    } else {
                                        $date_1_arr = explode('-', $date_1);
                                        $date_3_arr = explode('-', $date_3);

                                        if (count($date_1_arr) == 3 && count($date_3_arr) == 3) {
                                            $date_4 = $date_3_arr[0] . '-' . $date_3_arr[1] . '-' . $date_1_arr[2];
                                        }
                                    }
                                } else {
                                    $date_4 = $date_3;
                                }
                            } else {
                                $date_4 = $date_3;
                            }

                            if (empty($date_4)) {
                                $date_4 = $date_3;
                            }
                        }

                        // Year
                        else if ($subscription->billing_cycle == 4) {
                            $carbon_temp = clone ($carbon_2);
                            $carbon_temp->endOfMonth();

                            if ($date_1_day_number > 27) {
                                $carbon_temp->addDays(4);
                            }

                            $date_3__add_years = $carbon_temp->diffInYears($carbon_1);
                            $date_3__add_years += $subscription->billing_frequency;


                            $carbon_3 = clone ($carbon_1);
                            $carbon_3->addYearNoOverflow($date_3__add_years);
                            $date_3 = $carbon_3->format('Y-m-d');

                            $date_4 = $date_3;
                        }

                        if (empty($date_4)) {
                            $date_4 = $date_3;
                        }


                        $subscription_new_payment_date = Carbon::createFromFormat('Y-m-d', $date_4, $subscription->timezone);
                        // $subscription_new_payment_date->addDays($total_days);
                        $subscription_new_payment_datetime_str = $subscription_new_payment_date->format('Y-m-d H:i:s');


                        // Calculate time period for email alerts from user settings
                        $user_total_days = $total_days;
                        if ($user_total_days > 0) {
                            $total_days = $user_total_days;
                        }


                        $new_payment_date = Carbon::createFromFormat('Y-m-d', $date_4, $subscription->timezone);
                        // $new_payment_date->setTimezone($alert_timezone);
                        // $new_payment_date->setTimeFromTimeString($alert_time_at);


                        // Refund date check
                        // Before Refund Date
                        // $new_payment_date = $new_payment_date->addDays($total_days);
                        //     if ($user_alert_found && $subscription_user_alert->alert_condition == 3 && !empty($subscription->refund_date && $subscription->refund_date >= $now)) {
                        //     $new_payment_date = $new_payment_date->subDays($alert_time_period);
                        // } else {
                        //     $new_payment_date = $new_payment_date->addDays($total_days);
                        // }

                        // $new_payment_date = $new_payment_date->addDays($total_days);
                        $new_payment_date->setTimezone(APP_TIMEZONE);
                        $new_payment_datetime_str = $new_payment_date->format('Y-m-d H:i:s');
                        $new_payment_date_str = $new_payment_date->format('Y-m-d');

                        // $result[] = $new_payment_date_str;

                        $subscription->calc_next_payment_date = $new_payment_date_str;
                        $subscription->next_payment_date = $new_payment_date_str;
                        $subscription_this->next_payment_date = $new_payment_date_str;

                        if ($subscription->calc_next_payment_date <= $today) {
                            $result[] = clone ($subscription);
                        }
                    } else {
                        // continue;

                        // Calculate by days

                        $subscription_new_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->next_payment_date, $subscription->timezone);
                        $subscription_new_payment_date->addDays($total_days);
                        $subscription_new_payment_datetime_str = $subscription_new_payment_date->format('Y-m-d H:i:s');


                        // Calculate time period for email alerts from user settings
                        $user_total_days = $total_days;
                        if ($user_total_days > 0) {
                            $total_days = $user_total_days;
                        }


                        // Refund date check
                        // Before Refund Date
                        $new_payment_date = $new_payment_date->addDays($total_days);
                        //     if ($user_alert_found && $subscription_user_alert->alert_condition == 3 && !empty($subscription->refund_date && $subscription->refund_date >= $now)) {
                        //     $new_payment_date = $new_payment_date->subDays($alert_time_period);
                        // } else {
                        //     $new_payment_date = $new_payment_date->addDays($total_days);
                        // }

                        // $new_payment_date = $new_payment_date->addDays($total_days);
                        $new_payment_date->setTimezone(APP_TIMEZONE);
                        $new_payment_datetime_str = $new_payment_date->format('Y-m-d H:i:s');
                        $new_payment_date_str = $new_payment_date->format('Y-m-d');


                        // $result[] = $new_payment_date_str;
                        $subscription->calc_next_payment_date = $new_payment_date_str;
                        $subscription->next_payment_date = $new_payment_date_str;
                        $subscription_this->next_payment_date = $new_payment_date_str;

                        if ($subscription->calc_next_payment_date <= $today) {

                            // Expiry date check
                            if (empty($subscription->expiry_date)) {
                                $result[] = clone ($subscription);
                            } else {

                                // Expire date in App timezone
                                $expiry_date = lib()->do->timezone_convert([
                                    'from_timezone' => $subscription->timezone,
                                    'to_timezone' => APP_TIMEZONE,
                                    'date_time' => $subscription->expiry_date,
                                ]);

                                // Expire date compare with scheduled date
                                if ($subscription->expiry_date >= $subscription->calc_next_payment_date) {
                                    $result[] = clone ($subscription);
                                }
                            }
                        }
                    }


                    if ($j >= 1000) {
                        // break;
                    }

                    // Reset
                    unset(
                        $date_1,
                        $date_2,
                        $date_3,
                        $date_4,
                        $carbon_1,
                        $carbon_2,
                        $carbon_3,
                        $carbon_temp,
                        $date_1_day_number,
                        $date_1_month_number,
                        $date_1_year,
                        $date_1_days,
                        $date_2_day_number,
                        $date_2_month_number,
                        $date_3__add_months,
                        $date_3_day_number,
                        $date_3_month_number,
                        $date_3_year,
                        $date_3_days,
                        $date_1_arr,
                        $date_3_arr,
                        $date_3__add_years,
                        $event_id,
                    );
                }
            }

            // Non recurring
            else {
                $new_payment_datetime_str = $subscription->payment_date;
                // $subscription_new_payment_datetime_str = $new_payment_datetime_str;
                $i = 101;

                // DB::table('subscriptions')
                //     ->where('id', $subscription->id)
                //     ->update([
                //         'next_payment_date' => $new_payment_datetime_str,
                //         'payment_date_upd' => $new_payment_datetime_str,
                //     ]);
            }

            // Reset
            unset(
                $subscription_this,
                $subscription,
                $history,
                $next_payment_date_1,
                $start_date,
                $now,
                $alert_timezone,
                $alert_time_period,
                $alert_time_at,
                $user_profile,
                $user_alert,
                $this_next_payment_date,
                $total_days,
                $matching,
                $new_payment_date,
                $date_1,
                $date_2,
                $date_3,
                $date_4,
                $carbon_1,
                $carbon_2,
                $carbon_3,
                $carbon_temp,
                $date_1_day_number,
                $date_1_month_number,
                $date_1_year,
                $date_1_days,
                $date_2_day_number,
                $date_2_month_number,
                $date_3__add_months,
                $date_3_day_number,
                $date_3_month_number,
                $date_3_year,
                $date_3_days,
                $date_1_arr,
                $date_3_arr,
                $date_3__add_years,
                $subscription_new_payment_date,
                $subscription_new_payment_datetime_str,
                $user_total_days,
                $new_payment_date,
                $new_payment_datetime_str,
                $new_payment_date_str,
                $old_event,
                $event_status,
                $data,
                $event_id,
                $subscription_event,
                $new_subscription_history,
                $user_alert_found,
                $subscription_user_alert,
                $old_active_event,
            );
        }

        return $result;
    }












    // public static function get_lifetime_drilldown()
    // {
    //     // Load data on page load

    //     // Get Lifetime data
    //     if (empty(local('subscription_folder_id'))) {
    //         $lifetime_data = DB::table('subscriptions')
    //             ->where('user_id', Auth::user()->id)
    //             ->where('status', 1)
    //             ->where('type', 3)
    //             ->get();
    //     } else {
    //         $lifetime_data = DB::table('subscriptions')
    //             ->where('user_id', Auth::user()->id)
    //             ->where('folder_id', local('subscription_folder_id'))
    //             ->where('status', 1)
    //             ->where('type', 3)
    //             ->get();

    //         $folder_data = FolderModel::get(local('subscription_folder_id'));
    //         if (!empty($folder_data->id)) {

    //             // Get folder name to display in the charts
    //             lib()->cache->subscription_folder_name = $folder_data->name;
    //         }
    //     }

    //     $lifetime_total_price = 0;
    //     $lifetime_total_count = 0;
    //     $lifetime_this_year_price = 0;
    //     $lifetime_this_year_count = 0;
    //     $lifetime_active_count = 0;
    //     $lifetime_active_price = 0;
    //     $lifetime_monthly_price = 0;

    //     // Count total price in $lifetime_data
    //     foreach ($lifetime_data as $val) {

    //         // Count item in $lifetime_data
    //         $lifetime_total_count++;
    //         $lifetime_total_price += $val->price;

    //         // Count total this year price in $lifetime_data
    //         if (date('Y', strtotime($val->payment_date)) == date('Y')) {
    //             $lifetime_this_year_count++;
    //             $lifetime_this_year_price += $val->price;
    //         }


    //         // Count total active price in $lifetime_data
    //         if ($val->status == 1) {
    //             $lifetime_active_count++;
    //             $lifetime_active_price += $val->price;
    //         }
    //     }





    //     if (count($lifetime_data) > 0) {

    //         $to = Carbon::createFromFormat('Y-m-d', $lifetime_data[0]->payment_date);
    //         $from = Carbon::createFromFormat('Y-m-d', $lifetime_data[count($lifetime_data) - 1]->payment_date);
    //         $diff_in_days = $to->diffInDays($from);
    //         // $diff_in_months = $to->diffInMonths($from);
    //         $diff_in_months = $diff_in_days / 30;
    //         // print_r($diff_in_months); // Output: 1
    //         // dd($diff_in_months);

    //         // dd($lifetime_data[0]->payment_date, $lifetime_data[count($lifetime_data) - 1]->payment_date);
    //         // dd($diff_in_days, $diff_in_months, $subscription_total_value, number_format((float)($subscription_total_value / $diff_in_months), 2, '.', ''));
    //         if ($diff_in_months < 1) {
    //             $diff_in_months = 1;
    //         }

    //         // $diff_in_months = round($diff_in_months);

    //         if ($lifetime_total_price > 0 && $diff_in_months > 0) {
    //             $lifetime_monthly_price = number_format((float)($lifetime_total_price / $diff_in_months), 2, '.', '');
    //         } else if ($diff_in_months == 0) {
    //             $lifetime_monthly_price = $lifetime_total_price;
    //         }

    //         // dd($diff_in_months, $lifetime_total_price, $lifetime_monthly_price);

    //         if ($lifetime_monthly_price < 0) {
    //             $lifetime_monthly_price = 0;
    //         }
    //     }


    //     // Store in session
    //     $_SESSION['lifetime_total_price'] = $lifetime_total_price;
    //     $_SESSION['lifetime_total_count'] = $lifetime_total_count;
    //     $_SESSION['lifetime_this_year_price'] = $lifetime_this_year_price;
    //     $_SESSION['lifetime_this_year_count'] = $lifetime_this_year_count;
    //     $_SESSION['lifetime_active_count'] = $lifetime_active_count;
    //     $_SESSION['lifetime_active_price'] = $lifetime_active_price;
    //     $_SESSION['lifetime_monthly_price'] = $lifetime_monthly_price;
    // }






    public static function get_days_from_period($period_str)
    {
        $days = 0;
        $now = Carbon::now();
        $start = Carbon::now();
        $end = Carbon::now();

        switch ($period_str) {
            case 'all_time':
            case 0:
                $start = Carbon::createFromFormat('Y', '1970')->startOfYear();
                $days = $start->diff($now, true)->days;
                break;
            case 'this_month':
                $start->startOfMonth();
                $days = $start->diff($now, true)->days;
                break;
            case 'this_year':
                $start->startOfYear();
                $days = $start->diff($now, true)->days;
                break;
            case 'last_year':
                $start->startOfYear()->subYear();
                $days = $start->diff($now, true)->days;
                $end = $start->copy();
                $end->endOfYear();
                break;

            case 'last_2_year':
                $start->startOfYear()->subYear();
                $days = $start->diff($now, true)->days;
                $end = $now->copy();
                break;
            case 'last_1_year':
                $start->startOfYear();
                $days = $start->diff($now, true)->days;
                $end = $now->copy();
                break;
            case 'last_6_month':
                $start->startOfMonth()->subMonth(6);
                $days = $start->diff($now, true)->days;
                $end = $now->copy();
                break;
            case 'last_3_month':
                $start->startOfMonth()->subMonth(3);
                $days = $start->diff($now, true)->days;
                $end = $now->copy();
                break;
            case 'last_1_month':
                $start->startOfMonth()->subMonth(1);
                $days = $start->diff($now, true)->days;
                $end = $now->copy();
                break;



            default:

                // Year value
                if (is_numeric($period_str) && intval($period_str) == $period_str && strlen($period_str) == 4) {
                    $start = Carbon::createFromFormat('Y', $period_str)->startOfYear();
                    $days = $start->diff($now, true)->days;
                    $end = $start->copy();
                    $end->endOfYear();
                }

                // Days value
                else if (is_numeric($period_str) && intval($period_str) == $period_str && $period_str < 1970) {
                    $start = $now->copy()->subDays($period_str);
                    $days = $start->diff($now, true)->days;
                    $end = $now->copy();
                }
                break;
        }

        return [
            'start' => $start,
            'end' => $end,
            'days' => $days,
        ];
    }

    // ----------------------------------------------------- Subscription page top chart data (Lifetime) -----------------------------------------------------
    public static function koolreport_lifetime_drilldown_chart_all_time(array $filter = [])
    {
        $folder_ids = [];
        $tag_ids = [];
        $payment_method_ids = [];
        $days = 0;

        lib()->cache->dashboard_kr_lifetime_summary_total_count = 0;
        lib()->cache->dashboard_kr_lifetime_summary_active_count = 0;


        if (isset($filter['period'])) {
            $period_str = $filter['period'];
        } else {
            $period_str = local('dashboard_lifetime_kr_period', 'all_time');
        }

        if (isset($filter['folder_ids'])) {
            $folder_ids = $filter['folder_ids'];
        }

        if (isset($filter['tag_ids'])) {
            $tag_ids = $filter['tag_ids'];
        }

        if (isset($filter['payment_method_ids'])) {
            $payment_method_ids = $filter['payment_method_ids'];
        }

        // Calculate period
        $period_arr = self::get_days_from_period($period_str);

        $days = $period_arr['days'];
        $start_date = $period_arr['start']->format('Y-m-d');
        $end_date = $period_arr['end']->format('Y-m-d');



        // Build query to get data
        $query = Subscription::where('subscriptions.user_id', Auth::id())

            // Disabled to get total count
            // ->where('subscriptions.status', 1)
            ->where('subscriptions.type', 3)
            ->whereBetween('subscriptions.payment_date', [$start_date, $end_date])
            ->leftJoin('subscriptions_tags', 'subscriptions_tags.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('folder', 'folder.id', '=', 'subscriptions.folder_id')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'subscriptions.category_id')
            ->select(
                'subscriptions.*',
                'folder.name as folder_name',
                'product_categories.name as product_category_name',
            )
            ->orderBy('subscriptions.payment_date', 'desc');

        if (count($folder_ids) > 0) {
            $query->whereIn('subscriptions.folder_id', $folder_ids);
        }

        if (count($tag_ids) > 0) {
            $query->whereIn('subscriptions_tags.tag_id', $tag_ids);
        }

        if (count($payment_method_ids) > 0) {
            $query->whereIn('subscriptions.payment_mode_id', $payment_method_ids);
        }

        // Store query to use later
        // $query_total = clone ($query);

        $query->where('subscriptions.status', 1);
        $query->groupBy('subscriptions.id');
        $output = $query->get();


        // Definition
        $chart_data_sorted = [];
        $all_years_value = [];
        $lifetime_summary_total_array = [];
        $lifetime_summary_active_array = [];
        $lifetime_this_year_count = 0;
        $lifetime_this_year_price = 0;
        $lifetime_total_price = 0;


        foreach ($output as $val) {
            $val->calc_payment_date_formatted = date('jS M Y', strtotime($val->payment_date));
            $val->year = date('Y', strtotime($val->payment_date));
            $val->month = date('F', strtotime($val->payment_date));

            $chart_data_sorted[] = $val->toArray();

            // Get all years value
            // $val->year = date('Y', strtotime($val->payment_date));
            if (isset($all_years_value[$val->year])) {
                $all_years_value[$val->year] += $val->price;
            } else {
                $all_years_value[$val->year] = $val->price;
            }


            // Count total this year price in $lifetime_data
            if (date('Y', strtotime($val->payment_date)) == date('Y')) {
                $lifetime_this_year_count++;
                $lifetime_this_year_price += $val->price;
            }
            $lifetime_total_price += $val->price;


            // Calculate lifetime count
            $lifetime_summary_total_array[$val->id] = true;

            // Count only active lifetime
            if ($val->status == 1) {
                $lifetime_summary_active_array[$val->id] = true;
            }
        }

        // Store count in cache
        lib()->cache->dashboard_kr_lifetime_summary_total_count = count($lifetime_summary_total_array);
        lib()->cache->dashboard_kr_lifetime_summary_active_count = count($lifetime_summary_active_array);
        lib()->cache->dashboard_kr_lifetime_summary_total_count += lib()->cache->dashboard_kr_lifetime_summary_draft_count;
        lib()->cache->dashboard_kr_lifetime_summary_total_count += lib()->cache->dashboard_kr_lifetime_summary_canceled_count;


        // Set output data for KoolReport
        lib()->cache->dashboard_kr_lifetime_average_currency_code = 'USD';
        lib()->cache->dashboard_kr_lifetime_average_currency_symbol = '$';


        lib()->cache->dashboard_kr_lifetime_this_year_count = $lifetime_this_year_count;
        lib()->cache->dashboard_kr_lifetime_this_year_price = number_format($lifetime_this_year_price, 2, '.', '');
        lib()->cache->dashboard_kr_lifetime_total_price = number_format($lifetime_total_price, 2, '.', '');



        // Chart object
        lib()->cache->dashboard_kr_lifetime_level = 'all';


        // 1 Month view
        if ($days <= 30) {
            lib()->cache->dashboard_kr_lifetime_level = 'month';
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_year = '';
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_month = '';

            $data_arr_1 = collect($chart_data_sorted)->groupBy('calc_payment_date_formatted')->toArray();
            $data_arr_2 = [];
            foreach ($data_arr_1 as $val) {
                $data_arr_2[] = $val[0];
            }

            lib()->cache->dashboard_kr_lifetime_drilldown_month_chart = $data_arr_2;
        }

        // Yearly view
        else if ($days > 30 && $days <= 365) {
            lib()->cache->dashboard_kr_lifetime_level = 'year';
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_year = '';

            $data_arr_1 = collect($chart_data_sorted)->groupBy('month')->toArray();
            $data_arr_2 = [];
            foreach ($data_arr_1 as $val) {
                $data_arr_2[] = $val[0];
            }

            lib()->cache->dashboard_kr_lifetime_drilldown_year_chart = $data_arr_2;
        }


        // lib()->cache->dashboard_kr_lifetime_drilldown_all_chart = collect($chart_data_sorted)->groupBy('year')->toArray();
        // $data_arr_1 = collect($chart_data_sorted)->groupBy('year')->toArray();
        // $data_arr_2 = [];
        // foreach ($data_arr_1 as $val) {
        //     $data_arr_2[] = $val[0];
        // }

        // lib()->cache->dashboard_kr_lifetime_drilldown_all_chart = $data_arr_2;

        $output_data = [];
        $new_sorted_data = self::group_data($chart_data_sorted, 'year');
        foreach ($new_sorted_data as $key => $value) {
            $output_data[] = $value;
        }

        lib()->cache->dashboard_kr_lifetime_drilldown_all_chart = $output_data;
    }
















    public static function koolreport_lifetime_drilldown_chart_inside(array $filter = [])
    {
        $folder_ids = [];
        $tag_ids = [];
        $payment_method_ids = [];
        $days = 0;
        $year = '';
        $month = '';
        $level = '';


        if (isset($filter['period'])) {
            $period_str = $filter['period'];
        } else {
            $period_str = local('dashboard_lifetime_kr_period', 'all_time');
        }

        if (isset($filter['folder_ids'])) {
            $folder_ids = $filter['folder_ids'];
        }

        if (isset($filter['tag_ids'])) {
            $tag_ids = $filter['tag_ids'];
        }

        if (isset($filter['payment_method_ids'])) {
            $payment_method_ids = $filter['payment_method_ids'];
        }

        if (isset($filter['level'])) {
            $level = $filter['level'];
        }


        if ($level == 'year') {
            lib()->cache->dashboard_kr_lifetime_level = 'year';
            $year = isset($filter['year']) ? $filter['year'] : date('Y');
        } elseif ($level == 'month') {
            lib()->cache->dashboard_kr_lifetime_level = 'month';
            $year = isset($filter['year']) ? $filter['year'] : date('Y');
            $month = isset($filter['month_name']) ? date('m', strtotime($filter['month_name'])) : date('m');
        }


        // Calculate period
        $period_arr = self::get_days_from_period($period_str);

        $days = $period_arr['days'];
        $start_date = $period_arr['start']->format('Y-m-d');
        $end_date = $period_arr['end']->format('Y-m-d');


        // Build query to get data
        $query = Subscription::where('subscriptions.user_id', Auth::id())
            ->where('subscriptions.status', 1)
            ->where('subscriptions.type', 3)
            ->whereBetween('subscriptions.payment_date', [$start_date, $end_date])
            ->leftJoin('subscriptions_tags', 'subscriptions_tags.subscription_id', '=', 'subscriptions.id')
            ->leftJoin('folder', 'folder.id', '=', 'subscriptions.folder_id')
            ->select(
                'subscriptions.*',
                'folder.name as folder_name',
            )
            ->orderBy('subscriptions.payment_date', 'desc');

        if (count($folder_ids) > 0) {
            $query->whereIn('subscriptions.folder_id', $folder_ids);
        }

        if (count($tag_ids) > 0) {
            $query->whereIn('subscriptions_tags.tag_id', $tag_ids);
        }

        if (count($payment_method_ids) > 0) {
            $query->whereIn('subscriptions.payment_mode_id', $payment_method_ids);
        }

        if (!empty($year)) {
            $query->whereYear('subscriptions.payment_date', $year);
        }

        if (!empty($month)) {
            $query->whereMonth('subscriptions.payment_date', $month);
        }

        $output = $query->get();

        // dd($filter, $level, lib()->cache->dashboard_kr_lifetime_level, $output);

        $area_chart_data_sorted = [];
        foreach ($output as $val) {
            $val->calc_payment_date_formatted = date('jS M Y', strtotime($val->payment_date));
            $val->year = date('Y', strtotime($val->payment_date));
            // month name
            $val->month = date('F', strtotime($val->payment_date));
            // $val->month = date('m', strtotime($val->payment_date));

            $area_chart_data_sorted[] = $val->toArray();
        }


        if (lib()->cache->dashboard_kr_lifetime_level == 'year') {
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_year = $year;
            // lib()->cache->dashboard_kr_lifetime_drilldown_year_chart = $area_chart_data_sorted;
            // lib()->cache->dashboard_kr_lifetime_drilldown_year_chart = collect($area_chart_data_sorted)->groupBy('month')->toArray();

            // $data_arr_1 = collect($area_chart_data_sorted)->groupBy('month')->toArray();
            // $data_arr_2 = [];
            // foreach ($data_arr_1 as $val) {
            //     $data_arr_2[] = $val[0];
            // }

            // lib()->cache->dashboard_kr_lifetime_drilldown_year_chart = $data_arr_2;

            $output_data = [];
            $new_sorted_data = self::group_data($area_chart_data_sorted, 'month');
            foreach ($new_sorted_data as $key => $value) {
                $output_data[] = $value;
            }

            lib()->cache->dashboard_kr_lifetime_drilldown_year_chart = $output_data;
        }

        // For months
        elseif (lib()->cache->dashboard_kr_lifetime_level == 'month') {
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_year = $year;
            lib()->cache->dashboard_kr_lifetime_drilldown_selected_month = $month;
            // lib()->cache->dashboard_kr_lifetime_drilldown_month_chart = $area_chart_data_sorted;
            // lib()->cache->dashboard_kr_lifetime_drilldown_month_chart = collect($area_chart_data_sorted)->groupBy('calc_payment_date_formatted')->toArray();


            // $data_arr_1 = collect($area_chart_data_sorted)->groupBy('calc_payment_date_formatted')->toArray();
            $data_arr_1 = collect($area_chart_data_sorted)->toArray();
            $data_arr_2 = [];
            foreach ($data_arr_1 as $val) {
                $data_arr_2[] = $val;
            }

            lib()->cache->dashboard_kr_lifetime_drilldown_month_chart = $data_arr_2;
        }
    }

    private static function group_data($data, $group_key)
    {
        $output = [];
        $data = collect($data)->toArray();

        foreach ($data as $val) {
            if (isset($val[$group_key])) {
                $group_key_val = $val[$group_key];
                if (isset($output[$group_key_val])) {
                    $output[$group_key_val]['price'] += $val['price'];
                } else {
                    $output[$group_key_val] = $val;
                }
            }
        }

        return $output;
    }

    public static function get_status($subscription)
    {
        switch ($subscription->status) {
            case 0:
                return 'Draft';
                break;
            case 1:
                if ($subscription->sub_addon) {
                    $status = 'Addon';
                } elseif ($subscription->recurring) {
                    $status = 'Recur';
                } else {
                    $status = 'Once';
                }
                break;
            case 2:
                $status = 'Canceled';
                break;
            case 3:
                $status = 'Refund';
                break;
            case 4:
                $status = 'Expired';
                break;
            default:
                $status = 'Draft';
        }
        return $status;
    }
}
