<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
use App\Models\ProductModel;
use App\Models\TagModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;

class KoolReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function subscription(Request $request, $chart_name)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|between:1,' . len()->subscriptions->type,
            'days' => 'required|integer|between:0,730',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // AreaChart
        if ($chart_name == 'area_chart') {
            $data = [
                'days' => $request->input('days'),
            ];

            $_SESSION['subscription_days'] = $request->input('days');
            lib()->cache->subscription_area_chart = SubscriptionModel::get_subscription_area_chart(local('subscription_days', 0));

            return view('client/koolreport/subscription/area_chart', ['data' => $data]);
        }

        // Drilldown
        else if ($chart_name == 'drilldown') {
            $data = [
                'days' => $request->input('days'),
            ];

            $_SESSION['lifetime_days'] = $request->input('days');

            return view('client/koolreport/subscription/drilldown', ['data' => $data]);
        }
    }

    public function get_drilldown_tooltip(Request $request)
    {
        return response()->json(local('subscription_drilldown', []));
    }
}
