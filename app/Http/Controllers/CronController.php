<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Library\Cron;
use App\Library\Email;
use App\Mail\DefaultMail;
use App\Models\FolderModel;
use App\Models\TemplateModel;
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CronController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, $token)
    {
        // if ($token !== config('CRON_TOKEN')) {
        if ($token == 'm3Fn5vGjr4') {
            // return $this->execute();

            $mail = 0;
            $schedule = 0;
            $plan = 0;


            $mail = Cron::send_mail();
            $schedule = Cron::schedule();
            $plan = Cron::plan_calculate();
            $notification = Cron::notification();

            $count = $schedule + $mail + $plan;

            return response()->json([
                'status' => true,
                'message' => "Total $count records processed",
                'schedule' => $schedule,
                'mail' => $mail,
                'plan' => $plan,
                'notification' => $notification,
            ]);
        }

        // Unauthorized
        abort(401);
    }

    public function specific(Request $request, $type, $token)
    {
        if ($token == CRON_TOKEN) {

            switch ($type) {
                case 'schedule':
                    $count = Cron::schedule();
                    break;

                case 'mail':
                    $count = Cron::send_mail();
                    break;

                case 'plan':
                    $count = Cron::plan_calculate();
                    break;

                case 'report':
                    $count = Cron::report();
                    break;

                case 'misc':
                    $count = Cron::misc();
                    break;

                case 'notification':
                    $count = Cron::notification();
                    break;

                default:
                    abort(404);
            }

            // return response()->json([
            //     'status' => true,
            //     'message' => "Total $count records processed",
            // ]);


            // Save log file in storage
            Storage::disk('local')->append("system/cron/$type.log", '[' . (string)lib()->do->timezone_convert([
                'from_timezone' => LARAVEL_TIMEZONE,
                'to_timezone' => APP_TIMEZONE,
                'return_format' => DATE_ATOM,
            ]) . ']: ' . json_encode([
                'status' => true,
                'message' => "Total $count records processed",
            ]));

            return null;
        }

        abort(401);
    }
}
