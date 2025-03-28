<?php

namespace App\Library;

use App\Library\NotificationEngine;
use App\Models\Subscription;
use App\Models\SubscriptionModel;
use App\Models\UserModel;
use App\Models\CronFlag;
use App\Models\Event;
use App\Models\EventBrowser;
use App\Models\EventChrome;
use App\Models\SubscriptionHistory;
use App\Models\UserAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class Cron
{
	public static function schedule($subscription_id = null, $skip_history = false)
	{
		if ($subscription_id) {
			$measure_time = false;
		} else {
			$measure_time = true;
		}
		$start_time = microtime(true);
		if (CronFlag::get_flag('schedule')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('schedule', true);
		}
		$count = 0;

		$now = lib()->do->timezone_convert([
			'to_timezone' => APP_TIMEZONE,
		]);
		if (empty($subscription_id)) {
			$subscription_all = SubscriptionModel::where('type', 1)
				->where('recurring', 1)
				->where('status', 1)
				->where(function ($q) use ($now) {
					$q->where('next_payment_date', '<=', $now);
					$q->orWhere(function ($q2) use ($now) {
						$q2->whereNull('next_payment_date');
						$q2->where('payment_date', '<=', $now);
					});
				})
				->get();
		} else {
			$subscription_all = [SubscriptionModel::get($subscription_id)];
		}

		foreach ($subscription_all as $subscription_this) {
			$i = 0;
			if (!$subscription_this) {
				continue;
			}
			$subscription = clone ($subscription_this);
			$subscription_id = $subscription->id;
			unset($expiry_date);

			// Expiry date check
			if (!empty($subscription->expiry_date) && $subscription->status == 1) {

				// Get current datetime in app timezone
				$now = lib()->do->timezone_convert([
					'to_timezone' => APP_TIMEZONE,
				]);

				// Expire date in App timezone
				$expiry_date = lib()->do->timezone_convert([
					'from_timezone' => $subscription->timezone,
					'to_timezone' => APP_TIMEZONE,
					'date_time' => $subscription->expiry_date,
				]);

				// Expire date compare with current datetime
				if ($expiry_date < $now) {
					// Mark as expired
					DB::table('subscriptions')
						->where('id', $subscription->id)
						->update([
							'status' => 4,
						]);

					// Create event logs
					foreach (['email', 'browser', 'extension'] as $this_type) {
						NotificationEngine::staticModel($this_type)::do_create([
							'user_id' => $subscription->user_id,
							'event_type' => "{$this_type}_expire",
							'event_type_status' => 'create',
							'event_type_source' => 1,
							'event_status' => 0,
							'table_name' => 'subscriptions',
							'table_row_id' => $subscription->id,
							'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
							'event_cron' => 1,
							'event_product_id' => $subscription->brand_id,
							'event_type_schedule' => 1,
							'event_type_scdate' => $now,
						]);
					}
					NotificationEngine::add_event_for_subscription_extension_push([
						'table_row_id' => $subscription->id,
						'event_type_status' => 'create',
						'event_product_id' => $subscription->brand_id,
						'event_type_schedule' => $subscription->recurring,
						'event_types' => ['extension', 'browser'],
					]);

					continue;
				}
			}

			// Type is Subscription and Status is Active
			if ($subscription->type == 1) {
				$date_flag = true;
				$j = 0;

				while ($date_flag) {
					$j++;

					// Reset
					unset(
						$subscription_this,
						$subscription,
						$history,
						$next_payment_date_1,
						$start_date,
						$now,
						$alert_timezone,
						$alert_time_period,
						$alert_time_at,
						$user_profile,
						$user_alert,
						$this_next_payment_date,
						$total_days,
						$matching,
						$new_payment_date,
						$date_1,
						$date_2,
						$date_3,
						$date_4,
						$carbon_1,
						$carbon_2,
						$carbon_3,
						$carbon_temp,
						$date_1_day_number,
						$date_1_month_number,
						$date_1_year,
						$date_1_days,
						$date_2_day_number,
						$date_2_month_number,
						$date_3__add_months,
						$date_3_day_number,
						$date_3_month_number,
						$date_3_year,
						$date_3_days,
						$date_1_arr,
						$date_3_arr,
						$date_3__add_years,
						$subscription_new_payment_date,
						$subscription_new_payment_datetime_str,
						$subscription_new_payment_datetime_str_1,
						$user_total_days,
						$new_payment_date,
						$new_payment_datetime_str,
						$new_payment_date_str,
						$old_event_id,
						$event_status,
						$data,
						$new_event_id,
						$subscription_event,
						$new_subscription_history,
						$user_alert_found,
						$subscription_user_alert,
						$old_active_event_id,
					);

					$subscription_this = SubscriptionModel::get($subscription_id);
					$subscription = clone ($subscription_this);
					if (empty($subscription->id)) {
						break;
					}

					// Get current datetime in app timezone
					$now = lib()->do->timezone_convert([
						'to_timezone' => APP_TIMEZONE,
					]);

					if (empty($subscription->next_payment_date)) {

						$history = DB::table('subscriptions_history')
							->where('subscription_id', $subscription->id)
							->orderBy('id', 'desc')
							->get()
							->first();

						if (empty($history->next_payment_date)) {
							$next_payment_date_1 = Carbon::createFromFormat('Y-m-d', $subscription->payment_date, $subscription->timezone);
							$next_payment_date_1->subDay($subscription->billing_frequency);
							$subscription->next_payment_date = $next_payment_date_1->format('Y-m-d');
						} else {
							$subscription->next_payment_date = $history->next_payment_date;
						}
					} else {
						$old_active_event_id = NotificationEngine::staticModel('email')::where([
							['table_name', 'subscriptions'],
							['table_row_id', $subscription->id],
							['event_type', 'email'],
							['event_cron', 1],
							['event_status', 0],
						])->value('id');

						// Check for completed events
						if ($old_active_event_id && !empty($subscription->next_payment_date) && $subscription->next_payment_date >= $now) {
							break;
						}
					}

					if (!empty($subscription->next_payment_date)) {
						$start_date = $subscription->next_payment_date;
					}

					if (empty($start_date)) {
						$start_date = $now;
					} else {
						$start_date = lib()->do->timezone_convert([
							'to_timezone' => APP_TIMEZONE,
							'date_time' => $start_date,
						]);
					}

					// Check subscription timezone
					if (empty($subscription->timezone)) {
						$subscription->timezone = APP_TIMEZONE;
					}

					// Set default data
					$alert_timezone = APP_TIMEZONE;
					$alert_time_period = 0;
					$alert_time_at = '09:00:00';

					$user_profile = DB::table('users_profile')
						->where('user_id', $subscription->user_id)
						->get()
						->first();

					$user_alert_found = false;

					if (!empty($subscription->alert_id)) {

						$subscription_user_alert = UserAlert::whereId($subscription->alert_id)
							->whereFindInSetOr('alert_types', ['email', 'browser']) 
							->first();

						if (!empty($subscription_user_alert->id)) {
							$user_alert_found = true;
							$user_alert = $subscription_user_alert;
							$user_profile->timezone = $user_alert->timezone;
						}
					}

					if (!$user_alert_found) {
						$user_alert = DB::table('users_alert_preferences')
							->where('user_id', $subscription->user_id)
							->get()
							->first();
					}

					// Check for empty value and get user timezone
					if (!empty($user_profile) && !empty($user_profile->timezone)) {
						$alert_timezone = $user_profile->timezone;
					}

					// Check for empty value and get user time period for email alerts
					if (!empty($user_alert) && !empty($user_alert->time_period)) {
						$alert_time_period = $user_alert->time_period;
					}

					// Check for empty value and get user time period for email alerts
					if (!empty($user_alert) && !empty($user_alert->time)) {
						$alert_time_at = $user_alert->time;
					}

					// Check if upcoming date is empty then set default
					if (empty($subscription->next_payment_date)) {
						$subscription->next_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->payment_date, $subscription->timezone)
							->format('Y-m-d H:i:s');
					}

					// Check if already scheduled then skip
					else {
						$this_next_payment_date = lib()->do->timezone_convert([
							'from_timezone' => $subscription->timezone,
							'to_timezone' => APP_TIMEZONE,
							'date_time' => $subscription->next_payment_date,
						]);

						if (!empty($subscription->next_payment_date)) {
							if ($subscription->next_payment_date >= $now) {
								$date_flag = false;
								continue;
							}
						}
					}

					// Supported cycle days
					$cycle_days = [
						1 => 1,
						2 => 7,
						3 => 30,
						4 => 365,
					];

					// Check if cycle days match
					if (isset($cycle_days[$subscription->billing_cycle])) {
						$total_days = $cycle_days[$subscription->billing_cycle] * $subscription->billing_frequency;

						$matching = true;

						// Refund date check
						// Before Refund Date
						if ($user_alert_found && $subscription_user_alert->alert_condition == 3 && !empty($subscription->refund_date && $subscription->refund_date >= $now)) {
							$new_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->refund_date, $subscription->timezone);
							$new_payment_date->setTimezone($alert_timezone);
							$new_payment_date->setTimeFromTimeString($alert_time_at);
						}

						if (empty($new_payment_date)) {
							$new_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->next_payment_date, $subscription->timezone);
							$new_payment_date->setTimezone($alert_timezone);
							$new_payment_date->setTimeFromTimeString($alert_time_at);
						}

						// Search for matching date
						while ($matching) {
							$i++;

							if ($subscription->recurring) {

								// Calculate by date
								if (
									$subscription->billing_type == 2 &&

									// Month or Year
									($subscription->billing_cycle == 3 || $subscription->billing_cycle == 4)
								) {

									$date_1 = $subscription_this->payment_date;

									if (empty($subscription_this->next_payment_date)) {
										$date_2 = $subscription->payment_date;
									} else {
										$date_2 = $subscription->next_payment_date;
									}

									$carbon_1 = Carbon::createFromFormat('Y-m-d', $date_1, $subscription->timezone);
									$carbon_2 = Carbon::createFromFormat('Y-m-d', $date_2, $subscription->timezone);

									$date_1_day_number = date('d', strtotime($date_1));
									$date_1_month_number = date('m', strtotime($date_1));
									$date_1_year = date('Y', strtotime($date_1));
									$date_1_days = cal_days_in_month(CAL_GREGORIAN, $date_1_month_number, $date_1_year);

									$date_2_day_number = date('d', strtotime($date_2));
									$date_2_month_number = date('m', strtotime($date_2));

									// Month
									if ($subscription->billing_cycle == 3) {

										$carbon_temp = clone ($carbon_2);
										$carbon_temp->endOfMonth();

										if ($date_1_day_number > 27) {
											$carbon_temp->addDays(4);
										}

										$date_3__add_months = $carbon_temp->diffInMonths($carbon_1);
										$date_3__add_months += $subscription->billing_frequency;

										$carbon_3 = clone ($carbon_1);
										$carbon_3->addMonthsNoOverflow($date_3__add_months);
										$date_3 = $carbon_3->format('Y-m-d');

										$date_3_day_number = date('d', strtotime($date_3));
										$date_3_month_number = date('m', strtotime($date_3));
										$date_3_year = date('Y', strtotime($date_3));
										$date_3_days = cal_days_in_month(CAL_GREGORIAN, $date_3_month_number, $date_3_year);

										if ($date_1_day_number > 27) {

											if ($date_1_day_number > $date_3_day_number) {
												if ($date_1_days > $date_3_days) {
													$date_4 = $carbon_3->endOfMonth()->format('Y-m-d');
												} else {
													$date_1_arr = explode('-', $date_1);
													$date_3_arr = explode('-', $date_3);

													if (count($date_1_arr) == 3 && count($date_3_arr) == 3) {
														$date_4 = $date_3_arr[0] . '-' . $date_3_arr[1] . '-' . $date_1_arr[2];
													}
												}
											}
										} else {
											$date_4 = $date_3;
										}
									}

									// Year
									else if ($subscription->billing_cycle == 4) {
										$carbon_temp = clone ($carbon_2);
										$carbon_temp->endOfMonth();

										if ($date_1_day_number > 27) {
											$carbon_temp->addDays(4);
										}

										$date_3__add_years = $carbon_temp->diffInYears($carbon_1);
										$date_3__add_years += $subscription->billing_frequency;

										$carbon_3 = clone ($carbon_1);
										$carbon_3->addYearNoOverflow($date_3__add_years);
										$date_3 = $carbon_3->format('Y-m-d');
									}

									if (empty($date_4)) {
										$date_4 = $date_3;
									}

									$subscription_new_payment_date = Carbon::createFromFormat('Y-m-d', $date_4, $subscription->timezone);
									$subscription_new_payment_datetime_str = $subscription_new_payment_date->format('Y-m-d H:i:s');

									// Calculate time period for email alerts from user settings
									$user_total_days = $total_days - $alert_time_period;
									if ($user_total_days > 0) {
										$total_days = $user_total_days;
									}

									$new_payment_date = Carbon::createFromFormat('Y-m-d', $date_4, $subscription->timezone);
									$new_payment_date->setTimezone($alert_timezone);
									$new_payment_date->setTimeFromTimeString($alert_time_at);

									// Refund date check
									// Before Refund Date
									if ($user_alert_found && $subscription_user_alert->alert_condition == 3 && !empty($subscription->refund_date && $subscription->refund_date >= $now)) {
										$new_payment_date = $new_payment_date->subDays($alert_time_period);
									} else {
										$new_payment_date = $new_payment_date->subDays($alert_time_period);
									}

									$new_payment_date->setTimezone(APP_TIMEZONE);
									$new_payment_datetime_str = $new_payment_date->format('Y-m-d H:i:s');
									$new_payment_date_str = $new_payment_date->format('Y-m-d');
								} else {

									// Calculate by days

									$subscription_new_payment_date = Carbon::createFromFormat('Y-m-d', $subscription->next_payment_date, $subscription->timezone);
									$subscription_new_payment_date->addDays($total_days);
									$subscription_new_payment_datetime_str = $subscription_new_payment_date->format('Y-m-d H:i:s');

									// Calculate time period for email alerts from user settings
									$user_total_days = $total_days - $alert_time_period;
									if ($user_total_days > 0) {
										$total_days = $user_total_days;
									}

									// Refund date check
									// Before Refund Date
									if ($user_alert_found && $subscription_user_alert->alert_condition == 3 && !empty($subscription->refund_date && $subscription->refund_date >= $now)) {
										$new_payment_date = $new_payment_date->subDays($alert_time_period);
									} else {
										$new_payment_date = $new_payment_date->addDays($total_days);
									}

									$new_payment_date->setTimezone(APP_TIMEZONE);
									$new_payment_datetime_str = $new_payment_date->format('Y-m-d H:i:s');
									$new_payment_date_str = $new_payment_date->format('Y-m-d');
								}
							}

							// Non recurring
							else {
								$new_payment_datetime_str = $subscription->payment_date;
								$i = 101;

								DB::table('subscriptions')
									->where('id', $subscription->id)
									->update([
										'next_payment_date' => $new_payment_datetime_str,
										'payment_date_upd' => $new_payment_datetime_str,
									]);
							}

							if ($new_payment_datetime_str > $start_date || $i > 100) {
								$matching = false;

								if (($subscription->next_payment_date < $new_payment_datetime_str || !$subscription->recurring) && (!isset($expiry_date) || $subscription_new_payment_datetime_str < $expiry_date)) {
									$subscription->new_payment_date = $new_payment_datetime_str;

									$old_event_id = NotificationEngine::staticModel('email')::where([
										['table_name', 'subscriptions'],
										['table_row_id', $subscription->id],
										['event_type', 'email'],
									])->value('id');

									$event_status = 0;
									if ($now > $new_payment_datetime_str) {
										$event_status = 1;
									}

									// Before Due Date
									if ($user_alert_found && ($subscription_user_alert->alert_condition == 1 || $subscription_user_alert->alert_condition == 2)) {

										if (!$old_event_id) {

											$data = [
												'user_id' => $subscription->user_id,
												'event_type' => 'email',
												'event_type_status' => 'create',
												'event_status' => $event_status,
												'table_name' => 'subscriptions',
												'table_row_id' => $subscription->id,
												'event_type_function' => __CLASS__ . '::' . __FUNCTION__ . '()',
												// 'event_cron' => 1,
												'event_product_id' => $subscription->brand_id,
												'event_type_schedule' => $subscription->recurring ? 2 : 1,

												// Already converted to app timezone
												'event_type_scdate' => $subscription->new_payment_date,
											];

											// Check active status
											if ($subscription->status == 1) {
												$data['event_cron'] = 1;
											}

											// Create event logs
											$new_event_id = NotificationEngine::staticModel('email')::do_create($data)->id;
											$data_browser = $data;
											$data_browser['event_type'] = 'browser';
											EventBrowser::do_create($data_browser);
											$data_extension = $data;
											$data_extension['event_type'] = 'extension';
											EventChrome::do_create($data_extension);
										} else if ($subscription->status == 1) {

											// Update for recurring event only
											if ($subscription->recurring == 1) {

												$data = [
													'event_status' => $event_status,
													'event_url' => url()->current(),
													'event_type_schedule' => $subscription->recurring ? 2 : 1,
													'event_type_scdate' => $subscription->new_payment_date,
													'event_timezone' => APP_TIMEZONE,
													'event_datetime' => lib()->do->timezone_convert([
														'to_timezone' => APP_TIMEZONE,
													]),
												];

												// Check active status
												if ($subscription->status == 1) {
													$data['event_cron'] = 1;
												}

												// Update event logs
												NotificationEngine::staticModel('email')::do_update($old_event_id, $data);
												$old_browser_event_id = EventBrowser::where([
													['table_name', 'subscriptions'],
													['table_row_id', $subscription->id],
													['event_type', 'browser'],
												])->value('id');
												EventBrowser::do_update($old_browser_event_id, $data);
												$old_chrome_event_id = EventChrome::where([
													['table_name', 'subscriptions'],
													['table_row_id', $subscription->id],
													['event_type', 'extension'],
												])->value('id');
												EventChrome::do_update($old_chrome_event_id, $data);

												$new_event_id = $old_event_id;
											}
										}
									}

									// Update subscription when event updated
									if ((!empty($new_event_id) || $user_alert_found) && $subscription->recurring) {

										DB::table('subscriptions')
											->where('id', $subscription->id)
											->update([
												'next_payment_date' => $subscription_new_payment_datetime_str,
												'payment_date_upd' => $subscription_new_payment_datetime_str,
											]);

										// Find subscription event
										$subscription_event = NotificationEngine::staticModel('event')::where('event_type', 'subscription')
											->where('table_name', 'subscriptions')
											->where('table_row_id', $subscription->id)
											->first();

										if (!empty($subscription_event)) {
											NotificationEngine::staticModel('event')::do_update($subscription_event->id, [
												'event_type_scdate' => $subscription->new_payment_date,
											]);
										}

										// Add subscription history
										if ($subscription->recurring && $subscription->status == 1 && !$skip_history) {
											$new_subscription_history = SubscriptionModel::get($subscription->id);
											if (!empty($new_subscription_history)) {
												SubscriptionHistory::create_history($new_subscription_history);
											}
										}
									}
								}
							}

							if ($new_payment_datetime_str > $now || $j > 1000) {
								$date_flag = false;
							}
						}
					}
				}
			}

			// Set past date
			$past_next_payment_date = new \DateTime($subscription->next_payment_date);
			if (empty($subscription->payment_date_upd)) {
				$past_payment_date_upd_str = lib()->do->timezone_convert([
					'from_timezone' => LARAVEL_TIMEZONE,
					'to_timezone' => $subscription->timezone,
				]);
				$past_payment_date_upd_obj = new \DateTime($past_payment_date_upd_str);
			} else {
				$past_payment_date_upd_str = $subscription->payment_date_upd;
				$past_payment_date_upd_obj = new \DateTime($subscription->payment_date_upd);
			}

			if ($past_payment_date_upd_obj > $past_next_payment_date) {
				DB::table('subscriptions')
					->where('id', $subscription->id)
					->update([
						'payment_date_upd' => $past_payment_date_upd_str,
					]);
			}

			$count++;
		}

		// Delete records
		$subscription_event_delete_all = NotificationEngine::staticModel('event')::select('id', 'table_row_id')
			->where([
				['event_type', 'subscription'],
				['event_type_status', 'delete'],
				['table_name', 'subscriptions']
			])->get();

		foreach ($subscription_event_delete_all as $event) {

			NotificationEngine::staticModel('event')::find($event->id)->delete();
			DB::table('subscriptions_history')
				->where('subscription_id', $event->table_row_id)
				->delete();
		}

		$end_time = microtime(true);
		$execution_time = ($end_time - $start_time);
		if ($measure_time) {
			echo " Execution time of script = " . $execution_time . " sec\n";
		}
		CronFlag::set_flag('schedule', false);
		return $count;
	}

	public static function send_mail()
	{
		if (CronFlag::get_flag('mail')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('mail', true);
		}
		$count = NotificationEngine::staticModel('email')::send_messages();

		CronFlag::set_flag('mail', false);
		return $count;
	}

	public static function plan_calculate()
	{
		if (CronFlag::get_flag('plan')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('plan', true);
		}
		$count = 0;
		$user_all = UserModel::get_all();

		foreach ($user_all as $user) {

			// Count total data
			$subscription_count = DB::table('subscriptions')
				->where('subscriptions.user_id', $user->id)
				->count();

			$folder_count = DB::table('folder')
				->where('folder.user_id', $user->id)
				->count();

			$tag_count = DB::table('tags')
				->where('tags.user_id', $user->id)
				->count();

			$contact_count = DB::table('users_contacts')
				->where('users_contacts.user_id', $user->id)
				->count();

			$payment_method_count = DB::table('users_payment_methods')
				->where('users_payment_methods.user_id', $user->id)
				->count();

			$total_alert_profiles_count = DB::table('users_alert')
				->where('users_alert.user_id', $user->id)
				->count();

			$total_webhooks_count = DB::table('webhooks')
				->where('webhooks.user_id', $user->id)
				->count();

			$total_teams_count = DB::table('users_teams')
				->where('users_teams.team_user_id', $user->id)
				->count();

			// Calculate total storage used in the file system
			$total_storage_count = 0;
			$folder_path = 'storage/app/client/1/user/' . Auth::id();
			if (File::exists($folder_path) && File::isDirectory($folder_path)) {
				foreach (File::allFiles($folder_path) as $file) {
					$total_storage_count += $file->getSize();
				}
			}


			// Search for user plan
			$user_plan = DB::table('users_plans')
				->where('users_plans.user_id', $user->id)
				->select('users_plans.*')
				->get()
				->first();

			// Check if user plan exists
			if (empty($user_plan)) {
				$default_plan = DB::table('plans')
					->where('plans.is_default', 1)
					->select('plans.*')
					->get()
					->first();

				// Insert default plan
				if (!empty($default_plan)) {
					DB::table('users_plans')->insert([
						'user_id' => $user->id,
						'plan_id' => $default_plan->id,
						'total_subs' => $subscription_count,
						'total_folders' => $folder_count,
						'total_tags' => $tag_count,
						'total_contacts' => $contact_count,
						'total_pmethods' => $payment_method_count,
						'total_alert_profiles' => $total_alert_profiles_count,
						'total_webhooks' => $total_webhooks_count,
						'total_teams' => $total_teams_count,
						'total_storage' => $total_storage_count,
					]);
				}
			}

			// Update user plan limit
			else {
				DB::table('users_plans')
					->where('id', $user_plan->id)
					->update([
						'total_subs' => $subscription_count,
						'total_folders' => $folder_count,
						'total_tags' => $tag_count,
						'total_contacts' => $contact_count,
						'total_pmethods' => $payment_method_count,
						'total_alert_profiles' => $total_alert_profiles_count,
						'total_webhooks' => $total_webhooks_count,
						'total_teams' => $total_teams_count,
						'total_storage' => $total_storage_count,
					]);
			}


			$count++;
		}
		CronFlag::set_flag('plan', false);
		return $count;
	}

	public static function report()
	{
		if (CronFlag::get_flag('report')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('report', true);
		}
		$count = 0;
		$sub_history_all = DB::table('subscriptions_history')
			// ->leftJoin('users_profile', 'subscriptions_history.user_id', '=', 'users_profile.user_id')
			// ->select('subscriptions_history.*', 'users_profile.currency as user_currency')
			->get();

		CronFlag::set_flag('report', false);
		return $count;
	}

	public static function misc()
	{
		if (CronFlag::get_flag('misc')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('misc', true);
		}
		$count = 0;
		$days_before = 0;

		// Get configuration
		if (!empty(lib()->config->cron_misc_days)) {
			$days_before = lib()->config->cron_misc_days;
		}


		// Delete events after account reset
		$all_user = DB::table('users')
			->where('role_id', 2)
			->whereNotNull('reset_at')
			->get();

		foreach ($all_user as $user) {
			$count += NotificationEngine::staticModel('event')::where([
				['event_type', 'subscription'],
				['event_type_status', 'delete'],
				['user_id', $user->id],
				['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
			])->delete();
			$count += NotificationEngine::staticModel('email')::where([
				['event_type', 'email'],
				['event_type_status', 'delete'],
				['user_id', $user->id],
				['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
			])->delete();
			EventBrowser::where([
				['user_id', $user->id],
				['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
				['event_type_status', 'delete'],
			])->delete();
			EventChrome::where([
				['user_id', $user->id],
				['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
				['event_type_status', 'delete'],
			])->delete();

			DB::table('users')
				->where('id', $user->id)
				->update([
					'reset_at' => null,
				]);
		}



		// Delete webhook logs
		DB::table('webhook_logs')
			->where('created_at', '<', date('Y-m-d', strtotime("-$days_before day")))
			->delete();


		// Delete email logs
		DB::table('email_logs')
			->where('created_at', '<', date('Y-m-d', strtotime("-$days_before day")))
			->delete();


		// Delete subscription events
		NotificationEngine::staticModel('email')::where([
			['event_type', 'email'],
			['event_type_status', 'create'],
			['event_type_schedule', 0],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();
		EventBrowser::where([
			['event_type_status', 'create'],
			['event_type_schedule', 0],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();
		EventChrome::where([
			['event_type_status', 'create'],
			['event_type_schedule', 0],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();



		// Delete email delete events
		NotificationEngine::staticModel('email')::where([
			['event_type', 'email'],
			['event_type_status', 'delete'],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();
		EventBrowser::where([
			['event_type_status', 'delete'],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();
		EventChrome::where([
			['event_type_status', 'delete'],
			['event_datetime', '<', date('Y-m-d', strtotime("-$days_before day"))],
		])->delete();



		// Get session lifetime from Laravel config
		$session_lifetime = config('session.lifetime', 120);

		// Delete old session files
		$sessionDirectory = storage_path('framework/sessions');
		$handle = opendir($sessionDirectory);
		while (false !== ($filename = readdir($handle))) {
			if ($filename != "." && $filename != "..") {
				if (filemtime("$sessionDirectory/$filename") < strtotime("-$session_lifetime minutes")) {
					unlink("$sessionDirectory/$filename");
				}
			}
		}
		closedir($handle);

		// Update product_avail for existing subscriptions
		$subscription_all = Subscription::join('products', 'products.id', '=', 'subscriptions.product_submission_id')
			->where('subscriptions.brand_id', '<=', PRODUCT_RESERVED_ID)
			->where('subscriptions.status', 1)
			->where('subscriptions.product_avail', 0)
			->where('subscriptions.product_submission_id', '!=', 0)
			->whereNotNull('subscriptions.product_submission_id')
			->where('products.status', 1)
			->select('subscriptions.*')
			->get();

		foreach ($subscription_all as $subscription) {
			$subscription->product_avail = 1;
			$subscription->save();
		}


		CronFlag::set_flag('misc', false);
		return $count;
	}

	public static function notification()
	{
		if (CronFlag::get_flag('notification')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('notification', true);
		}
		// Check if push notification is enabled
		if (!lib()->config->gravitec_status) {
			CronFlag::set_flag('notification', false);
			return 0;
		}

		$count = NotificationEngine::staticModel('browser')::send_messages();

		CronFlag::set_flag('notification', false);
		return $count;
	}

	public static function fix_subscription_history($user_id)
	{
		if (CronFlag::get_flag('fix_subscription_history')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('fix_subscription_history', true);
		}
		$subscription_ids = SubscriptionHistory::find_subscription_ids_for_null_next_payment_date($user_id);
		$total = count($subscription_ids);
		$subarray = array_slice($subscription_ids, 0, 50);
		$count = count($subarray);
		echo "Process first $count of $total subscriptions\n";
		foreach ($subarray as $subscription_id) {
			echo "Start process subscription id $subscription_id\n";
			SubscriptionHistory::where('subscription_id', $subscription_id)->delete();
			$subscription = Subscription::find($subscription_id);
			if (!$subscription) {
				echo "Subscription id $subscription_id not found\n";
				continue;
			}
			SubscriptionModel::create_new_history($subscription_id);
			Cron::schedule($subscription_id);
		}
		CronFlag::set_flag('fix_subscription_history', false);
		return $count;
	}

	public static function send_messages()
	{
		if (CronFlag::get_flag('messages')) {
			echo "Cron task is already running\n";
			return 0;
		} else {
			CronFlag::set_flag('messages', true);
		}
		$count = NotificationEngine::send_messages();

		CronFlag::set_flag('messages', false);
		return $count;
	}

	public static function migrate_notifications()
	{
		$count = 0;
		$email_events = Event::whereIn('event_type', ['email', 'email_refund', 'email_expire'])->where('table_name', 'subscriptions')->get();
		foreach ($email_events as $event) {
			if (Subscription::whereId($event->table_row_id)->exists()) {
				$event_array = $event->toArray();
				unset($event_array['id']);
				NotificationEngine::staticModel('email')::do_create($event_array);
				$count++;
			}
			$event->delete();
		}
		$notification_events = Event::where('event_type', 'notifications')->where('table_name', 'subscriptions')->get();
		foreach ($notification_events as $event) {
			if (Subscription::whereId($event->table_row_id)->exists()) {
				$event_array = $event->toArray();
				unset($event_array['id']);
				foreach (['browser', 'extension'] as $type) {
					NotificationEngine::staticModel($type)::do_create($event_array);
					$count++;
				}
			}
			$event->delete();
		}
		return $count;
	}
}
