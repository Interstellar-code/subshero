<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\MailSender;
use App\Models\TeamModel;
use App\Models\TokenModel;
use App\Models\User;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function confirm_show(Request $request, $token)
    {
        $validator = Validator::make([
            'token' => $token,
        ], [
            'token' => 'required|string|size:64',
        ]);

        if ($validator->fails()) {
            abort(401);

            // return Response::json([
            //     'status' => false,
            //     'message' => $validator->errors(),
            // ]);
        }

        $token = $request->route()->parameter('token');

        // $token = TokenModel::retrieve($request->route()->parameter('token'));
        // if (empty($token)) {
        //     abort(401);
        // }

        return view('auth/confirm', [
            'token' => $token,
            'email' => $request->input('email'),
        ]);
    }

    public function confirm_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|size:64',
            'email' => 'required|string|max:' . len()->users->email,
            'password' => 'required|string|min:8|same:password_confirmation|max:' . len()->users->password,
            'password_confirmation' => 'required|string',
        ]);

        $token_value = $request->input('token');
        $email = $request->input('email');

        if ($validator->fails()) {
            return view('auth/confirm', [
                'token' => $token_value,
                'email' => $email,
            ])->withErrors($validator);
        }
        $validated = true;

        // Validate token
        $token = TokenModel::validate('webhook_user_confirm', $token_value, $email);
        if (empty($token)) {
            $validated = false;
            // abort(401);
        }

        if ($validated) {
            $user = UserModel::get($token->table_row_id);
            if (empty($user)) {
                $validated = false;
            }
        }

        if ($validated) {
            // Set password
            UserModel::do_update($user->id, [
                'password' => Hash::make($request->input('password')),
                'email_verified_at' => date(APP_TIMESTAMP_FORMAT),
                'status' => 1,
            ]);

            // Update token
            TokenModel::used($token->id, $user->id);

            Auth::loginUsingId($user->id, false);

            return redirect()->route('/');
        }

        // Return invalid token
        else {
            return view('auth/confirm', [
                'token' => $token_value,
                'email' => $email,
            ])->withErrors([
                'email' => __('This token is invalid.'),
            ]);
        }
    }




    public function email_verify_show(Request $request)
    {
        $user_id = Auth::id();
        $user = UserModel::get($user_id);

        if (empty($user)) {
            return redirect()->route('login');
        }

        if ($user->status != 0) {
            return redirect()->route('/');
        }

        return view('auth/email_send', [
            'email' => $user->email,
        ]);
    }

    public function email_verify_send(Request $request)
    {
        $validator = Validator::make($request->all(), lib()->do->recaptcha_rules([
            'email' => 'required|string|max:' . len()->users->email,
        ]));

        // Validate user
        $user_id = Auth::id();
        $user = UserModel::get($user_id);

        if (empty($user)) {
            return redirect()->route('login');
        }

        if ($user->status != 0) {
            return redirect()->route('/');
        }

        $email = $request->input('email');

        if ($validator->fails()) {
            return view('auth/email_send', [
                'email' => $email,
            ])->withErrors($validator);
        }

        $user = UserModel::get(Auth::id());

        if (empty($user)) {
            return redirect()->route('login');
        }

        if ($user->email != $email) {
            return view('auth/email_send', [
                'email' => $email,
            ])->withErrors([
                'email' => __('Invalid email address'),
            ]);
        }

        $status = MailSender::user_registration($user->id);

        if ($status) {
            return view('auth/email_send', [
                'email' => $email,
                'email_sent' => true,
            ]);
        } else {
            return view('auth/email_send', [
                'email' => $email,
            ])->withErrors([
                'email' => __('Failed to send email'),
            ]);
        }
    }

    public function email_verify_token(Request $request, $token)
    {
        $validator = Validator::make([
            'token' => $token,
            'email' => $request->input('email'),
        ], [
            'token' => 'required|string|size:64',
            'email' => 'required|email',
        ]);

        // $token_value = $request->route()->parameter('token');

        if ($validator->fails()) {
            abort(401);
        }
        $validated = true;

        $token_value = $token;
        $email = $request->input('email');

        // Validate token
        $token = TokenModel::validate('user_verify_email', $token_value, $email);
        if (empty($token)) {
            $validated = false;
            abort(401);
        }

        if ($validated) {
            $user = UserModel::get($token->table_row_id);
            if (empty($user)) {
                $validated = false;
            }
        }

        if ($validated) {
            // Set status
            UserModel::do_update($user->id, [
                'email_verified_at' => date(APP_TIMESTAMP_FORMAT),
                'status' => 1,
            ]);

            // Update token
            TokenModel::used($token->id, $user->id);

            return redirect()->route('/');
        }

        // Return invalid token
        else {
            abort(401);
        }
    }

    public function team_invite_accept(Request $request, $token)
    {
        $validator = Validator::make([
            'token' => $token,
            'email' => $request->input('email'),
        ], [
            'token' => 'required|string|size:64',
            'email' => 'required|email',
        ]);

        // $token_value = $request->route()->parameter('token');

        if ($validator->fails()) {
            abort(401);
        }
        $validated = true;

        $token_value = $token;
        $email = $request->input('email');

        // Validate token
        $token = TokenModel::validate('team_user_invite', $token_value, $email);
        if (empty($token)) {
            $validated = false;
            abort(401);
        }

        if ($validated) {
            $team = TeamModel::find($token->table_row_id);
            if (empty($team->id)) {
                $validated = false;
            }
        }

        if ($validated) {
            $user = User::find($team->pro_user_id);
            if (empty($user->id) || $user->users_plan->plan_id != FREE_PLAN_ID || $user->role_id != CLIENT_ROLE_ID) {
                $validated = false;
            }
        }

        // Check for team user account
        if ($validated) {
            $team_user = User::find($team->team_user_id);
            if (empty($team_user->id) || !in_array($team_user->users_plan->plan_id, TEAM_PLAN_ALL_ID)) {
                $validated = false;
            }
        }

        if ($validated) {

            // Get new plan_id = Pro Recurring or Pro LTD
            $new_plan_id = FREE_PLAN_ID;
            if ($team_user->users_plan->plan_id == TEAM_RECUR_PLAN_ID) {
                $new_plan_id = PRO_RECUR_PLAN_ID;
            } else if ($team_user->users_plan->plan_id == TEAM_LTD_PLAN_ID) {
                $new_plan_id = PRO_LTD_PLAN_ID;
            }

            UserModel::set_plan($team->pro_user_id, $new_plan_id);

            // Update to paid user
            $user->email_verified_at = date(APP_TIMESTAMP_FORMAT);
            $user->status = 1;
            $user->team_user_id = $team->team_user_id;
            $user->save();

            // Accept the invitation
            $team->status = 2;
            $team->save();

            // Update token
            TokenModel::used($token->id, $team->pro_user_id);

            Auth::loginUsingId($team->pro_user_id);

            return redirect()->route('/');
        }

        // Return invalid token
        else {
            abort(401);
        }
    }
}
