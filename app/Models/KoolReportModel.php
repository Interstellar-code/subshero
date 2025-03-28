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


class KoolReportModel extends BaseModel
{
    private const TABLE = 'subscriptions';

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_by_product_id($product_id)
    {
        return DB::table(self::TABLE)
            ->where('product_id', $product_id)
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
