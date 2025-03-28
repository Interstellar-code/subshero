<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Library\NotificationEngine;
use App\Models\FolderModel;
use App\Models\SubscriptionHistoryModel;
use App\Models\SubscriptionModel;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'calendar',
            'folder' => FolderModel::get_by_user(),
            'events' => [],
        ];


        $subscriptions = SubscriptionHistoryModel::get_by_user();
        if (!empty($subscriptions)) {
            foreach ($subscriptions as $val) {

                if (!empty($val->next_payment_date)) {
                    $icon = '💰';

                    if ($val->recurring == 1) {
                        $icon .= '⏰';
                    } else if ($val->recurring == 0) {
                        $icon .= '🗓';
                    }

                    // Check alert type
                    if ($val->alert_type == 1) {
                        $icon .= '📧';
                    }

                    // Active status check
                    if ($val->status == 1) {
                    }

                    // Cancel status check
                    else if ($val->status == 2) {
                        $icon .= '❌';
                    }

                    // Addon check
                    if ($val->sub_addon == 1) {
                        $icon .= '✚';
                    }

                    $data['events'][] = [
                        'title' => $icon . ' ' . $val->product_name,
                        'start' => date('Y-m-d', strtotime($val->next_payment_date)),
                        'color' => $val->folder_color,
                        'folder_id' => $val->folder_id,
                        'subscription_id' => $val->subscription_id,
                        'kind' => 'subscription',
                    ];
                }
            }
        }
        $notification_messages = NotificationEngine::getNotificationsByUser();
        $data['events'] = array_merge($data['events'], $notification_messages);
        return view('client/calendar/index', $data);
    }
}
