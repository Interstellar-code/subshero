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
use App\Library\Email;
use App\Library\NotificationEngine;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Test;
use App\Models\EmailLog;
use App\Models\EmailModel;
use App\Models\EmailTemplate;
use DateTimeZone;
use Yajra\DataTables\DataTables;

class EmailController extends Controller
{
    private $id = 1;

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

    public function smtp(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/smtp',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/email/smtp', $data);
    }

    public function template(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/template',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/email/template/index', $data);
    }

    public function template_create_show(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/template/create',
            'data' => SettingsModel::get($this->id),
        ];
        return view('admin/settings/email/template/create', $data);
    }

    public function template_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:' . len()->email_templates->type,
            'subject' => 'required|string|max:' . len()->email_templates->subject,
            'body' => 'nullable|string|max:' . len()->email_templates->body,
            'is_default' => 'nullable|integer|digits_between:0,1',
            'status' => 'nullable|integer|digits_between:0,1',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $is_default = $request->input('is_default') ? 1 : 0;
        $status = $request->input('status') ? 1 : 0;
        $type = $request->input('type');

        if ($is_default) {
            EmailTemplate::clear_default($type);
        }

        $data = [
            'type' => $request->input('type'),
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            // 'is_default' => $request->input('is_default'),
            'is_default' => $is_default,
            'status' => $status,
            'user_id' => Auth::id(),
        ];

        $data_id = EmailTemplate::create($data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function template_update_show(Request $request)
    {
        $data = [
            'slug' => 'admin/settings/email/template/update',
            'data' => EmailTemplate::get($request->id),
        ];

        if (empty($data['data'])) {
            return back();
        }

        return view('admin/settings/email/template/update', $data);
    }

    public function template_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:' . len()->email_templates->type,
            'subject' => 'required|string|max:' . len()->email_templates->subject,
            'body' => 'nullable|string|max:' . len()->email_templates->body,
            'is_default' => 'nullable|integer|digits_between:0,1',
            'status' => 'nullable|integer|digits_between:0,1',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $is_default = $request->input('is_default') ? 1 : 0;
        $status = $request->input('status') ? 1 : 0;
        $type = $request->input('type');

        if ($is_default) {
            EmailTemplate::clear_default($type);
        }

        $data = [
            'type' => $request->input('type'),
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
            // 'is_default' => $request->input('is_default'),
            'is_default' => $is_default,
            'status' => $status,
            'user_id' => Auth::id(),
        ];

        $status = EmailTemplate::do_update($request->id, $data);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    public function template_delete(Request $request)
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
            'status' => EmailTemplate::del($request->input('id')),
            'message' => 'Success',
        ], 200);
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

    public function smtp_test(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|string|max:255|email:rfc,dns',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Send email and log this
        NotificationEngine::staticModel('email')::send_message([
            'user' => (object) [
                'id' => Auth::id(),
                'email' => $request->input('test_email'),
                'name' => Auth::user()->name,
            ],
            'template' => [
                'subject' => __('Test'),
                'body' => \View::make('mail/test')->render(),
            ],
            'type' => 'test',
        ]);

        if ($request->ajax()) {
            return Response::json([
                'status' => true,
                'message' => 'Success',
            ], 200);
        } else {
            return back();
        }
    }

    // public function email_logs_user(Request $request)
    // {
    //     $logs = EmailModel::get_logs();
    //     return Datatables::of($logs)->make();
    // }

    public function email_logs_user(Request $request, $search = null)
    {
        $column_map = [
            'column_status' => 'status',
        ];

        // TimeZone
        $timezone = $request->input('timezone');
        // if (!empty($timezone) && !in_array($timezone, timezone_identifiers_list())) {
        //     $timezone = '';
        // }



        ## Read value
        $draw = $request->get('draw');
        $start = $request->get('start');
        $row_per_page = $request->get('length');

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        // Get column name
        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];

        // Ascending (asc) or descending order (desc)
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // Remove unicode characters
        $searchValue = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/', '', $searchValue);
        $searchValue = preg_replace('/[\x00-\x1F\x7F]/u', '', $searchValue);

        // Find product type
        $data['searchValue'] = $searchValue;
        $data['status'] = $this->get_email_log_status($searchValue);
        $data['created_at'] = $this->get_email_log_date($searchValue);


        // Total records
        $totalRecords = EmailLog::select('count(*) as allcount')
            ->count();

        $totalRecordswithFilter = $this->datatable_query($data)
            ->select('count(email_logs.*) as allcount')
            ->count();

        // Map custom column name
        if (isset($column_map[$columnName])) {
            $columnName = $column_map[$columnName];
        }


        // Fetch records
        $records = $this->datatable_query($data)
            ->select(
                'email_logs.*',
            )
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($row_per_page)
            ->get();

        // $totalRecordswithFilter = $records->count();

        $data_arr = array();

        foreach ($records as $val) {

            // Check timezone
            if (empty($timezone)) {
                $created_at = $val->created_at;
            } else {
                $created_at = Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at, $val->created_timezone)
                    ->setTimezone($timezone)
                    ->format('Y-m-d H:i:s');
            }

            $data_arr[] = array(
                'id' => $val->id,
                'template_name' => $val->template_name,
                'from_name' => $val->from_name,
                'from_email' => $val->from_email,
                'to_name' => $val->to_name,
                'to_email' => $val->to_email,
                'subject' => $val->subject,
                'body' => $val->body,
                // 'created_at' => (empty($timezone) ? $val->created_at : $val->created_at->setTimezone($timezone)),
                'created_at' => $this->time_elapsed_string($created_at, $timezone),
                'column_status' => view('admin/datatable/settings/email/column_status', compact('val'))->render(),
                'column_action' => view('admin/datatable/settings/email/column_action', compact('val'))->render(),
            );
        }

        $response = array(
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr
        );

        return response()->json($response);
    }

    private function datatable_query(array $data)
    {
        return EmailLog::leftJoin('users', 'email_logs.created_by', '=', 'users.id')

            ->where(function ($query) use ($data) {
                $query->where('email_logs.template_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere($data['status'] !== null ? ['email_logs.status' => $data['status']] : null)
                    ->orWhere('email_logs.from_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.from_email', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.to_name', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.to_email', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.subject', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.body', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere('email_logs.sent_timezone', 'like', '%' . $data['searchValue'] . '%')
                    ->orWhere($data['created_at'] !== null ? ['email_logs.created_at' => $data['created_at']] : null);
            });
    }

    function time_elapsed_string($datetime, string $timezone = null, $full = false)
    {
        // Get by timezone
        if (empty($timezone)) {
            $now = new \DateTime;
            $ago = new \DateTime($datetime);
        } else {
            $now = new \DateTime('now', new \DateTimeZone($timezone));
            $ago = new \DateTime($datetime, new \DateTimeZone($timezone));
        }

        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    private function get_email_log_status(string $search_term)
    {
        // Predefined search terms
        $subscription_type_terms = [

            // Pending
            0 => [
                'pending',
            ],

            // Sent
            1 => [
                'sent',
            ],

            // Failed
            2 => [
                'failed',
            ],
        ];

        $search_term = strtolower($search_term);
        $all_search_term = explode(' ', $search_term);

        if (count($all_search_term) > 2) {
            $all_search_term = array_slice($all_search_term, 2);
        }

        $subscription_type = null;

        // Search the needle
        foreach ($all_search_term as $val) {
            foreach ($subscription_type_terms as $type_id => $all_terms) {
                foreach ($all_terms as $term) {
                    if (strpos($term, $val) !== false) {
                        $subscription_type = $type_id;
                        break 3;
                    }
                }
            }
        }

        return $subscription_type;
    }

    private function get_email_log_date(string $search_term)
    {
        $datetime_int = strtotime($search_term);

        if (empty($datetime_int)) {
            return null;
        } else {
            $date = date('Y-m-d', $datetime_int);
        }
    }

    public function email_logs_delete_all(Request $request)
    {
        EmailModel::delete_all_logs();
        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }
}
