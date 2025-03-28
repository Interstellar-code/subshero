<?php

namespace App\Http\Controllers\Client\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\Application as lib;
use App\Library\MailSender;
use Illuminate\Support\Carbon;
use App\Models\FolderModel;
use App\Models\PlanModel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\PaymentMethodModel;
use App\Models\TeamModel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');

        // Check if user subscribed to Team plan
        if (Auth::user() && !in_array(Auth::user()->users_plan->plan_id, TEAM_PLAN_ALL_ID)) {
            redirect()->route('/')->send();
        }
    }

    public function index()
    {
        $data = [
            'slug' => 'team',
        ];
        return view('client/settings/team/index', $data);
    }

    public function get(Request $request)
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

        return Response::json(UserModel::get($request->input('id')));
    }

    public function link(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|string|exists:users,email',
            'email' => [
                'required',
                'string',
                'max:' . len()->users->email,
                // Rule::exists('users', 'email')
                //     ->where(function ($query) {
                //         $query->where('users.role_id', CLIENT_ROLE_ID);
                //         $query->where('users.plan_id', '==', FREE_PLAN_ID);
                //         return $query->where('users.id', '!=', Auth::id());
                //     }),
                // Rule::unique('users', 'email')
                //     ->where(function ($query) {
                //         $query->join('users_teams', 'users_teams.pro_user_id', '=', 'users.id');
                //         return $query->where('users_teams.pro_user_id', '!=', Auth::id());
                //     }),
            ],
            'name' => [
                'nullable',
                'string',
                'max:' . len()->users->name,

                // Required only for new users
                Rule::requiredIf(function () use ($request) {
                    $user = User::where('email', $request->input('email'))->first();
                    return empty($user->id);
                }),
            ],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('team')) {
            return lib()->do->get_limit_msg($request, 'team');
        }

        if (TeamModel::is_using($request->input('email'))) {
            return Response::json([
                'status' => false,
                'message' => __('This email is already in use.'),
            ]);
            // abort(419);
        }


        // Validate user manually
        $user = User::where('email', $request->input('email'))->first();
        if (empty($user->id)) {

            // Get first name and last name from full name
            $name = explode(' ', $request->input('name'));
            $first_name = $name[0];
            $last_name = $name[1] ?? '';

            // Create new user
            $user = new User();
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->password = '';
            $user->role_id = CLIENT_ROLE_ID;
            $user->status = 0;
            $user->save();
            UserModel::create_profile_default($user->id, 'team_user_invite');

            // Send email
            MailSender::password_reset_link($user->email);
        } else {
            // if ($user->role_id != CLIENT_ROLE_ID) {
            //     return Response::json([
            //         'status' => false,
            //         'message' => __('This email is not a client.'),
            //     ]);
            //     // abort(419);
            // }

            if ($user->id == Auth::id()) {
                return Response::json([
                    'status' => false,
                    'message' => __('You cannot link yourself.'),
                ]);
                // abort(419);
            }

            if ($user->users_plan->plan_id != FREE_PLAN_ID) {
                return Response::json([
                    'status' => false,
                    'message' => __('This email is not a free user.'),
                ]);
                // abort(419);
            }
        }

        if (empty($user->id) || $user->users_plan->plan_id != FREE_PLAN_ID || $user->role_id != CLIENT_ROLE_ID) {
            return Response::json([
                'status' => false,
                'message' => __('This email is not valid.'),
            ]);
            // abort(419);
        }



        $pro_user = User::where('email', $request->input('email'))->first();

        $users_team = new TeamModel();
        $users_team->team_user_id = Auth::id();
        $users_team->created_by = Auth::id();
        $users_team->pro_user_id = $pro_user->id;
        $users_team->pro_user_email = $pro_user->email;
        $users_team->save();

        // $pro_user->team_user_id = Auth::id();
        // $pro_user->save();

        TeamModel::calc_team_plan();

        $mail_status = MailSender::team_user_invite($pro_user->id);

        if ($mail_status) {
            $users_team->status = 1;
            $users_team->save();
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

    public function unlink(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'id' => 'required|integer|exists:users_teams,id',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        $data = TeamModel::get($request->input('id'));
        // UserModel::set_plan($data->pro_user_id, FREE_PLAN_ID);

        // Delete team and set child account to free plan
        TeamModel::del($request->input('id'), $data->pro_user_id);
        TeamModel::calc_team_plan();

        return Response::json([
            'status' => true,
            'message' => 'Success',
        ], 200);
    }
}
