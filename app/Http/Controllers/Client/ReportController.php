<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Library\Cron;
use App\Models\BrandModel;
use App\Models\FolderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use App\Models\File;
use App\Models\KoolReportModel;
use App\Models\ProductModel;
use App\Models\ReportModel;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        // KoolReport variable convert
        if (isset($_POST['saleDrillDown']['currentLevel'][1]['month']) && !ctype_digit($_POST['saleDrillDown']['currentLevel'][1]['month'])) {
            $_POST['saleDrillDown']['currentLevel'][1]['month'] = date('m', strtotime($_POST['saleDrillDown']['currentLevel'][1]['month']));
        }

        // Set default value for KoolReport
        lib()->cache->report_subscription_average_currency_code = 'USD';
        lib()->cache->report_subscription_average_currency_symbol = '$';
        lib()->cache->report_subscription_average_yearly_cost = 0;
        lib()->cache->report_subscription_average_monthly_cost = 0;
        lib()->cache->report_subscription_average_weekly_cost = 0;

        lib()->cache->report_lifetime_average_currency_code = 'USD';
        lib()->cache->report_lifetime_average_currency_symbol = '$';
        lib()->cache->report_lifetime_average_yearly_cost = 0;
        lib()->cache->report_lifetime_average_monthly_cost = 0;
        lib()->cache->report_lifetime_average_weekly_cost = 0;

        lib()->cache->report_subscription_summary_total_count = 0;
        lib()->cache->report_subscription_summary_active_count = 0;
        lib()->cache->report_subscription_summary_trial_count = 0;
    }

    public function index(Request $request)
    {
        // dd(ReportModel::get_subscription_mrp_area_chart());
        // lib()->cache->report_subscription_mrp_area_chart = ReportModel::get_subscription_mrp_area_chart();
        // lib()->cache->report_subscription_category_pie_chart = ReportModel::get_subscription_category_pie_chart();
        // lib()->cache->report_subscription_google_area_chart = ReportModel::get_subscription_mrp_area_chart();
        // lib()->cache->subscription_area_chart = lib()->kr->get_subscription_area_chart(local('subscription_days', 0));
        lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart();



        ReportModel::load_subscription_chart_data();



        $data = [
            'slug' => 'report',
        ];

        return view('client/report/index', $data);
    }

    public function get_sub_area_chart_1(Request $request)
    {
        $data = [
            'slug' => 'report',
        ];

        return view('client/koolreport/subscription/drilldown', ['data' => $data]);
        return view('client/report/subscription', $data);
    }


    public function get_subscription_mrp_area_chart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        $_SESSION['report_period'] = $request->input('period');

        // dd(ReportModel::get_subscription_mrp_area_chart());

        lib()->cache->report_subscription_mrp_area_chart = ReportModel::get_subscription_mrp_area_chart();

        $data = [
            'slug' => 'report',
        ];

        if (empty(lib()->cache->report_subscription_mrp_area_chart)) {
            // return response()->json(['status' => false, 'message' => 'No data found'], 422);
            return response()->json([
                'html' => '<img class="img-fluid mx-auto d-block" src="' . url('assets/images/placeholder/subscription.png') . '">',
            ]);
        } else {
            return response()->json([
                'html' => view('client/report/koolreport/subscription/mrp_area_chart')->render(),
            ]);
        }
    }


    public function get_subscription_all_charts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'nullable|string',
            'type' => 'required|integer',
            'folder_ids.*' => 'nullable|integer',
            'tag_ids.*' => 'nullable|integer',
            'payment_method_ids.*' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        $_SESSION['report_period'] = $request->input('period');

        // dd(ReportModel::get_subscription_mrp_area_chart());

        $data = [
            'type' => $request->input('type'),
            'folder_ids' => $request->input('folder_ids'),
            'tag_ids' => $request->input('tag_ids'),
            'payment_method_ids' => $request->input('payment_method_ids'),
        ];


        // Subscription all charts
        if ($request->input('type') == 1) {
            ReportModel::load_subscription_chart_data($data);
            return response()->json([
                'html' => view('client/report/subscription')->render(),
            ]);
        }

        // Lifetime all charts
        else {
            ReportModel::load_lifetime_chart_data($data);
            return response()->json([
                'html' => view('client/report/lifetime')->render(),
            ]);
        }
    }

    public function get_lifetime_drilldown_chart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'nullable|string',
            'folder_ids.*' => 'nullable|integer',
            'tag_ids.*' => 'nullable|integer',
            'payment_method_ids.*' => 'nullable|integer',
            'level' => 'required|string',
            'year' => 'nullable|digits:4|integer',
            'month_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        $_SESSION['report_period'] = $request->input('period');

        // dd(ReportModel::get_subscription_mrp_area_chart());

        $data = [
            'folder_ids' => $request->input('folder_ids'),
            'tag_ids' => $request->input('tag_ids'),
            'payment_method_ids' => $request->input('payment_method_ids'),
            'level' => $request->input('level'),
            'year' => $request->input('year'),
            'month_name' => $request->input('month_name'),
        ];

        ReportModel::get_lifetime_drilldown_chart($data);

        return response()->json([
            'html' => view('client/report/koolreport/lifetime/drilldown_chart')->render(),
        ]);
    }
}
