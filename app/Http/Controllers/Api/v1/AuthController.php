<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Library\MailSender;
use App\Models\Api\User as ApiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user = ApiUser::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => __('These credentials do not match our records.'),
            ], 401);
        }

        $user->plan_name = $user->plan_name;
        $token = $user->createToken(env('APP_NAME', 'Subshero') . ' (Auto Generated)')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
        ]);

        // Extra default data
        UserModel::do_update($data->id, [
            'role_id' => 2,
            'first_name' => $data['name'],
            'image' => User_Default_Img,
            'country' => 'US',
        ]);

        // Create default profile
        UserModel::create_profile_default($data->id);
        MailSender::user_registration($data->id);

        $user = ApiUser::find($data->id);
        $token = $user->createToken(env('APP_NAME', 'Subshero') . ' (Auto Generated)')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function token(Request $request)
    {
        $user = $request->user();
        $user->image = img_url($user->image);
        $user->plan_name = $user->plan_name;
        return $user;
    }

    public function logout(Request $request)
    {
        // Revoke all tokens for the user
        // auth()->user()->tokens()->delete();

        // Revoke only current token for the user when cookies not present in request
        // $request->user()->currentAccessToken()->delete();

        // Revoke only current token for the user when cookies present in request
        $request->session()->invalidate();
        $currentAccessToken = PersonalAccessToken::findToken($request->bearerToken());

        // Check if token is valid and found in the database
        if (!empty($currentAccessToken)) {
            $currentAccessToken->delete();
        }

        return [
            'message' => 'Logged out',
        ];
    }
}
