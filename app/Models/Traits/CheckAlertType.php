<?php

namespace App\Models\Traits;

use App\Models\Subscription;
use App\Library\NotificationEngine;
use App\Models\EventBrowser;
use App\Models\EventChrome;
use Illuminate\Support\Facades\Auth;

trait CheckAlertType
{
    static $additional_models = [
        'browser' => EventBrowser::class,
        'extension' => EventChrome::class,
    ];

    public static function needCheckAlertType(array $data)
    {
        $data += ['table_name' => null, 'table_row_id' => null];
        return $data['table_name'] == 'subscriptions' && $data['table_row_id'] > 0;
    }

    public static function getTypeOfModel()
    {
        $model = self::class;
        $type = array_search($model, NotificationEngine::models);
        if (!$type) {
            $type = array_search($model, self::$additional_models);
        }
        return $type;
    }

    public static function alertIsEnabled(array $data)
    {
        $subscription = Subscription::with('alert:id,alert_types')->find($data['table_row_id']);
        if (!$subscription) {
            return false;
        }
        $type = self::getTypeOfModel();
        return in_array($type, $subscription->alert->alert_types);
    }

    public static function needDo(array $data)
    {
        if (self::needCheckAlertType($data)) {
            return self::alertIsEnabled($data);
        } else {
            return true;
        }
    }

    public static function do_create(array $data)
    {
        if (self::needDo($data)) {
            $type = self::getTypeOfModel();
            $model = self::$additional_models[$type] ?? self::class;
            return $model::create($data);
        } else {
            return null;
        }
    }

    public static function do_update($id, array $data)
    {
        if (self::needDo($data)) {
            $type = self::getTypeOfModel();
            $model = self::$additional_models[$type] ?? self::class;
            $event = $model::find($id);
            if ($event) {
                $event->update($data);
            }
        }
    }

    public static function refund_create_or_update(array $data)
    {
        $subscription = $data['subscription'];
        $alert_timezone = $data['alert_timezone'];
        $schedule_datetime = $data['schedule_datetime'];
        $type = self::getTypeOfModel();
        $model = self::$additional_models[$type] ?? self::class;
        $data_create = [
            'user_id' => Auth::id(),
            'event_type' => "{$type}_refund",
            'event_type_status' => 'create',
            'event_status' => 0,
            'table_name' => 'subscriptions',
            'table_row_id' => $subscription->id,
            'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
            'event_cron' => 1,
            'event_product_id' => $subscription->brand_id,
            'event_type_schedule' => 1,
            'event_type_scdate' => lib()->do->timezone_convert([
                'from_timezone' => $alert_timezone,
                'to_timezone' => APP_TIMEZONE,
                'date_time' => $schedule_datetime,
            ]),
        ];
        $data_update = $data_create;
        $data_update['event_type_status'] = 'update';
        $data_update += [
            'event_url' => url()->current(),
            'event_timezone' => APP_TIMEZONE,
            'event_datetime' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]),
        ];
        unset($data_update['user_id']);

        $event_id = $model::where([
            ['table_name', 'subscriptions'],
            ['event_type', "{$type}_refund"],
            ['table_row_id', $subscription->id],
        ])->value('id');
        // Check for new event
        if (!$event_id) {

            if (!empty($subscription->refund_date)) {

                // Create event logs
                $model::do_create($data_create);
            }
        } else {
            if (empty($subscription->refund_date)) {
                $model::find($event_id)->delete();
            } else {

                // Update event logs
                $model::do_update($event_id, $data_update);
            }
        }
    }

    public static function find_event($id)
    {
        $type = self::getTypeOfModel();
        $model = self::$additional_models[$type] ?? self::class;
        return $model::find($id);
    }

    public static function getNotifications(array $data)
    {
        $type = self::getTypeOfModel();
        $model = self::$additional_models[$type] ?? self::class;
        $notifications = $model::where([
            'event_status' => 0,
            'event_cron' => 1,
            'table_name' => 'subscriptions',
            'table_row_id' => $data['subscription_id'],
            'user_id' => $data['user_id'],
        ])->get()->toArray();
        $type = self::getTypeOfModel();
        $subscription = Subscription::find($data['subscription_id']);
        $favicon = $subscription->favicon;
        if (!$subscription->_favicon) {
            if (!$subscription->url) {
                $favicon = asset_ver('assets/images/favicon.ico');
            } else {
                $parsedUrl = parse_url($subscription->url);
                if (isset($parsedUrl['scheme']) && isset($parsedUrl['host'])) {
                    $favicon = "$parsedUrl[scheme]://$parsedUrl[host]/favicon.ico";
                }
            }
        }
        $favicon_html = $favicon ? "<img src='$favicon' style='width: 16px; height: 16px;'> " : '';
        $notifications = array_map(function ($notification) use ($type, $favicon_html) {
            $notification['notification_type'] = $type;
            $notification['favicon_html'] = $favicon_html;
            return $notification;
        }, $notifications);
        return $notifications;
    }
}
