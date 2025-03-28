<?php

namespace App\Models;

use App\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationTemplate extends BaseModel
{
    use HasFactory;

    private const TABLE = '';
    protected $table = '';
    private static $user_id = NULL;

    private static $dynamic_fields = [
        'user_first_name',
        'user_last_name',
        'user_full_name',
        'user_email',
        'app_url',
        'email_verify_url',
        'password_reset_url',
        'new_password_url',
        'subscription_url',
        'subscription_image_url',
        'subscription_renew_date',
        'subscription_price',
        'subscription_payment_mode',
        'product_name',
        'product_type',
        'product_description',
        'plan_name',
        'plan_price',
        'plan_type',
        'plan_expire_date',
        'transaction_id',
        'invoice_url',
        'subscription_refund_date',
        'subscription_refund_days_left',
        'invitation_url',
    ];

    private static $templates = [
        'subscription_create' => [
            'title' => 'Subscription Created',
            'message' => 'Your subscription has been created successfully.',
        ],
        'subscription_update' => [
            'title' => 'Subscription Updated',
            'message' => 'Your subscription has been updated successfully.',
        ],
        'subscription_delete' => [
            'title' => 'Subscription Deleted',
            'message' => 'Your subscription has been deleted successfully.',
        ],
        'subscription_active' => [
            'title' => 'Subscription Activated',
            'message' => 'Your subscription has been activated successfully.',
        ],
        'subscription_renew' => [
            'title' => 'Subscription Renew',
            'message' => 'Your {product_name} will renew in {subscription_renew_days} days, so we thought we would check in.',
        ],
        'subscription_refund_remind' => [
            'title' => 'Subscription Refund',
            'message' => 'Your subscription refund period for {product_name} will expire in {subscription_refund_days_left} days.',
        ],
        'subscription_refund_success' => [
            'title' => 'Subscription Refund',
            'message' => 'Your subscription for {product_name} has been refunded successfully.',
        ],
        'subscription_cancel' => [
            'title' => 'Subscription Canceled',
            'message' => 'Your subscription has been canceled successfully.',
        ],
    ];

    public static function get($type)
    {
        return DB::table(self::TABLE)
            ->where('type', $type)
            ->where('is_default', 1)
            ->where('status', 1)
            ->get()
            ->first();
    }

    // public static function generate($email_type, $user_email)
    // {
    //     $user = UserModel::get_by_email($user_email);
    //     if (empty($user)) {
    //         return false;
    //     }

    //     $template = self::get($email_type);
    //     if (empty($template)) {
    //         return false;
    //     }

    //     $template_body = self::set_dynamic_field($template->body, [
    //         '{user_first_name}' => [
    //             'user' => $user,
    //         ],
    //         '{email_verify_url}' => [
    //             'token'
    //         ],
    //     ]);
    // }

    public static function generate($email_type, $search_replace)
    {
        $template = self::get($email_type);
        if (empty($template)) {
            return false;
        }

        $template_body = self::set_dynamic_field($template->body, $search_replace);
        $template_subject = self::set_dynamic_field($template->subject, $search_replace);

        return [
            'subject' => $template_subject,
            'body' => $template_body,
        ];
    }

    public static function get_title($type, array $search_replace)
    {
        if (isset(self::$templates[$type])) {
            $template = self::$templates[$type];
            $title = self::set_dynamic_field($template['title'], $search_replace);
            return $title;
        }

        return null;
    }

    public static function get_message($type, array $search_replace)
    {
        if (isset(self::$templates[$type])) {
            $template = self::$templates[$type];
            $message = self::set_dynamic_field($template['message'], $search_replace);
            return $message;
        }

        return null;
    }

    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('email_templates.user_id', self::get_user_id($user_id))
            ->select('email_templates.*')
            ->get();
    }

    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }


    private static function set_dynamic_field($text, $search_replace)
    {
        if (!empty($text) && is_array($search_replace)) {
            foreach ($search_replace as $key => $val) {
                $text = str_replace($key, self::get_dynamic_field($key, $val), $text);
            }
        }

        return $text;
    }

    private static function get_dynamic_field($search, $data = null)
    {
        switch ($search) {
            case '{user_first_name}':
                if (isset($data['user'])) {
                    return $data['user']->first_name;
                }
                return null;
                break;

            case '{user_last_name}':
                if (isset($data['user'])) {
                    return $data['user']->last_name;
                }
                return null;
                break;

            case '{user_full_name}':
                if (isset($data['user'])) {
                    return $data['user']->name;
                }
                return null;
                break;

            case '{user_email}':
                if (isset($data['user'])) {
                    return $data['user']->email;
                }
                return null;
                break;

            case '{subscription_url}':
                if (isset($data['subscription'])) {
                    // return $data['subscription']->url;
                    return url('subscription');
                }
                return null;
                break;

            case '{subscription_image_url}':
                if (isset($data['subscription'])) {
                    return url('storage/' . base64_encode($data['subscription']->image));
                }
                return null;
                break;

            case '{subscription_renew_date}':
                if (isset($data['subscription'])) {
                    // return date('d F, Y', strtotime($data['subscription']->payment_date));

                    // Get the next payment date from the subscription
                    if (empty($data['subscription']->next_payment_date)) {
                        return date('d F, Y', strtotime($data['subscription']->payment_date));
                    } else {
                        return date('d F, Y', strtotime($data['subscription']->next_payment_date));
                    }
                }
                return null;
                break;

            case '{subscription_renew_days}':
                if (isset($data['subscription'])) {
                    return $data['subscription']->renew_days;
                }
                return null;
                break;

            case '{subscription_price}':
                if (isset($data['subscription'])) {
                    return $data['subscription']->price_type . ' ' . $data['subscription']->price;
                }
                return null;
                break;

            case '{subscription_payment_mode}':
                if (isset($data['payment_method'])) {

                    // Prevent background color blank in the template
                    if (empty($data['payment_method']->name)) {
                        return '&nbsp;';
                    } else {
                        return $data['payment_method']->name;
                    }
                }
                return null;
                break;

            case '{product_name}':
                if (isset($data['subscription'])) {
                    return $data['subscription']->product_name;
                }

                // Check for product
                else if (isset($data['product'])) {
                    return $data['product']->product_name;
                }
                return null;
                break;

            case '{product_type}':
                if (isset($data['subscription']) && !empty($data['subscription']->product_type_name)) {
                    return $data['subscription']->product_type_name;
                }

                // Check for product
                else if (isset($data['product']) && !empty($data['product']->product_type_name)) {
                    return $data['product']->product_type_name;
                }
                return null;
                break;

            case '{product_description}':
                if (isset($data['subscription'])) {
                    return $data['subscription']->description;
                }

                // Check for product
                else if (isset($data['product'])) {
                    return $data['product']->description;
                }
                return null;
                break;

            case '{product_image}':
                if (isset($data['product'])) {
                    return url('storage/' . base64_encode($data['product']->image));
                }
                return null;
                break;

            case '{plan_name}':
                if (isset($data['plan'])) {
                    return $data['plan']->name;
                }
                return null;
                break;

            case '{plan_price}':
                if (isset($data['plan'])) {
                    return $data['plan']->price_monthly;
                }
                return null;
                break;

            case '{plan_type}':
                if (isset($data['plan'])) {
                    return $data['plan']->type;
                }
                return null;
                break;

            case '{plan_expire_date}':
                // if (isset($data['product'])) {
                //     return $data['product']->product_name;
                // }
                return null;
                break;





            case '{password_reset_url}':
                // Check token and email
                if (isset($data['token']) && isset($data['email'])) {
                    return route('password.reset', $data['token']) . '?email=' . urlencode($data['email']);
                } else if (isset($data['token'])) {
                    return route('password.reset', $data['token']);
                }
                return null;
                break;

            case '{email_verify_url}':
                // Check token and user
                if (isset($data['token']) && isset($data['user'])) {
                    return route('user/email/verify/token', $data['token']) . '?email=' . urlencode($data['user']->email);
                } else if (isset($data['token'])) {
                    return route('user/email/verify/token', $data['token']);
                }
                return null;
                break;

            case '{new_password_url}':
                if (isset($data['token']) && isset($data['user']->email)) {
                    return route('user/confirm', $data['token']) . '?email=' . urlencode($data['user']->email);
                } else if (isset($data['token'])) {
                    return route('user/confirm', $data['token']);
                }
                return null;
                break;





            case '{contact_full_name}':
                if (isset($data['contact'])) {
                    return $data['contact']->name;
                }
                return null;
                break;

            case '{contact_email}':
                if (isset($data['contact'])) {
                    return $data['contact']->email;
                }
                return null;
                break;





            case '{subscription_refund_date}':
                if (isset($data['subscription'])) {
                    if (!empty($data['subscription']->refund_date)) {
                        return date('d F, Y', strtotime($data['subscription']->refund_date));
                    }
                }
                return null;
                break;

            case '{subscription_refund_days_left}':
                if (isset($data['subscription'])) {

                    if (!empty($data['subscription']->refund_date)) {

                        $now = strtotime(lib()->do->timezone_convert([
                            'to_timezone' => APP_TIMEZONE,
                        ]));
                        $refund_date = lib()->do->timezone_convert([
                            'date_time' => $data['subscription']->refund_date,
                            'to_timezone' => APP_TIMEZONE,
                            'return_format' => 'Y-m-d',
                        ]);

                        $date_diff = strtotime($refund_date) - $now;
                        $days_left = (int)round($date_diff / (60 * 60 * 24));

                        if ($days_left < 0) {
                            $days_left = 0;
                        }

                        return $days_left;
                    }
                }
                return null;
                break;






            case '{app_url}':
                return url('/');
                break;

            case '{invitation_url}':
                if (isset($data['token']) && isset($data['user']->email)) {
                    return route('user/team/invite/accept', $data['token']) . '?email=' . urlencode($data['user']->email);
                } else if (isset($data['token'])) {
                    return route('user/team/invite/accept', $data['token']);
                }
                return null;
                break;























            default:
                return null;
        }
    }
}
