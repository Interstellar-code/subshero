<?php

namespace App\Models;

use App\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// DrillDown
use koolreport\drilldown\DrillDown;
use koolreport\processes\CopyColumn;
use koolreport\processes\DateTimeFormat;
use koolreport\widgets\google\ColumnChart;


class ReportModel extends BaseModel
{
    private const TABLE = 'subscriptions';

    public static function get_days_from_period($period_str)
    {
        $days = 0;
        $now = Carbon::now();
        $start = Carbon::now();
        $end = Carbon::now();

        switch ($period_str) {
            case 'all_time':
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

            default:
                if (is_numeric($period_str) && intval($period_str) == $period_str && strlen($period_str) == 4) {
                    $start = Carbon::createFromFormat('Y', $period_str)->startOfYear();
                    $days = $start->diff($now, true)->days;
                    $end = $start->copy();
                    $end->endOfYear();
                }
                break;
        }

        return [
            'start' => $start,
            'end' => $end,
            'days' => $days,
        ];
    }






    public static function load_subscription_chart_data(array $filter = [])
    {
        // $filter = [
        //     'period',
        //     'folders',
        //     'tags',
        //     'payment_methods',
        // ];

        $type = 1;
        $folder_ids = [];
        $tag_ids = [];
        $payment_method_ids = [];
        $days = 0;


        if (isset($filter['period'])) {
            $period_str = $filter['period'];
        } else {
            $period_str = local('report_period', 'this_month');
        }

        // if (isset($filter['type'])) {
        //     $type = $filter['type'];
        // }

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
            // ->where('subscriptions.status', 1)
            ->where('subscriptions.type', $type)
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

        // ->where('folder_id', local('subscription_folder_id'))
        // ->select(DB::raw(implode(', ', [
        //     'YEAR(payment_date) as year',
        //     'SUM(price) as price',
        // ])))
        // ->groupBy('year')
        // ->orderBy('subscriptions.year', 'desc');

        // dd($query->toSql());



        // Store query to use later
        // $query_total = clone ($query);
        // $query_active = clone ($query);
        $query_draft = clone ($query);
        $query_canceled = clone ($query);

        $query->where('subscriptions.status', 1);

        $data = [];
        $data['days'] = $days;
        $all_schedules = self::get_subscription_data_by_query($query);
        $today = date('Y-m-d');






        // Calculate total count
        // $query_total->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        // lib()->cache->report_subscription_summary_total_count = $query_total->count();

        // $query_active->where('subscriptions.status', 1);
        // $query_active->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        // lib()->cache->report_subscription_summary_active_count = $query_active->count();

        $query_draft->where('subscriptions.status', 0);
        $query_draft->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        lib()->cache->report_subscription_summary_draft_count = $query_draft->count();

        $query_canceled->where('subscriptions.status', 2);
        $query_canceled->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        lib()->cache->report_subscription_summary_canceled_count = $query_canceled->count();


        lib()->cache->report_subscription_summary_total_count = 0;
        lib()->cache->report_subscription_summary_active_count = 0;




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

            // $start_date = date('Y-m-d', strtotime("-$days days"));
            // $end_date = date('Y-m-d');

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
        $subscription_summary_total_array = [];
        $subscription_summary_active_array = [];


        $all_years_value = [];
        $all_months_value = [];
        $all_weeks_value = [];
        $yearly_array_cost = [];
        $monthly_array_cost = [];
        $weekly_array_cost = [];

        foreach ($output as &$schedule) {
            $schedule->calc_next_payment_date_formatted = date('d M', strtotime($schedule->calc_next_payment_date));
            $subscription_total_value += $schedule->price;

            if (!in_array($schedule->id, $subscription_id_all)) {
                $subscription_id_all[] = $schedule->id;
                $subscription_total_count++;
            }


            // Get all years value
            $schedule->year = date('Y', strtotime($schedule->calc_next_payment_date));
            if (isset($all_years_value[$schedule->year])) {
                $all_years_value[$schedule->year] += $schedule->price;
            } else {
                $all_years_value[$schedule->year] = $schedule->price;
            }


            // Calculate weekly, monthly and yearly value
            // Daily billing cycle
            if ($schedule->billing_cycle == 1) {

                // Daily -> Weekly cost
                if (!isset($weekly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $weekly_price = ($price / $frequency) * 7;
                    $weekly_array_cost[$schedule->id] = $weekly_price;
                }

                // Daily -> Monthly cost
                if (!isset($monthly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $monthly_price = ($price / $frequency) * 30;
                    $monthly_array_cost[$schedule->id] = $monthly_price;
                }

                // Daily -> Yearly cost
                if (!isset($yearly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $yearly_price = ($price / $frequency) * 365;
                    $yearly_array_cost[$schedule->id] = $yearly_price;
                }
            }

            // Weekly billing cycle
            else if ($schedule->billing_cycle == 2) {

                // Weekly -> Weekly cost
                if (!isset($weekly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $weekly_price = $price / $frequency;
                    $weekly_array_cost[$schedule->id] = $weekly_price;
                }

                // Weekly -> Monthly cost
                if (!isset($monthly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $monthly_price = ($price / ($frequency * 7)) * 30;
                    $monthly_array_cost[$schedule->id] = $monthly_price;
                }

                // Weekly -> Yearly cost
                if (!isset($yearly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $yearly_price = ($price / ($frequency * 7)) * 365;
                    $yearly_array_cost[$schedule->id] = $yearly_price;
                }
            }

            // Monthly billing cycle
            else if ($schedule->billing_cycle == 3) {

                // Monthly -> Weekly cost
                if (!isset($weekly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $weekly_price = $price / (($frequency * 30) / 7);
                    $weekly_array_cost[$schedule->id] = $weekly_price;
                }

                // Monthly -> Monthly cost
                if (!isset($monthly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $monthly_price = $price / $frequency;
                    $monthly_array_cost[$schedule->id] = $monthly_price;
                }

                // Monthly -> Yearly cost
                if (!isset($yearly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $yearly_price = ($price / $frequency) * 12;
                    $yearly_array_cost[$schedule->id] = $yearly_price;
                }
            }

            // Yearly billing cycle
            else if ($schedule->billing_cycle == 4) {

                // Yearly -> Weekly cost
                if (!isset($weekly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $weekly_price = $price / (($frequency * 365) / 7);
                    $weekly_array_cost[$schedule->id] = $weekly_price;
                }

                // Yearly -> Monthly cost
                if (!isset($monthly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $monthly_price = $price / ($frequency * 12);
                    $monthly_array_cost[$schedule->id] = $monthly_price;
                }

                // Yearly -> Yearly cost
                if (!isset($yearly_array_cost[$schedule->id])) {
                    $price = $schedule->price;
                    $frequency = $schedule->billing_frequency;
                    $yearly_price = $price / $frequency;
                    $yearly_array_cost[$schedule->id] = $yearly_price;
                }
            }






            // Get all months value
            $schedule->month = date('m', strtotime($schedule->calc_next_payment_date));
            if (isset($all_months_value[$schedule->month])) {
                $all_months_value[$schedule->month] += $schedule->price;
            } else {
                $all_months_value[$schedule->month] = $schedule->price;
            }

            // Get all weeks value
            $schedule->week = date('W', strtotime($schedule->calc_next_payment_date));
            if (isset($all_weeks_value[$schedule->week])) {
                $all_weeks_value[$schedule->week] += $schedule->price;
            } else {
                $all_weeks_value[$schedule->week] = $schedule->price;
            }


            // Calculate subscription count
            $subscription_summary_total_array[$schedule->id] = true;

            // Count only active subscriptions
            if ($schedule->status == 1) {
                $subscription_summary_active_array[$schedule->id] = true;
            }
        }

        // Store count in cache
        lib()->cache->report_subscription_summary_total_count = count($subscription_summary_total_array);
        lib()->cache->report_subscription_summary_active_count = count($subscription_summary_active_array);
        lib()->cache->report_subscription_summary_total_count += lib()->cache->report_subscription_summary_draft_count;
        lib()->cache->report_subscription_summary_total_count += lib()->cache->report_subscription_summary_canceled_count;


        // Sorting for KoolReport
        $area_chart_data_sorted = [];
        $category_pie_chart_data = [];

        foreach ($output as $val) {
            $area_chart_data_sorted[] = $val->toArray();
        }

        // $output = $area_chart_data_sorted;

        $output = collect($output);
        // dd($output->sum('folder_name'));
        // dd($output->unique('calc_next_payment_date_formatted'));
        // dd($output->unique('calc_next_payment_date_formatted')->sum('price'));

        // $category_pie_chart_data = $output->unique('calc_next_payment_date_formatted')->sum('price')->toArray();

        // get total price of unique folders
        $category_pie_chart_data_grouped = $output->groupBy('product_category_name')->map(function ($item, $key) {
            return $item->sum('price');
        })->toArray();

        // dd($category_pie_chart_data);

        foreach ($category_pie_chart_data_grouped as $key => $val) {
            $category_pie_chart_data[] = [
                'product_category_name' => $key,
                'price' => $val,
            ];
        }



        // Subscription top section calculation

        $subscription_output = clone ($all_schedules);

        $subscription_total_count = 0;
        $subscription_total_value = 0;
        $subscription_monthly_value = 0;
        $subscription_id_all = [];

        foreach ($subscription_output as &$schedule) {
            $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
            $subscription_total_value += $schedule->price;

            if (!in_array($schedule->id, $subscription_id_all)) {
                $subscription_id_all[] = $schedule->id;
                $subscription_total_count++;
            }
        }





        // Top section calculation

        // count total months in $subscription_output
        if (count($subscription_output) > 0) {
            // $subscription_monthly_value = $subscription_total_value / count($subscription_output);

            $to = Carbon::createFromFormat('Y-m-d', $subscription_output[0]->calc_next_payment_date);
            $from = Carbon::createFromFormat('Y-m-d', $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
            $diff_in_days = $to->diffInDays($from);
            // $diff_in_months = $to->diffInMonths($from);
            $diff_in_months = $diff_in_days / 30;
            // print_r($diff_in_months); // Output: 1
            // dd($diff_in_months);

            // dd($subscription_output[0]->calc_next_payment_date, $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
            // dd($diff_in_days, $diff_in_months, $subscription_total_value, number_format((float)($subscription_total_value / $diff_in_months), 2, '.', ''));

            if ($diff_in_months < 1) {
                $diff_in_months = 1;
            }

            // $diff_in_months = round($diff_in_months);

            if ($subscription_total_value > 0 && $diff_in_months > 0) {
                $subscription_monthly_value = number_format((float)($subscription_total_value / $diff_in_months), 2, '.', '');
            } else if ($diff_in_months == 0) {
                $subscription_monthly_value = $subscription_total_value;
            }

            if ($subscription_monthly_value < 0) {
                $subscription_monthly_value = 0;
            }
        }

        // $_SESSION['subscription_monthly_price'] = $subscription_monthly_value;
        // $_SESSION['subscription_total_price'] = $subscription_total_value;
        // $_SESSION['subscription_total_count'] = $subscription_total_count;


        // Set output data for KoolReport
        lib()->cache->report_subscription_average_currency_code = 'USD';
        lib()->cache->report_subscription_average_currency_symbol = '$';



        // Set output data for KoolReport
        lib()->cache->report_subscription_average_currency_code = 'USD';
        lib()->cache->report_subscription_average_currency_symbol = '$';

        // // Calculate average yearly cost
        // if (count(($all_years_value)) > 0) {
        //     lib()->cache->report_subscription_average_yearly_cost = number_format((float)(array_sum($all_years_value) / count($all_years_value)), 2, '.', '');
        // }

        // if (count(($all_months_value)) > 0) {
        //     lib()->cache->report_subscription_average_monthly_cost = number_format((float)(array_sum($all_months_value) / count($all_months_value)), 2, '.', '');
        // }

        // if (count(($all_weeks_value)) > 0) {
        //     lib()->cache->report_subscription_average_weekly_cost = number_format((float)(array_sum($all_weeks_value) / count($all_weeks_value)), 2, '.', '');
        // }




        // Calculate average cost for weekly, monthly, yearly
        if (count(($yearly_array_cost)) > 0) {
            lib()->cache->report_subscription_average_yearly_cost = number_format((float)(array_sum($yearly_array_cost)), 2, '.', '');
        }

        if (count(($monthly_array_cost)) > 0) {
            lib()->cache->report_subscription_average_monthly_cost = number_format((float)(array_sum($monthly_array_cost)), 2, '.', '');
        }

        if (count(($weekly_array_cost)) > 0) {
            lib()->cache->report_subscription_average_weekly_cost = number_format((float)(array_sum($weekly_array_cost)), 2, '.', '');
        }







        // lib()->cache->report_subscription_summary_total_count = $subscription_total_count;
        // lib()->cache->report_subscription_summary_active_count = $subscription_total_count;
        // lib()->cache->report_subscription_summary_trial_count = 0;

        // Chart object
        lib()->cache->report_subscription_mrp_area_chart = $area_chart_data_sorted;
        lib()->cache->report_subscription_google_area_chart = $area_chart_data_sorted;
        lib()->cache->report_subscription_category_pie_chart = $category_pie_chart_data;
    }

    private static function get_subscription_data_by_query($query)
    {
        // DB::enableQueryLog();
        $data = $query->get();
        // dd(DB::getQueryLog());
        // dd($data);

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
                $new_event_id,
                $subscription_event,
                $new_subscription_history,
                $user_alert_found,
                $subscription_user_alert,
                $old_active_event,
            );
        }

        return $result;
    }





    public static function load_lifetime_chart_data(array $filter = [])
    {
        $folder_ids = [];
        $tag_ids = [];
        $payment_method_ids = [];
        $days = 0;

        lib()->cache->report_lifetime_summary_total_count = 0;
        lib()->cache->report_lifetime_summary_active_count = 0;
        lib()->cache->report_lifetime_summary_trial_count = 0;



        if (isset($filter['period'])) {
            $period_str = $filter['period'];
        } else {
            $period_str = local('report_period', 'this_month');
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
            ->orderBy('subscriptions.payment_date', 'asc');

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
        // $query_active = clone ($query);
        $query_draft = clone ($query);
        $query_canceled = clone ($query);

        $query->where('subscriptions.status', 1);
        $query->groupBy('subscriptions.id');
        $output = $query->get();



        // Calculate total count
        // lib()->cache->report_lifetime_summary_total_count = $query_total->count();

        // $query_active->where('subscriptions.status', 1);
        // lib()->cache->report_lifetime_summary_active_count = $query_active->count();

        $query_draft->where('subscriptions.status', 0);
        $query_draft->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        lib()->cache->report_lifetime_summary_draft_count = $query_draft->count();

        $query_canceled->where('subscriptions.status', 2);
        $query_canceled->whereBetween('subscriptions.payment_date', [$start_date, $end_date]);
        lib()->cache->report_lifetime_summary_canceled_count = $query_canceled->count();








        $area_chart_data_sorted = [];
        $all_years_value = [];
        $all_months_value = [];
        $all_weeks_value = [];
        $lifetime_summary_total_array = [];
        $lifetime_summary_active_array = [];


        foreach ($output as $val) {
            $val->calc_next_payment_date = $val->payment_date;
            $val->calc_next_payment_date_formatted = date('jS M Y', strtotime($val->payment_date));
            $val->year = date('Y', strtotime($val->payment_date));

            $area_chart_data_sorted[] = $val->toArray();

            // Disabled to take all data from database
            // lib()->cache->report_lifetime_summary_total_count++;
            // lib()->cache->report_lifetime_summary_active_count++;


            // Get all years value
            // $val->year = date('Y', strtotime($val->payment_date));
            if (isset($all_years_value[$val->year])) {
                $all_years_value[$val->year] += $val->price;
            } else {
                $all_years_value[$val->year] = $val->price;
            }

            // Get all months value
            $val->month = date('m', strtotime($val->payment_date));
            if (isset($all_months_value[$val->month])) {
                $all_months_value[$val->month] += $val->price;
            } else {
                $all_months_value[$val->month] = $val->price;
            }

            // Get all weeks value
            $val->week = date('W', strtotime($val->payment_date));
            if (isset($all_weeks_value[$val->week])) {
                $all_weeks_value[$val->week] += $val->price;
            } else {
                $all_weeks_value[$val->week] = $val->price;
            }


            // Calculate lifetime count
            $lifetime_summary_total_array[$val->id] = true;

            // Count only active lifetime
            if ($val->status == 1) {
                $lifetime_summary_active_array[$val->id] = true;
            }
        }

        // Store count in cache
        lib()->cache->report_lifetime_summary_total_count = count($lifetime_summary_total_array);
        lib()->cache->report_lifetime_summary_active_count = count($lifetime_summary_active_array);
        lib()->cache->report_lifetime_summary_total_count += lib()->cache->report_lifetime_summary_draft_count;
        lib()->cache->report_lifetime_summary_total_count += lib()->cache->report_lifetime_summary_canceled_count;




        // get total price of unique folders
        $category_pie_chart_data_grouped = $output->groupBy('product_category_name')->map(function ($item, $key) {
            return $item->sum('price');
        })->toArray();

        // dd($category_pie_chart_data);

        $category_pie_chart_data = [];
        foreach ($category_pie_chart_data_grouped as $key => $val) {
            $category_pie_chart_data[] = [
                'product_category_name' => $key,
                'price' => $val,
            ];
        }



        // Set output data for KoolReport
        lib()->cache->report_lifetime_average_currency_code = 'USD';
        lib()->cache->report_lifetime_average_currency_symbol = '$';

        // Calculate average yearly cost
        if (count(($all_years_value)) > 0) {
            lib()->cache->report_lifetime_average_yearly_cost = number_format((float)(array_sum($all_years_value) / count($all_years_value)), 2, '.', '');
        }

        if (count(($all_months_value)) > 0) {
            lib()->cache->report_lifetime_average_monthly_cost = number_format((float)(array_sum($all_months_value) / count($all_months_value)), 2, '.', '');
        }

        if (count(($all_weeks_value)) > 0) {
            lib()->cache->report_lifetime_average_weekly_cost = number_format((float)(array_sum($all_weeks_value) / count($all_weeks_value)), 2, '.', '');
        }



        // Chart object
        lib()->cache->report_lifetime_level = 'all';
        lib()->cache->report_lifetime_mrp_area_chart = $area_chart_data_sorted;
        lib()->cache->report_lifetime_google_area_chart = $area_chart_data_sorted;
        lib()->cache->report_lifetime_category_pie_chart = $category_pie_chart_data;
        // lib()->cache->report_lifetime_drilldown_all_chart = $area_chart_data_sorted;


        // lib()->cache->report_lifetime_drilldown_all_chart = collect($area_chart_data_sorted)->groupBy('year')->toArray();
        // $data_arr_1 = collect($area_chart_data_sorted)->groupBy('year')->toArray();
        // $data_arr_2 = [];
        // foreach ($data_arr_1 as $val) {
        //     $data_arr_2[] = $val[0];
        // }

        // lib()->cache->report_lifetime_drilldown_all_chart = $data_arr_2;

        $output_data = [];
        $new_sorted_data = self::group_data($area_chart_data_sorted, 'year');
        foreach ($new_sorted_data as $key => $value) {
            $output_data[] = $value;
        }

        lib()->cache->report_lifetime_drilldown_all_chart = $output_data;
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



    // copied function
    public static function get_lifetime_drilldown_chart(array $filter = [])
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
            $period_str = local('report_period', 'this_month');
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
            lib()->cache->report_lifetime_level = 'year';
            $year = isset($filter['year']) ? $filter['year'] : date('Y');
        } elseif ($level == 'month') {
            lib()->cache->report_lifetime_level = 'month';
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

        // dd($filter, $level, lib()->cache->report_lifetime_level, $output);

        $area_chart_data_sorted = [];
        foreach ($output as $val) {
            $val->calc_payment_date_formatted = date('jS M Y', strtotime($val->payment_date));
            $val->year = date('Y', strtotime($val->payment_date));
            // month name
            $val->month = date('F', strtotime($val->payment_date));
            // $val->month = date('m', strtotime($val->payment_date));

            $area_chart_data_sorted[] = $val->toArray();
        }


        if (lib()->cache->report_lifetime_level == 'year') {
            lib()->cache->report_lifetime_drilldown_selected_year = $year;
            // lib()->cache->report_lifetime_drilldown_year_chart = $area_chart_data_sorted;
            // lib()->cache->report_lifetime_drilldown_year_chart = collect($area_chart_data_sorted)->groupBy('month')->toArray();

            // $data_arr_1 = collect($area_chart_data_sorted)->groupBy('month')->toArray();
            // $data_arr_2 = [];
            // foreach ($data_arr_1 as $val) {
            //     $data_arr_2[] = $val[0];
            // }

            // lib()->cache->report_lifetime_drilldown_year_chart = $data_arr_2;

            $output_data = [];
            $new_sorted_data = self::group_data($area_chart_data_sorted, 'month');
            foreach ($new_sorted_data as $key => $value) {
                $output_data[] = $value;
            }

            lib()->cache->report_lifetime_drilldown_year_chart = $output_data;
        }
        
        // For months
        elseif (lib()->cache->report_lifetime_level == 'month') {
            lib()->cache->report_lifetime_drilldown_selected_year = $year;
            lib()->cache->report_lifetime_drilldown_selected_month = $month;
            // lib()->cache->report_lifetime_drilldown_month_chart = $area_chart_data_sorted;
            // lib()->cache->report_lifetime_drilldown_month_chart = collect($area_chart_data_sorted)->groupBy('calc_payment_date_formatted')->toArray();


            // $data_arr_1 = collect($area_chart_data_sorted)->groupBy('calc_payment_date_formatted')->toArray();
            $data_arr_1 = collect($area_chart_data_sorted)->toArray();
            $data_arr_2 = [];
            foreach ($data_arr_1 as $val) {
                $data_arr_2[] = $val;
            }

            lib()->cache->report_lifetime_drilldown_month_chart = $data_arr_2;
        }
    }












































    public static function get_subscription_mrp_area_chart()
    {
        $days = 0;
        $period_str = local('report_period', 'this_month');
        // $days = self::get_days_from_period($period_str);

        $period_arr = self::get_days_from_period($period_str);

        // dd($period_arr);

        // Add today's date to the end of the period
        // $days++;


        // $start_date = date('Y-m-d', strtotime("-$days days"));
        // $end_date = date('Y-m-d');

        $days = $period_arr['days'];
        $start_date = $period_arr['start']->format('Y-m-d');
        $end_date = $period_arr['end']->format('Y-m-d');

















        $folder_id = null;
        $data = [];
        $data['days'] = $days;
        $data['folder_id'] = $folder_id;
        $all_schedules = self::_get_subscription_mrp_area_chart_data($folder_id);
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

            // $start_date = date('Y-m-d', strtotime("-$days days"));
            // $end_date = date('Y-m-d');

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
        $subscription_id_all = [];

        foreach ($subscription_output as &$schedule) {
            $schedule->calc_next_payment_date_formatted = date('jS M Y', strtotime($schedule->calc_next_payment_date));
            $subscription_total_value += $schedule->price;

            if (!in_array($schedule->id, $subscription_id_all)) {
                $subscription_id_all[] = $schedule->id;
                $subscription_total_count++;
            }

            // Calculate monthly value
            $schedule->monthly_value = SubscriptionModel::get_monthly_value($schedule);
            $subscription_monthly_value += $schedule->monthly_value;
        }





        // Top section calculation

        // // count total months in $subscription_output
        // if (count($subscription_output) > 0) {
        //     // $subscription_monthly_value = $subscription_total_value / count($subscription_output);

        //     $to = Carbon::createFromFormat('Y-m-d', $subscription_output[0]->calc_next_payment_date);
        //     $from = Carbon::createFromFormat('Y-m-d', $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
        //     $diff_in_days = $to->diffInDays($from);
        //     // $diff_in_months = $to->diffInMonths($from);
        //     $diff_in_months = $diff_in_days / 30;
        //     // print_r($diff_in_months); // Output: 1
        //     // dd($diff_in_months);

        //     // dd($subscription_output[0]->calc_next_payment_date, $subscription_output[count($subscription_output) - 1]->calc_next_payment_date);
        //     // dd($diff_in_days, $diff_in_months, $subscription_total_value, number_format((float)($subscription_total_value / $diff_in_months), 2, '.', ''));

        //     if ($diff_in_months < 1) {
        //         $diff_in_months = 1;
        //     }

        //     // $diff_in_months = round($diff_in_months);

        //     if ($subscription_total_value > 0 && $diff_in_months > 0) {
        //         $subscription_monthly_value = number_format((float)($subscription_total_value / $diff_in_months), 2, '.', '');
        //     } else if ($diff_in_months == 0) {
        //         $subscription_monthly_value = $subscription_total_value;
        //     }

        //     if ($subscription_monthly_value < 0) {
        //         $subscription_monthly_value = 0;
        //     }
        // }

        $_SESSION['subscription_monthly_price'] = $subscription_monthly_value;
        $_SESSION['subscription_total_price'] = $subscription_total_value;
        $_SESSION['subscription_total_count'] = $subscription_total_count;

        // return $area_chart_data_sorted;

        // dd($output);
        return $output;
    }

    private static function _get_subscription_mrp_area_chart_data($folder_id = null)
    {
        // if (empty(local('subscription_folder_id'))) {
        $data = DB::table('subscriptions')
            ->where('user_id', Auth::user()->id)
            ->where('type', 1)
            ->where('status', 1)
            ->orderBy('payment_date', 'desc')
            ->get();
        // } else {
        //     $data = DB::table('subscriptions')
        //         ->where('user_id', Auth::user()->id)
        //         ->where('folder_id', local('subscription_folder_id'))
        //         ->where('type', 1)
        //         ->where('status', 1)
        //         ->orderBy('payment_date', 'desc')
        //         ->get();
        // }


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
                            $result[] = clone ($subscription);
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
                $new_event_id,
                $subscription_event,
                $new_subscription_history,
                $user_alert_found,
                $subscription_user_alert,
                $old_active_event,
            );
        }

        return $result;
    }

    public static function get_subscription_drilldown($type = 1, $params = [])
    {
        // All Years = 0 -> Level 1
        if ($type == 1) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                // ->select(DB::raw('YEAR(payment_date) as year, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'YEAR(payment_date) as year',
                    'SUM(price) as price',
                ])))
                ->groupBy('year')
                ->orderBy('year', 'desc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }

        // All Years = 0 -> Level 2
        else if ($type == 2) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', $params['year'])
                // ->select(DB::raw('MONTHNAME(payment_date) as month, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'MONTHNAME(payment_date) as month',
                    'SUM(price) as price',
                ])))
                ->groupBy('month');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }

        // All Years = 0 -> Level 3
        else if ($type == 3) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', $params['year'])
                ->whereMonth('payment_date', $params['month'])
                // ->select(DB::raw('payment_date, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'DATE_FORMAT(payment_date, "%D %b %Y") as payment_date',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('payment_date')
                ->orderBy('payment_date', 'asc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }


        // 1 month -> Level 1
        else if ($type == 10) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                // ->whereYear('payment_date', $params['year'])
                // ->whereMonth('payment_date', $params['month'])
                ->whereBetween('payment_date', [date('Y-m-d', strtotime('-' . local('lifetime_days', 30) . ' days')), date('Y-m-d', strtotime('+1 days'))])
                ->select(DB::raw(implode(', ', [
                    'DATE_FORMAT(payment_date, "%D %b %Y") as payment_date',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('payment_date')
                ->orderBy('payment_date', 'asc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }


        // 1 year -> Level 1
        else if ($type == 20) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', date('Y'))
                // ->select(DB::raw('MONTHNAME(payment_date) as month, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'MONTHNAME(payment_date) as month',
                    'SUM(price) as price',
                ])))
                ->groupBy('month');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }

        // 1 year -> Level 2
        else if ($type == 21) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', date('Y'))
                // ->whereYear('payment_date', $params['year'])
                ->whereMonth('payment_date', $params['month'])
                // ->select(DB::raw('payment_date, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'DATE_FORMAT(payment_date, "%D %b %Y") as payment_date',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('payment_date')
                ->orderBy('payment_date', 'asc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }


        // More than 1 year -> Level 1
        else if ($type == 30) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereBetween('payment_date', [date('Y-m-d', strtotime('-' . local('lifetime_days', 365) . ' days')), date('Y-m-d', strtotime('+1 days'))])
                // ->select(DB::raw('YEAR(payment_date) as year, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'YEAR(payment_date) as year',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('year')
                ->orderBy('year', 'desc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }

        // More than 1 year -> Level 2
        else if ($type == 31) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', $params['year'])
                // ->select(DB::raw('MONTHNAME(payment_date) as month, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'MONTHNAME(payment_date) as month',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('month');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        }

        // More than 1 year -> Level 3
        else if ($type == 32) {
            $data = DB::table('subscriptions')
                ->where('user_id', Auth::user()->id)
                ->where('status', 1)
                ->where('type', 3)
                ->whereYear('payment_date', $params['year'])
                ->whereMonth('payment_date', $params['month'])
                // ->select(DB::raw('payment_date, SUM(price) as price'))
                ->select(DB::raw(implode(', ', [
                    'DATE_FORMAT(payment_date, "%D %b %Y") as payment_date',
                    'SUM(price) as price',
                    'group_concat(product_name) as product_name',
                ])))
                ->groupBy('payment_date')
                ->orderBy('payment_date', 'asc');

            if (empty(local('subscription_folder_id'))) {
                $data = $data->get();
            } else {
                $data = $data->where('folder_id', local('subscription_folder_id'));
                $data = $data->get();
            }
        } else {
            return [];
        }

        lib()->cache->subscription_drilldown = $data;
        $_SESSION['subscription_drilldown'] = $data;

        return $data;
    }
}
