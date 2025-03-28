<?php

namespace App\Notifications;

use App\Models\EmailModel;
use App\Library\NotificationEngine;
use App\Models\TemplateModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PasswordReset extends Notification
{
    use Queueable;

    public $token = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        //
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = UserModel::get_by_email(request()->input('email'));
        if (empty($user)) {
            return false;
        }

        $template = NotificationEngine::staticModel('email')::prepare_message_template([
            '{user_first_name}' => [
                'user' => $user,
            ],
            '{password_reset_url}' => [
                'token' => $this->token,
                'email' => $user->email,
            ],
            'type' => 'password_reset_request',
        ]);

        // Check for template data
        if (empty($template)) {
            // return (new MailMessage);
        } else {

            $mail_config = Config::get('mail');

            // Create event logs
            NotificationEngine::staticModel('email')::do_create([
                'user_id' => $user->id,
                'event_type' => 'email',
                'event_type_status' => 'create',
                'event_status' => 1,
                'table_name' => 'password_resets',
                'table_row_id' => $user->id,
                'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
            ]);


            // Get user profile
            $user_profile = DB::table('users_profile')
                ->where('user_id', $user->id)
                ->get()
                ->first();
            $sent_data = [];

            if (empty($user_profile)) {

                // Update email status
                $sent_data = [
                    'status' => 1,
                    'sent_at' => lib()->do->timezone_convert([
                        'to_timezone' => APP_TIMEZONE,
                    ]),
                    'sent_by' => $user->id,
                    'sent_timezone' => APP_TIMEZONE,
                ];
            }

            // Set user profile timezone
            else {

                // Update email status
                $sent_data = [
                    'status' => 1,
                    'sent_at' => lib()->do->timezone_convert([
                        'to_timezone' => $user_profile->timezone,
                    ]),
                    'sent_by' => $user->id,
                    'sent_timezone' => $user_profile->timezone,
                ];
            }


            // Create email send logs
            EmailModel::log([
                'from_name' => (isset($mail_config['from']['name']) ? $mail_config['from']['name'] : null),
                'from_email' => (isset($mail_config['from']['address']) ? $mail_config['from']['address'] : null),
                'to_name' => $user->name,
                'to_email' => $user->email,
                'subject' => $template['subject'],
                'body' => $template['body'],
                'status' => 1,

                // Set user timezone
                'sent_at' => $sent_data['sent_at'],
                'sent_by' => $sent_data['sent_by'],
                'sent_timezone' => $sent_data['sent_timezone'],

                'created_at' => lib()->do->timezone_convert([
                    'to_timezone' => APP_TIMEZONE,
                ]),
                'created_by' => $user->id,
                'created_timezone' => APP_TIMEZONE,
            ]);

            return (new MailMessage)
                ->view('mail/load', [
                    'html' => $template['body'],
                ])
                ->subject($template['subject']);
        }


        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', url('/'))
        //     ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
