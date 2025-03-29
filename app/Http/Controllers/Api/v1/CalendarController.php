<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Models\SubscriptionHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * CalendarController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get calendar api
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = [];

        $subscriptions = SubscriptionHistoryModel::get_by_user();
        if (!empty($subscriptions)) {
            foreach ($subscriptions as $val) {
                if (!empty($val->next_payment_date)) {
                    $val->image = img_url($val->image);
                    $val->calendar_date = $val->next_payment_date;
                    $data[] = $val;
                }
            }
        }

        // Return success response with records
        return response()->json($data, 200);
    }
}
