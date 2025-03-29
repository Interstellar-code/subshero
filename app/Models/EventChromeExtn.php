<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Library\NotificationEngine;
use App\Models\Traits\CheckAlertType;

/**
 * EventChromeExtn - Manages browser notification events
 * 
 * This model handles:
 * - Preparing and sending browser notifications
 * - Managing event data for notifications
 */
class EventChromeExtn extends Model
{
    use HasFactory;
    use CheckAlertType;

    /** @var string Table name for event browser notifications */
    protected $table = 'event_chrome_extn';
    
    /** @var array Default attribute values */
    protected $attributes = [
        'status' => 0,
    ];
    
    /** @var array Fields that are guarded from mass assignment */
    protected $guarded = [];

    private static $all_type = [
        'subscription_renew' => 1,
        'subscription_delete' => 2,
    ];

    /**
     * Add an event from mail
     * @param array $data Event data
     * @return int Inserted event ID
     */
    public static function add_from_mail($data)
    {
        if (!self::needDo([
            'table_name' => 'subscriptions',
            'table_row_id' => $data['subscription_id'],
        ])) {
            return;
        }
        $data['type'] = self::$all_type[$data['type'] ?? 0] ?? 0;

        $data['created_at'] = date('Y-m-d H:i:s');
        return self::insert($data);
    }

    /**
     * Add an event for subscription extension push
     * @param array $data Event data
     * @return bool True if event was added successfully
     */
    public static function add_event_for_subscription_extension_push($data)
    {
        if (!self::needDo([
            'table_name' => 'subscriptions',
            'table_row_id' => $data['table_row_id'],
        ])) {
            return false;
        }
        $subscription = Subscription::with('alert:id,alert_types,alert_condition')->find($data['table_row_id']);

        // Check if alert is enabled
        if (!in_array('extension', $subscription->alert->alert_types)) {
            return false;
        }

        if ($data['event_type_status'] == 'renew' && !in_array($subscription->alert->alert_condition, [1, 2])) {
            return false;
        } elseif (($data['event_type_status'] == 'refund' || $data['event_type_status'] == 'before_refund') && !in_array($subscription->alert->alert_condition, [1, 3])) {
            return false;
        }

        if (empty($subscription->id)) {
            return false;
        }

        $notifications_old_event_id = EventChrome::where([
            ['event_type' => 'extension'],
            ['table_name' => 'subscriptions'],
            ['table_row_id' => $data['table_row_id']],
        ])->value('id');

        if (!$notifications_old_event_id) {

            // Create event logs
            EventChrome::create([
                'user_id' => Auth::id(),
                'event_type' => 'extension',
                'event_type_status' => 'create',
                'event_status' => 0,
                'table_name' => 'subscriptions',
                'table_row_id' => $data['table_row_id'],
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                'event_cron' => 1,
                'event_product_id' => $data['event_product_id'],
                'event_type_schedule' => $data['event_type_schedule'],
                'event_type_scdate' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
                'event_type_source' => 1,
            ]);
        } else {

            // Update event logs
            EventChrome::do_update($notifications_old_event_id, [
                'event_type' => 'extension',
                'event_type_status' => 'update',
                'event_status' => 0,
                'table_name' => 'subscriptions',
                'table_row_id' => $data['table_row_id'],
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
                'event_cron' => 1,
                'event_product_id' => $data['event_product_id'],
                'event_type_schedule' => $data['event_type_schedule'],

                'event_url' => url()->current(),
                'event_timezone' => APP_TIMEZONE,
                'event_datetime' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
                'event_type_scdate' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
                'event_type_source' => 1,
            ]);
        }
    }

    public static function getNotification(array $data)
    {
        $event_id = $data['event_id'];
        $event = EventChrome::find($event_id);
        $subscription = Subscription::find($event->table_row_id);

        if (empty($subscription->id)) {
            return null;
        }

        if (!self::needDo([
            'table_name' => 'subscriptions',
            'table_row_id' => $subscription->id,
        ])) {
            return null;
        }
        $message_data = self::prepare_message_data([
            'subscription' => $subscription,
        ]);
        return (object) [
            'title' => $message_data['title'],
            'message' => $message_data['message'],
            'event_type_scdate' => $event->event_type_scdate,
        ];
    }

    public static function prepare_message_data_browser(array $message_data)
    {
        $template = null;
        $event = $message_data['event'];
        $subscription = $message_data['subscription'];
        $default_result = [
            'template' => $template,
        ];
        // For subscription delete
        if (empty($subscription) && $event->event_type_status == 'delete') {
            $template = TemplateModel::exists('subscription_delete');
        }

        // For subscription create, update
        else if (!empty($subscription->id)) {

            $now = lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]);
            $now_int_time = strtotime($now);
            $new_time = strtotime($subscription->next_payment_date);
            $date_diff = $new_time - $now_int_time;
            $subscription->renew_days = round($date_diff / (60 * 60 * 24));

            // Set 0 if negative value
            if ($subscription->renew_days <= 0) {
                $subscription->renew_days = 0;
            }

            if ($event->event_type == 'browser_refund') {

                if (empty($subscription->refund_date)) {
                    return $default_result;
                }

                // Check if refund date is already passed
                $refund_date = lib()->do->timezone_convert([
                    'from_timezone' => $event->event_timezone,
                    'to_timezone' => APP_TIMEZONE,
                    'date_time' => $subscription->refund_date,
                ]);

                if ($refund_date < $now) {
                    $event->delete();
                    return $default_result;
                }

                $template = TemplateModel::exists('subscription_refund');

                // Add Extension notification and Push notification in queue (events table) for sending
                NotificationEngine::add_event_for_subscription_extension_push([
                    'table_row_id' => $subscription->id,
                    'event_type_status' => 'before_refund',
                    'event_product_id' => $subscription->brand_id,
                    'event_type_schedule' => $subscription->recurring,
                    'event_types' => ['browser'],
                ]);
            }

            else if ($event->event_type == 'browser_expire') {

                if (empty($subscription->expiry_date)) {
                    return $default_result;
                }

                // Check if expire date is already passed
                $expiry_date = lib()->do->timezone_convert([
                    'from_timezone' => $event->event_timezone,
                    'to_timezone' => APP_TIMEZONE,
                    'date_time' => $subscription->expiry_date,
                ]);

                if ($expiry_date > $now) {
                    return $default_result;
                }

                $template = TemplateModel::exists('subscription_expire');

                // Add Extension notification and Push notification in queue (events table) for sending
                NotificationEngine::add_event_for_subscription_extension_push([
                    'table_row_id' => $subscription->id,
                    'event_type_status' => 'before_expire',
                    'event_product_id' => $subscription->brand_id,
                    'event_type_schedule' => $subscription->recurring,
                    'event_types' => ['browser'],
                ]);
            }

            else {
                $template = TemplateModel::exists('subscription_renew');
            }
        }
        return [
            'template' => $template,
        ];
    }
}
