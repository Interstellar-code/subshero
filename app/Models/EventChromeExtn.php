<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Library\NotificationEngine;
use App\Models\Traits\CheckAlertType;

class EventChromeExtn extends Model
{
    use HasFactory;
    use CheckAlertType;

    protected $table = 'event_chrome_extn';
    public $timestamps = false;
    protected $attributes = [
        'status' => 0,
    ];
    protected $guarded = [];

    private static $all_type = [
        'subscription_renew' => 1,
        'subscription_delete' => 2,
    ];

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
            ['event_type', 'extension'],
            ['table_name', 'subscriptions'],
            ['table_row_id', $data['table_row_id']],
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

    public static function send_messages()
    {
        $count = 0;
        $all_cron = EventChrome::where([
            ['event_cron', 1],
            ['event_status', 0],
            ['table_name', 'subscriptions'],
        ])
            ->whereIn('event_type', ['extension', 'extension_refund', 'extension_expire'])
            ->whereIn('event_type_status', ['create', 'update', 'delete'])
            ->get();

        foreach ($all_cron as $event) {

            // Check scheduled date
            $now = lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]);

            $event->event_type_scdate = lib()->do->timezone_convert([
                'from_timezone' => $event->event_timezone,
                'to_timezone' => APP_TIMEZONE,
                'date_time' => $event->event_type_scdate,
            ]);

            // Skip if it is scheduled for future
            if ($event->event_type_scdate > $now) {
                continue;
            }

            $user = UserModel::get($event->user_id);
            $subscription = Subscription::with('alert:id,alert_types')->find($event->table_row_id);
            $product = ProductModel::get($event->event_product_id);

            if (!empty($user->id) && !empty($product->id)) {

                [
                    'template' => $template,
                ] = self::prepare_message_data_extension([
                    'user' => $user,
                    'product' => $product,
                    'subscription' => $subscription,
                    'event' => $event,
                ]);

                if (!empty($template)) {

                    if ($event->event_type_status != 'delete') {
                        NotificationEngine::add_event_for_subscription_extension_push([
                            'table_row_id' => $subscription->id,
                            'event_type_status' => 'renew',
                            'event_product_id' => $subscription->brand_id,
                            'event_type_schedule' => $subscription->recurring,
                            'event_types' => ['extension'],
                        ]);
                    }

                    // Update event logs
                    EventChrome::do_update($event->id, [
                        'event_status' => 1,
                        'event_type_status' => 'update',
                        'event_url' => url()->current(),
                        'event_timezone' => APP_TIMEZONE,
                        'event_datetime' => lib()->do->timezone_convert([
                            'to_timezone' => APP_TIMEZONE,
                        ]),
                    ]);

                    $count++;
                }
            }
        }
        //--------------------

        // Get all events
        $notifications_events = EventChrome::where([
            'event_type' => 'extension',
            'event_status' => 0,
            'event_cron' => 1,
            'table_name' => 'subscriptions',
        ])->get();

        foreach ($notifications_events as $event) {
            // Check scheduled date
            $now = lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]);

            $event->event_type_scdate = lib()->do->timezone_convert([
                'from_timezone' => $event->event_timezone,
                'to_timezone' => APP_TIMEZONE,
                'date_time' => $event->event_type_scdate,
            ]);

            // Skip if it is scheduled for future
            if ($event->event_type_scdate > $now) {
                continue;
            }

            $subscription = Subscription::find($event->table_row_id);

            if (empty($subscription->id)) {
                continue;
            }

            if (!self::needDo([
                'table_name' => 'subscriptions',
                'table_row_id' => $subscription->id,
            ])) {
                continue;
            }


            if (!empty($subscription->id)) {
                $message_data = self::prepare_message_data([
                    'subscription' => $subscription,
                ]);

                if (!$message_data['message'] || !$message_data['product']) {
                    continue;
                }



                $icon = asset_ver('assets/images/favicon.ico');

                if (!empty($subscription->favicon)) {
                    $icon = img_url($subscription->favicon, null);
                }


                // Add notification for Chrome extension
                $status = NotificationEngine::staticModel('extension')::add_from_mail([
                    'type' => $message_data['type'],
                    'user_id' => $subscription->user_id,
                    'subscription_id' => $subscription->id,
                    'alert_id' => $subscription->alert_id,
                    'title' => $subscription->product_name,
                    'message' => $message_data['message'],
                    'image' => $icon,
                ]);

                if ($status) {
                    $event->event_status = 1;
                } else {
                    $event->event_status = 2;
                }
                $event->save();

                $count++;
            }
        }
        return $count;
    }

    public static function prepare_message_data_extension(array $message_data)
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

            if ($event->event_type == 'extension_refund') {

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
                    return $default_result;
                }

                $template = TemplateModel::exists('subscription_refund');

                // Add Extension notification and Push notification in queue (events table) for sending
                NotificationEngine::add_event_for_subscription_extension_push([
                    'table_row_id' => $subscription->id,
                    'event_type_status' => 'before_refund',
                    'event_product_id' => $subscription->brand_id,
                    'event_type_schedule' => $subscription->recurring,
                    'event_types' => ['extension'],
                ]);
            }


            else if ($event->event_type == 'extension_expire') {

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
                    'event_types' => ['extension'],
                ]);
            }

            else {

                // Generate HTML mail template
                $template = TemplateModel::exists('subscription_renew');
            }
        }
        return [
            'template' => $template,
        ];
    }

    public static function prepare_message_data(array $message_data)
    {
        $subscription = $message_data['subscription'];
        $product = ProductModel::get($subscription->brand_id);
        $type = 'subscription_' . table('subscription.status_lowercase', $subscription->status);
        $result = [
            'message' => '',
            'title' => '',
            'type' => $type,
            'subscription' => $subscription,
            'product' => $product,
        ];
        // For renewal
        if ($type == 'subscription_active') {
            $type = 'subscription_refund_remind';
            // Get current time
            $now = lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]);
            $now_int_time = strtotime($now);
            $new_time = strtotime($subscription->next_payment_date);
            $date_diff = $new_time - $now_int_time;
            $subscription->renew_days = round($date_diff / (60 * 60 * 24));


            $result['message'] = NotificationTemplate::get_message($type, [
                '{subscription_renew_days}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_refund_days_left}' => [
                    'subscription' => $subscription,
                ],
                '{product_name}' => [
                    'subscription' => $subscription,
                    'product' => $product,
                ],
            ]);


            $result['title'] = NotificationTemplate::get_title($type, [
                '{subscription_renew_days}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_refund_days_left}' => [
                    'subscription' => $subscription,
                ],
                '{product_name}' => [
                    'subscription' => $subscription,
                    'product' => $product,
                ],
            ]);
        }

        // For refund
        else if ($type == 'subscription_refund') {

            // Set type to refund
            // Refund
            if ($subscription->status == 3) {
                $type = 'subscription_refund_success';
            } else {
                if (!empty($subscription->refund_date)) {
                    $type = 'subscription_refund_remind';
                }
            }

            $result['message'] = NotificationTemplate::get_message($type, [
                '{subscription_refund_days_left}' => [
                    'subscription' => $subscription,
                ],
                '{product_name}' => [
                    'subscription' => $subscription,
                    'product' => $product,
                ],
            ]);


            $result['title'] = NotificationTemplate::get_title($type, [
                '{subscription_refund_days_left}' => [
                    'subscription' => $subscription,
                ],
                '{product_name}' => [
                    'subscription' => $subscription,
                    'product' => $product,
                ],
            ]);
        }
        $result['type'] = $type;
        return $result;
    }
}
