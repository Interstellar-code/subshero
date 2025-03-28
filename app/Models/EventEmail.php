<?php

namespace App\Models;

use App\BaseModel;
use App\Library\Email;
use App\Models\Traits\CheckAlertType;

class EventEmail extends BaseModel
{
    use CheckAlertType;
    public $timestamps = false;
    protected $guarded = [];
    protected $attributes = [
        'event_timezone' => APP_TIMEZONE,
        'event_type_color' => 'green',
        'event_type_schedule' => 0,
        'event_cron' => 0,
        'event_migrate' => 0,
    ];

    public function __construct(array $attributes = [])
    {
        $this->attributes['event_datetime'] = lib()->do->timezone_convert([
            'to_timezone' => APP_TIMEZONE,
        ]);
        $this->attributes['event_url'] = url()->current();
        parent::__construct($attributes);
    }

    public static function set_del_status($table_name, $table_row_id)
    {
        self::whereIn('event_type', ['email', 'email_refund'])
            ->where([
                ['table_name', $table_name],
                ['table_row_id', $table_row_id]
            ])
            ->update([
                'event_type_status' => 'delete',
                'event_timezone' => APP_TIMEZONE,
                'event_datetime' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
            ]);
    }

    public static function set_cancel_status($table_name, $table_row_id)
    {
        self::where([
            ['event_type', 'email'],
            ['table_name', $table_name],
            ['table_row_id', $table_row_id]
        ])->update([
            'event_cron' => 0,
            'event_type_schedule' => 0,
            'event_timezone' => APP_TIMEZONE,
            'event_datetime' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]),
        ]);
    }

    public static function set_next_payment_date($subscription_id, $next_payment_date)
    {
        $history = SubscriptionModel::get_last_history($subscription_id, 1);

        // Update history
        if (!empty($history->id)) {
            SubscriptionHistoryModel::do_update($history->id, [
                'next_payment_date' => $next_payment_date,
            ]);
        }
        self::where([
            ['event_type', 'email'],
            ['table_name', 'subscriptions'],
            ['table_row_id', $subscription_id]
        ])->update([
            'event_type_status' => 'update',
            'event_timezone' => APP_TIMEZONE,
            'event_datetime' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]),
            'event_type_scdate' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
                'date_time' => $next_payment_date,
            ]),
        ]);
    }

    public static function send_message(array $message_data)
    {
        $user = $message_data['user'];
        $template = $message_data['template'];
        $send_data = [
            'user_id' => $user->id,
            'to_name' => $user->name,
            'to_email' => $user->email,
            'subject' => $template['subject'],
            'body' => $template['body'],
        ];
        if (isset($message_data['type'])) {
            $send_data['type'] = $message_data['type'];
        }
        $email_id = Email::send_now($send_data);
        return $email_id;
    }

    public static function prepare_message_template(array $message_data)
    {
        $type = null;
        if (isset($message_data['type'])) {
            $type = $message_data['type'];
            unset($message_data['type']);
        } else {
            return null;
        }
        $template = TemplateModel::generate($type, $message_data);
        return $template;
    }

    public static function prepare_message_data(array $messages_data)
    {
        $template = null;
        $event = $messages_data['event'];
        $user = $messages_data['user'];
        $product = $messages_data['product'];
        $subscription = $messages_data['subscription'];
        $unlock = $messages_data['unlock'] ?? false;
        $contact_all = UsersContact::where([
            ['user_id', $user->id],
            ['status', 1]
        ])->get();
        $contact_mail_all = [];
        $default_result = [
            'template' => $template,
            'contact_mail_all' => $contact_mail_all,
        ];
        // For subscription delete
        if (empty($subscription) && $event->event_type_status == 'delete') {
            $template = self::prepare_message_template([
                '{user_first_name}' => [
                    'user' => $user,
                ],
                '{user_last_name}' => [
                    'user' => $user,
                ],
                '{user_full_name}' => [
                    'user' => $user,
                ],
                '{user_email}' => [
                    'user' => $user,
                ],
                '{product_name}' => [
                    'product' => $product,
                ],
                '{product_type}' => [
                    'product' => $product,
                ],
                '{product_description}' => [
                    'product' => $product,
                ],
                '{product_image}' => [
                    'subscription' => $subscription,
                ],
                'type' => 'subscription_delete',
            ]);
        }

        // For subscription create, update
        else if (!empty($subscription->id)) {

            // Get payment information
            $payment_mode = PaymentMethodModel::get($subscription->payment_mode_id);

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

            // Generate HTML mail template
            $data = [
                '{user_first_name}' => [
                    'user' => $user,
                ],
                '{user_last_name}' => [
                    'user' => $user,
                ],
                '{user_full_name}' => [
                    'user' => $user,
                ],
                '{user_email}' => [
                    'user' => $user,
                ],
                // '{subscription_url}' => [],
                '{subscription_url}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_image_url}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_price}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_payment_mode}' => [
                    'payment_method' => $payment_mode,
                ],
                '{subscription_renew_date}' => [
                    'subscription' => $subscription,
                ],
                '{subscription_renew_days}' => [
                    'subscription' => $subscription,
                ],
                '{product_name}' => [
                    'subscription' => $subscription,
                    'product' => $product,
                ],
                '{product_type}' => [
                    'product' => $product,
                ],
                '{product_description}' => [
                    'product' => $product,
                ],
                '{product_image}' => [
                    'product' => $product,
                ],
            ];


            // email_refund event
            if ($event->event_type == 'email_refund') {

                if (empty($subscription->refund_date)) {
                    return $default_result;
                }

                // Check if refund date is already passed
                $refund_date = lib()->do->timezone_convert([
                    'from_timezone' => $event->event_timezone,
                    'to_timezone' => APP_TIMEZONE,
                    'date_time' => $subscription->refund_date,
                ]);

                if ($refund_date < $now && !$unlock) {
                    return $default_result;
                }


                // Set variables data
                $data['{subscription_refund_date}'] = [
                    'subscription' => $subscription,
                ];
                $data['{subscription_refund_days_left}'] = [
                    'subscription' => $subscription,
                ];

                // Generate HTML mail template
                $data['type'] = 'subscription_refund';
                $template = self::prepare_message_template($data);
            }


            // email_expire event
            else if ($event->event_type == 'email_expire') {

                if (empty($subscription->expiry_date) && !$unlock) {
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


                // Set variables data
                $data['{subscription_expiry_date}'] = [
                    'subscription' => $subscription,
                ];

                // Generate HTML mail template
                $data['type'] = 'subscription_expire';
                $template = self::prepare_message_template($data);
            }


            // email event
            else {

                // Generate HTML mail template
                $data['type'] = 'subscription_renew';
                $template = self::prepare_message_template($data);

                // Get contact data
                foreach ($contact_all as $contact) {

                    // Generate HTML mail template
                    $contact_data = [
                        '{contact_full_name}' => [
                            'contact' => $contact,
                        ],
                        '{contact_email}' => [
                            'contact' => $contact,
                        ],
                        '{subscription_url}' => [
                            'subscription' => $subscription,
                        ],
                        '{subscription_image_url}' => [
                            'subscription' => $subscription,
                        ],
                        '{subscription_price}' => [
                            'subscription' => $subscription,
                        ],
                        '{subscription_payment_mode}' => [
                            'payment_method' => $payment_mode,
                        ],
                        '{subscription_renew_date}' => [
                            'subscription' => $subscription,
                        ],
                        '{subscription_renew_days}' => [
                            'subscription' => $subscription,
                        ],
                        '{product_name}' => [
                            'subscription' => $subscription,
                            'product' => $product,
                        ],
                        '{product_type}' => [
                            'product' => $product,
                        ],
                        '{product_description}' => [
                            'product' => $product,
                        ],
                        '{product_image}' => [
                            'product' => $product,
                        ],
                        'type' => 'subscription_renew_contact',
                    ];

                    // Generate data to send to email
                    $contact_mail_all[] = [
                        'name' => $contact->name,
                        'email' => $contact->email,

                        // Generate HTML mail template
                        'template' => self::prepare_message_template($contact_data),
                    ];
                }
            }
        }
        return [
            'template' => $template,
            'contact_mail_all' => $contact_mail_all,
        ];
    }

    public static function send_messages()
    {
        $count = 0;
        $all_cron = self::where([
            ['event_cron', 1],
            ['event_status', 0],
            ['table_name', 'subscriptions'],
        ])
            ->whereIn('event_type', ['email', 'email_refund', 'email_expire'])
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
                    'contact_mail_all' => $contact_mail_all,
                ] = self::prepare_message_data([
                    'user' => $user,
                    'product' => $product,
                    'subscription' => $subscription,
                    'event' => $event,
                ]);

                if (!empty($template)) {

                    if ($event->event_type_status == 'delete') {

                        // Send email and log this
                        self::send_message([
                            'user' => $user,
                            'template' => $template,
                        ]);
                    } else {
                        // Check if alert is enabled
                        if (!empty($subscription->alert->alert_types) && in_array('email', $subscription->alert->alert_types)) {

                            // Send email and log this
                            self::send_message([
                                'user' => $user,
                                'template' => $template,
                            ]);
                        }
                    }

                    // Update event logs
                    self::do_update($event->id, [
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


                if (!empty($contact_mail_all) && !empty($subscription->alert->alert_types) && in_array('email', $subscription->alert->alert_types)) {

                    foreach ($contact_mail_all as $contact) {

                        // Send email and log this
                        self::send_message([
                            'user' => (object) [
                                'id' => $user->id,
                                'name' => $contact['name'],
                                'email' => $contact['email'],
                            ],
                            'template' => $contact['template'],
                        ]);
                    }
                }
            }
        }
        return $count;
    }

    public static function getNotification(array $data)
    {
        $event_id = $data['event_id'];
        $event = self::find($event_id);
        $user = UserModel::get($event->user_id);
        $subscription = Subscription::with('alert:id,alert_types')->find($event->table_row_id);
        $product = ProductModel::get($event->event_product_id);
        if ($user && $subscription) {
            [
                'template' => $template,
            ] = self::prepare_message_data([
                'user' => $user,
                'product' => $product,
                'subscription' => $subscription,
                'event' => $event,
                'unlock' => true,
            ]);
            $message = $template['body'] ?? '';
            preg_match('/<body[^>]*>(.*?)<\/body>/is', $message, $matches);
            $message = $matches[1] ?? '';
            $message = strip_tags($message);
            $message = trim(preg_replace('/\s+/', ' ', $message));
            $message = preg_replace('/&#?\w{0,7};/i', '', $message);
            if ($template) {
                return (object) [
                    'title' => $template['subject'],
                    'message' => $message,
                    'event_type_scdate' => $event->event_type_scdate,
                ];
            }
        }
    }
}
