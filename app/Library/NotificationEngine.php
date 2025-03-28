<?php

namespace App\Library;

use App\Models\Event;
use App\Models\EventBrowserNotify;
use App\Models\EventChromeExtn;
use App\Models\EventEmail;
use App\Models\EventWebhook;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class NotificationEngine
{
    public const models = [
        'event' => Event::class,
        'email' => EventEmail::class,
        'browser' => EventBrowserNotify::class,
        'extension' => EventChromeExtn::class,
        // 'mobile' => EventMobile::class,
        // 'webhook' => EventWebhook::class,
    ];
    protected const notification_types = [
        'email' => 'Email',
        'browser' => 'Browser notificaton',
        'extension' => 'Chrome extension notification',
        'mobile' => 'Mobile notification',
        'webhook' => 'Webhook notification',
    ];
    protected const default_model = 'event';
    protected const tmp_old_to_new = [
        1 => 'email',
        2 => 'browser',
        3 => 'extension',
        4 => 'mobile',
        5 => 'webhook',
    ];
    protected const notification_icons = [
        'email' => 'fa fa-envelope',
        'browser' => 'fa fa-globe',
        'extension' => 'fa fa-cogs',
        'mobile' => 'fa fa-mobile',
        'webhook' => 'fa fa-link',
    ];

    public static function staticModel($type = self::default_model)
    {
        if (!in_array($type, array_keys(self::models))) {
            $type = self::default_model;
        }
        return self::models[$type];
    }

    public static function newModel($type = self::default_model, $attributes = [])
    {
        $model = self::staticModel($type);
        return new $model($attributes);
    }

    public static function table($type = self::default_model)
    {
        return self::newModel($type)->getTable();
    }

    public static function events_table($type = self::default_model)
    {
        $additional_models_keys = array_keys(self::staticModel($type)::$additional_models);
        if (in_array($type, $additional_models_keys)) {
            $model = self::staticModel($type)::$additional_models[$type];
            return (new $model)->getTable();
        } else {
            return self::table($type);
        }
    }

    public static function getNotificationsByUser($user_id = null)
    {
        $subscriptions = Subscription::with('alert:id,alert_types')->whereUserId($user_id ?? Auth::id())->get();
        $notifications = [];
        foreach ($subscriptions as $subscription) {
            $alert_types = $subscription->alert->alert_types;
            foreach ($alert_types as $alert_type) {
                $notifications[] = self::staticModel($alert_type)::getNotifications([
                    'subscription_id' => $subscription->id,
                    'user_id' => $user_id ?? Auth::id(),
                ]);
            }
        }
        $notifications = array_merge(...$notifications);
        $result = [];
        foreach ($notifications as $notification) {
            $event_type_scdate = $notification['event_type_scdate'];
            $event_type_scdate = date('Y-m-d', strtotime($event_type_scdate));
            $favicon_html = $notification['favicon_html'] ?? '';
            $title_icon = self::notification_icons[$notification['notification_type']] ?? '';
            $title_icon = "<i class='$title_icon'></i>";
            $subscription = Subscription::find($notification['table_row_id']);
            $text = self::staticModel($notification['notification_type'])::getNotification(['event_id' => $notification['id']]);
            $title = $text->title ?? '';
            $title = "<span>$title</span>";
            $result[]= [
                'title' => "$title_icon $favicon_html $title",
                'start' => $event_type_scdate,
                'color' => null,
                'folder_id' => 0,
                'subscription_id' => $notification['table_row_id'],
                'kind' => 'notification',
                'notification_type' => $notification['notification_type'],
                'notification_id' => $notification['id'],
            ];
        }
        return $result;
    }

    public static function getNotificationTypes()
    {
        return array_keys(self::models);
    }

    public static function getLangNotificationType($type)
    {
        return __(self::notification_types[$type] ?? '');
    }

    public static function set_next_payment_date(array $data)
    {
        $event_types = $data['event_types'];
        $subscription_id = $data['subscription_id'];
        $next_payment_date = $data['next_payment_date'];
        foreach ($event_types as $this_type) {
            NotificationEngine::staticModel($this_type)::set_next_payment_date($subscription_id, $next_payment_date);
        }
    }

    public static function set_del_status(array $data)
    {
        $event_types = $data['event_types'];
        $subscription_id = $data['subscription_id'];
        foreach ($event_types as $this_type) {
            NotificationEngine::staticModel($this_type)::set_del_status('subscriptions', $subscription_id);
        }
    }

    public static function set_cancel_status(array $data)
    {
        $event_types = $data['event_types'];
        $subscription_id = $data['subscription_id'];
        foreach ($event_types as $this_type) {
            NotificationEngine::staticModel($this_type)::set_cancel_status('subscriptions', $subscription_id);
        }
    }

    public static function add_event_for_subscription_extension_push(array $data)
    {
        $event_types = $data['event_types'];
        unset($data['event_types']);
        foreach ($event_types as $type) {
            NotificationEngine::staticModel($type)::add_event_for_subscription_extension_push($data);
        }
    }

    public static function send_messages()
    {
        $event_types = self::getNotificationTypes();
        $event_types = array_diff($event_types, ['event']);
        $count = 0;
        foreach ($event_types as $type) {
            $count += NotificationEngine::staticModel($type)::send_messages();
        }
        return $count;
    }

    public static function refund_create_or_update(array $data)
    {
        $event_types = self::getNotificationTypes();
        $event_types = array_diff($event_types, ['event']);
        foreach ($event_types as $type) {
            NotificationEngine::staticModel($type)::refund_create_or_update($data);
        }
    }
}
