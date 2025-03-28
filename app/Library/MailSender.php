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
use App\Models\TeamModel;
use App\Models\TemplateModel;
use App\Models\TokenModel;
// use App\Models\User;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;

class MailSender
{
	public static function user_registration($user_id)
	{
		$user = UserModel::get($user_id);
		if (empty($user)) {
			return false;
		}

		// Generate token
		$token = Str::lower(Str::random(64));
		TokenModel::create([
			'user_id' => $user->id,
			'table_name' => 'users',
			'table_row_id' => $user->id,
			'type' => 'user_verify_email',
			'email' => $user->email,
			'token' => Hash::make($token),
			'expire_at' => Carbon::now()->addDays(1)->format(APP_TIMESTAMP_FORMAT),
		]);

		$template = NotificationEngine::staticModel('email')::prepare_message_template([
			'{user_first_name}' => [
				'user' => $user,
			],
			'{email_verify_url}' => [
				'token' => $token,
				'user' => $user,
			],
			'type' => 'user_registration',
		]);

		// Send email and log this
		NotificationEngine::staticModel('email')::send_message([
			'user' => $user,
			'template' => $template,
		]);

		// Create event logs
		NotificationEngine::staticModel('email')::do_create([
			'user_id' => $user->id,
			'event_type' => 'email',
			'event_type_status' => 'create',
			'event_status' => 1,
			'table_name' => 'users',
			'table_row_id' => $user->id,
			'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
		]);

		return true;
	}

	public static function webhook_user_create($user_id)
	{
		// User create manually

		$user = UserModel::get($user_id);
		if (empty($user)) {
			return false;
		}

		$template = NotificationEngine::staticModel('email')::prepare_message_template([
			'{user_first_name}' => [
				'user' => $user,
			],
			'{new_password_url}' => [
				'token' => Str::lower(Str::random(64)),
			],
			'type' => 'webhook_user_create',
		]);

		// Send email and log this
		NotificationEngine::staticModel('email')::send_message([
			'user' => $user,
			'template' => $template,
		]);

		// Create event logs
		NotificationEngine::staticModel('email')::do_create([
			'user_id' => $user->id,
			'event_type' => 'email',
			'event_type_status' => 'create',
			'event_status' => 1,
			'table_name' => 'users',
			'table_row_id' => $user->id,
			'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
		]);

		return true;
	}

	public static function team_user_invite($user_id)
	{
		// User create manually

		$user = UserModel::get($user_id);
		$users_teams = TeamModel::where('pro_user_id', $user_id)->first();

		if (empty($user->id) | empty($users_teams->id)) {
			return false;
		}

		// Generate token
		$token = Str::lower(Str::random(64));
		TokenModel::create([
			'user_id' => $user->id,
			'table_name' => 'users_teams',
			'table_row_id' => $users_teams->id,
			'type' => 'team_user_invite',
			'email' => $user->email,
			'token' => Hash::make($token),
			'expire_at' => Carbon::now()->addDays(1)->format(APP_TIMESTAMP_FORMAT),
		]);


		$template = NotificationEngine::staticModel('email')::prepare_message_template([
			'{app_url}' => [],
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
			'{invitation_url}' => [
				'user' => $user,
				'token' => $token,
			],
			'type' => 'team_user_invite',
		]);

		// Send email and log this
		NotificationEngine::staticModel('email')::send_message([
			'user' => $user,
			'template' => $template,
		]);

		// Create event logs
		NotificationEngine::staticModel('email')::do_create([
			'user_id' => $user->id,
			'event_type' => 'email',
			'event_type_status' => 'create',
			'event_status' => 1,
			'table_name' => 'users_teams',
			'table_row_id' => $users_teams->id,
			'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
		]);

		return true;
	}

	public static function password_reset_link($email_address)
	{
		$user = \App\User::where('email', $email_address)->first();
		$token = Password::getRepository()->create($user);
		return $user->sendPasswordResetNotification($token);
	}
}
