<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Models\PlanModel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Models\Config;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private $id = 1;
    private $misc_toggle_options = [
        'allow_user_login' => [
            'name' => 'Allow user to login',
            'description' => 'This will disable all authentication pages. Even admin users cannot login.',
            'status' => false,
        ],

        'maintenance_mode' => [
            'name' => 'Enable maintenance mode',
            'description' => 'If enabled, users will see the maintenance page after login. But admin can still access the Admin Panel.',
            'status' => false,
        ],

        'paypal_environment' => [
            'name' => 'PayPal environment',
            'description' => 'If enabled, PayPal live will be used. For testing, disable it to use sandbox mode.',
            'status' => false,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = [
            'slug' => 'admin/settings',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/index', $data);
    }

    public function email_smtp(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/smtp',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/email/smtp', $data);
    }

    public function email_template(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/template',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/email/template', $data);
    }


    public function profile_upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'picture' => 'image|dimensions:min_width=100,min_height=200',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Upload image
        $image_path = File::add_get_path($request->file('picture'), 'avatar', Auth::id());
        UserModel::do_update(Auth::id(), [
            'image' => $image_path,
        ]);
    }

    public function profile_update_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'country' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'email' => $request->input('email'),
            'country' => $request->input('country'),
        ];

        $status = UserModel::do_update(Auth::id(), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function profile_update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|confirmed|min:6|max:255',
            // 'confirm_password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'password' => Hash::make($request->input('password')),
        ];

        $status = UserModel::do_update(Auth::id(), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function preference_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:20',
            'time_period' => 'nullable|integer',
            'time_cycle' => 'nullable|integer',
            'time' => 'nullable|date_format:H:i',
            'monthly_report' => 'nullable|integer|digits_between:0,1',
            'monthly_report_time' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $profile_data = [
            'timezone' => $request->input('timezone'),
            'currency' => $request->input('currency'),
            'language' => $request->input('language'),
        ];

        $alert_preference_data = [
            'time_period' => $request->input('time_period'),
            'time_cycle' => $request->input('time_cycle'),
            'time' => $request->input('time'),
            'monthly_report' => $request->input('monthly_report'),
            'monthly_report_time' => $request->input('monthly_report_time'),
        ];

        $status = UserModel::update_profile(Auth::id(), $profile_data);
        $status = UserModel::update_alert_preference(Auth::id(), $alert_preference_data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function payment_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name.*' => 'required|string|max:20',
            'description.*' => 'nullable|string|max:100',
            'expiry.*' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        UserModel::delete_payment(Auth::id());

        if (is_array($request->input('name'))) {
            foreach ($request->input('name') as $key => $val) {
                $data = [
                    'user_id' => Auth::id(),
                    'name' => $request->input('name')[$key],
                    'description' => $request->input('description')[$key] ?? NULL,
                    'expiry' => $request->input('expiry')[$key] ?? NULL,
                ];

                $status = UserModel::create_payment($data);
            }
        }

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function contact_get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        return Response::json(UserModel::contact_get($request->input('id')));
    }

    public function contact_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        $contact_id = UserModel::contact_create($data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function contact_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|max:50',
            'email' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];

        $status = UserModel::contact_update($request->input('id'), $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function contact_delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        return Response::json([
            'status' => UserModel::contact_delete($request->input('id')),
            'message' => 'Success',
        ], 200);
    }



    public function smtp_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'smtp_host' => 'required|string|max:' . len()->config->smtp_host,
            'smtp_port' => 'required|integer|between:0,65535',
            'smtp_username' => 'required|string|max:' . len()->config->smtp_username,
            'smtp_password' => 'required|string|max:' . len()->config->smtp_password,
            'smtp_sender_name' => 'required|string|max:' . len()->config->smtp_sender_name,
            'smtp_sender_email' => 'required|email|max:' . len()->config->smtp_sender_email,
            'smtp_encryption' => 'nullable|string|max:' . len()->config->smtp_encryption,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'smtp_host' => $request->input('smtp_host'),
            'smtp_port' => $request->input('smtp_port'),
            'smtp_username' => $request->input('smtp_username'),
            'smtp_password' => $request->input('smtp_password'),
            'smtp_sender_name' => $request->input('smtp_sender_name'),
            'smtp_sender_email' => $request->input('smtp_sender_email'),
            'smtp_encryption' => $request->input('smtp_encryption'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    // public function smtp_test(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'test_email' => 'required|string|max:255|email:rfc,dns',
    //     ]);

    //     if ($validator->fails()) {
    //         return Response::json([
    //             'status' => false,
    //             'message' => $validator->errors(),
    //         ]);
    //         // abort(419);
    //     }

    //     Mail::to($request->input('test_email'))->send(new Test);

    //     if ($request->ajax()) {
    //         return Response::json([
    //             'status' => true,
    //             'message' => 'Success',
    //         ], 200);
    //     } else {
    //         return back();
    //     }
    // }


    public function script(Request $request)
    {
        $content = new \stdClass();
        $content->header = null;
        $content->footer = null;
        $content->css = null;

        // Get file content from storage
        if (Storage::disk('local')->exists('system/script/header')) {
            $content->header = Storage::disk('local')->get('system/script/header');
        }

        if (Storage::disk('local')->exists('system/script/footer')) {
            $content->footer = Storage::disk('local')->get('system/script/footer');
        }

        if (Storage::disk('local')->exists('system/script/css')) {
            $content->css = Storage::disk('local')->get('system/script/css');
        }

        $data = [
            'slug' => 'admin/settings/script',
            'data' => $content,
        ];

        return view('admin/settings/script', $data);
    }

    public function script_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'header' => 'nullable|string',
            'footer' => 'nullable|string',
            'css' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $header = $request->input('header');
        $footer = $request->input('footer');
        $css = $request->input('css');


        // Save file content in storage
        if ($header == null) {
            Storage::disk('local')->delete('system/script/header');
        } else {
            Storage::disk('local')->put('system/script/header', $header);
        }

        if ($footer == null) {
            Storage::disk('local')->delete('system/script/footer');
        } else {
            Storage::disk('local')->put('system/script/footer', $footer);
        }

        if ($css == null) {
            Storage::disk('local')->delete('system/script/css');
        } else {
            Storage::disk('local')->put('system/script/css', $css);
        }


        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }


    public function webhook(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/webhook',
            'data' => SettingsModel::get($this->id),
            'logs' => SettingsModel::get_webhook_logs(),
        ];
        $data['data']->webhook_url = route('webhook/admin/v1');

        return view('admin/settings/webhook', $data);
    }

    public function get_webhook_log(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = SettingsModel::get_webhook_log($request->input('id'));
        if (!empty($data->request)) {
            $data->request = json_decode($data->request, true);

            if (is_array($data->request)) {

                // Decode all elements in $data->request array
                foreach ($data->request as $key => $value) {

                    if (gettype($value) === 'string') {
                        $value = json_decode($value, true);

                        // Check if json_decode failed
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $data->request[$key] = $value;
                        }
                    }
                }
            }

            $data->request = print_r($data->request, true);
        }

        return $data;
    }

    public function webhook_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'webhook_key' => 'required|string|max:' . len()->config->webhook_key,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'webhook_key' => $request->input('webhook_key'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }


    public function misc(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/misc',
            'data' => SettingsModel::get($this->id),
            'misc_toggle_options' => $this->get_misc_toggle_options(),
        ];

        $data['data']->cron = new \stdClass();
        $data['data']->cron->misc_url = route('cron/specific', ['misc', CRON_TOKEN]);

        return view('admin/settings/misc', $data);
    }

    public function misc_recaptcha_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recaptcha_site_key' => 'required|string|max:' . len()->config->recaptcha_site_key,
            'recaptcha_secret_key' => 'required|string|max:' . len()->config->recaptcha_secret_key,
            'recaptcha_status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'recaptcha_status' => $request->input('recaptcha_status') ? 1 : 0,
            'recaptcha_site_key' => $request->input('recaptcha_site_key'),
            'recaptcha_secret_key' => $request->input('recaptcha_secret_key'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function misc_cdn_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cdn_base_url' => 'nullable|url|max:' . len()->config->cdn_base_url,
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'cdn_base_url' => $request->input('cdn_base_url'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function misc_xeno_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'xeno_public_key' => 'nullable|string|max:' . len()->config->xeno_public_key,
            'xeno_send_data' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'xeno_send_data' => $request->input('xeno_send_data') ? 1 : 0,
            'xeno_public_key' => $request->input('xeno_public_key'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax() && $status) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function misc_cron_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cron_misc_days' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'cron_misc_days' => $request->input('cron_misc_days'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    private function get_misc_toggle_options(): array
    {
        foreach ($this->misc_toggle_options as $key => &$value) {
            switch ($key) {
                case 'allow_user_login':
                    $value['status'] = !Storage::disk('local')->exists('system/toggle/login.txt');
                    break;

                case 'maintenance_mode':
                    $value['status'] = Storage::disk('local')->exists('maintenance.txt');
                    break;

                case 'paypal_environment':
                    $value['status'] = lib()->config->paypal_environment;
                    break;
            }
        }
        return $this->misc_toggle_options;
    }

    public function misc_toggle_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|in:' . implode(',', array_keys($this->misc_toggle_options)),
            'value' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $key = $request->input('key');
        $value = $request->input('value');

        switch ($key) {
            case 'allow_user_login':
                if ($value) {
                    Storage::disk('local')->delete('system/toggle/login.txt');
                } else {
                    Storage::disk('local')->put('system/toggle/login.txt', Carbon::now()->toDateTimeString());
                }
                break;

            case 'maintenance_mode':
                if ($value) {
                    Storage::disk('local')->put('maintenance.txt', Carbon::now()->toDateTimeString());
                } else {
                    Storage::disk('local')->delete('maintenance.txt');
                }
                break;

            case 'paypal_environment':
                Config::where('id', $this->id)->update(['paypal_environment' => $value]);
                break;
        }

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function cron_log_download(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $path = $request->input('path');

        // Check path
        if (!isset(lib()->config->cron_log_path_all[$path]) || !Storage::disk('local')->exists($path)) {
            return Response::json([
                'status' => false,
                'message' => __('File not found'),
            ]);
        }

        return Response::json([
            'filename' => basename($path),
            'content' => Storage::disk('local')->get($path),
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function cron_log_delete(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $path = $request->input('path');

        // Check path
        if (!isset(lib()->config->cron_log_path_all[$path]) || !Storage::disk('local')->exists($path)) {
            return Response::json([
                'status' => false,
                'message' => __('File not found'),
            ]);
        }

        Storage::disk('local')->delete($path);

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }

    public function misc_gravitec_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gravitec_status' => 'nullable|boolean',
            'gravitec_app_key' => 'required|string|max:' . len()->config->gravitec_app_key,
            'gravitec_app_secret' => 'required|string|max:' . len()->config->gravitec_app_secret,
            'gravitec_manifest_json' => 'nullable|file|mimetypes:application/json,text/plain',
            'gravitec_push_worker_js' => 'nullable|file|mimetypes:text/javascript,text/plain',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = [
            'gravitec_status' => $request->input('gravitec_status') ? 1 : 0,
            'gravitec_app_key' => $request->input('gravitec_app_key'),
            'gravitec_app_secret' => $request->input('gravitec_app_secret'),
        ];

        $status = SettingsModel::do_update($this->id, $data);

        // Store files
        if ($request->hasFile('gravitec_manifest_json')) {
            $file = $request->file('gravitec_manifest_json');
            $filename = 'manifest.json';
            $path = str_replace('app/', '', DIR_GRAVITEC_PUSH);
            $file->storeAs($path, $filename, 'local');
        }

        if ($request->hasFile('gravitec_push_worker_js')) {
            $file = $request->file('gravitec_push_worker_js');
            $filename = 'push-worker.js';
            $path = str_replace('app/', '', DIR_GRAVITEC_PUSH);
            $file->storeAs($path, $filename, 'local');
        }

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }
}
