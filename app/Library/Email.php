<?php

namespace App\Library;

// use Illuminate\Database\Eloquent\Model;

use App\Mail\DefaultMail;
use App\Mail\Test;
use App\Models\BrandModel;
use App\Models\FolderModel;
use App\Models\CustomerModel;
use App\Models\EmailModel;
use App\Models\EmailType;
use App\Models\ProductModel;
use App\Models\SettingsModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class Email extends Mail
{
	private static $default = [];

	/**
	 * Create a new class instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// parent::__construct();
	}

	public static function send_now($data)
	{
		// Set default values
		$data = self::set_default_val($data);

		$mail_config = Config::get('mail');

		// Create email send logs
		$email_id = EmailModel::log([
			'from_name' => (isset($mail_config['from']['name']) ? $mail_config['from']['name'] : null),
			'from_email' => (isset($mail_config['from']['address']) ? $mail_config['from']['address'] : null),
			'to_name' => $data['to_name'],
			'to_email' => $data['to_email'],
			'subject' => $data['subject'],
			'body' => $data['body'],
			'status' => 2,
			'created_at' => lib()->do->timezone_convert([
				'to_timezone' => APP_TIMEZONE,
			]),
			'created_by' => $data['user_id'],
			'created_timezone' => APP_TIMEZONE,
			// 'sent_at' => lib()->do->timezone_convert([
			// 	'to_timezone' => APP_TIMEZONE,
			// ]),
			// 'sent_by' => $data['user_id'],
			// 'sent_timezone' => APP_TIMEZONE,
		]);

		// Send test email or some other emails
		switch ($data['type']) {
			case 'test':
				Mail::to($data['to_email'])->send(new Test);
				break;

			default:
				// Mail to header
				$mail_to = [
					[
						'name' => $data['to_name'],
						'email' => $data['to_email'],
					],
				];

				// Send mail
				Mail::to($mail_to)->send(new DefaultMail([
					'subject' => $data['subject'],
					'html' => $data['body'],
				]));
		}

		// Get user profile
		$user_profile = DB::table('users_profile')
			->where('user_id', $data['user_id'])
			->get()
			->first();

		if (empty($user_profile)) {

			// Update email status
			EmailModel::log_update($email_id, [
				'status' => 1,
				'sent_at' => lib()->do->timezone_convert([
					'to_timezone' => APP_TIMEZONE,
				]),
				'sent_by' => $data['user_id'],
				'sent_timezone' => APP_TIMEZONE,
			]);
		}

		// Set user profile timezone
		else {

			// Update email status
			EmailModel::log_update($email_id, [
				'status' => 1,
				'sent_at' => lib()->do->timezone_convert([
					'to_timezone' => $user_profile->timezone,
				]),
				'sent_by' => $data['user_id'],
				'sent_timezone' => $user_profile->timezone,
			]);
		}


		return $email_id;
	}

	private static function set_default_val($data)
	{
		// Default values
		self::$default = [
			'type' => null,
			'from_name' => null,
			'from_email' => null,
			'to_name' => null,
			'to_email' => null,
			'subject' => null,
			'body' => null,
			'status' => 0,
			'created_at' => lib()->do->timezone_convert([
				'to_timezone' => APP_TIMEZONE,
			]),
			'created_by' => Auth::id(),
			'created_timezone' => APP_TIMEZONE,
		];

		foreach (self::$default as $key => $val) {
			if (!isset($data[$key])) {
				$data[$key] = $val;
			}
		}

		return $data;
	}
}
