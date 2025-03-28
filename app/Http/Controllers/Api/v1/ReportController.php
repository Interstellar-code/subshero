<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\ReportModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return response()->json([
            'msg' => 'ON',
            'data' => [
                'protocol' => $request->segment(1),
                'lang' => $request->segment(2),
                'version' => $request->segment(3),
                'type' => $request->segment(4),
                'mode' => $request->segment(5),
                'user' => ($request->user()) ? $request->user()->id : 'NO-USER',
            ],
        ], 200);
    }

    public function activeSubsLtd(Request $request)
    {
        $output = [
            'subscription' => [
                'total_count' => 0,
                'active_count' => 0,
                'monthly_price' => 0,
                'total_price' => 0,
            ],
            'lifetime' => [
                'total_count' => 0,
                'active_count' => 0,
                'this_year_price' => 0,
                'total_price' => 0,
            ],
        ];

        // AreaChart
        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart(local('subscription_days', 0));

        $output['subscription']['total_count'] = local('subscription_total_count', 0);
        $output['subscription']['active_count'] = local('subscription_total_count', 0);
        $output['subscription']['monthly_price'] = local('subscription_monthly_price', 0);
        $output['subscription']['total_price'] = local('subscription_total_price', 0);

        // Drilldown chart
        SubscriptionModel::koolreport_lifetime_drilldown_chart_all_time();

        $output['lifetime']['total_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['active_count'] = lib()->cache->dashboard_kr_lifetime_summary_total_count;
        $output['lifetime']['this_year_price'] = lib()->cache->dashboard_kr_lifetime_this_year_price;
        $output['lifetime']['total_price'] = lib()->cache->dashboard_kr_lifetime_total_price;

        return $output;

        // Return success response
        return response()->json($output, 200);
    }

    public function activeSubscriptionTotal(Request $request)
    {
        $output = [];

        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart();
        ReportModel::load_subscription_chart_data();
        $output = lib()->cache->report_subscription_mrp_area_chart;

        // Return success response
        return response()->json($output, 200);
    }

    public function activeSubscriptionMRR(Request $request)
    {
        // Validate the request
        $fields = $request->validate([
            'period' => 'nullable|string|in:all_time,this_month,this_year,last_year',
        ]);

        $output = [];
        $filter = [
            'period' => $fields['period'] ?? 'all_time',
        ];

        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart();
        ReportModel::load_subscription_chart_data($filter);
        $output = lib()->cache->report_subscription_mrp_area_chart;

        // Return success response
        return response()->json($output, 200);
    }

    public function activeSubscriptionPie(Request $request)
    {
        $output = [];
        ReportModel::load_subscription_chart_data();
        $output = lib()->cache->report_subscription_category_pie_chart;

        if (!empty($output)) {
            $collection = collect($output);
            $max_price = (float)$collection->max('price');
            $sorted = $collection->sortBy('price');
            $sorted_array = $sorted->values()->all();

            foreach ($sorted_array as $key => $value) {
                // Calculate percent by total number of items
                $sorted_array[$key]['percent'] = round(($value['price'] / $collection->sum('price')) * 100, 2);
            }

            $output = $sorted_array;
        }

        // Return success response
        return response()->json($output, 200);
    }

    public function activeLifetimeMRR(Request $request)
    {
        // Validate the request
        $fields = $request->validate([
            'period' => 'nullable|string|in:all_time,this_month,this_year,last_year',
        ]);

        $output = [];
        $filter = [
            'type' => 3,
            'folder_ids' => [],
            'tag_ids' => null,
            'payment_method_ids' => null,
            'period' => $fields['period'] ?? 'all_time',
        ];

        ReportModel::load_lifetime_chart_data($filter);
        $output = lib()->cache->report_lifetime_mrp_area_chart;

        // Return success response
        return response()->json($output, 200);
    }
}
