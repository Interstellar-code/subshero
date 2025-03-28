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

class MarketplaceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'marketplace',
        ];
        return view('client/settings/marketplace/index', $data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|in:' . Auth::user()->id,
            'paypal_api_username' => 'required|string|max:' . len()->users->paypal_api_username,
            'paypal_api_password' => 'required|string|max:' . len()->users->paypal_api_password,
            'paypal_api_secret' => 'required|string|max:' . len()->users->paypal_api_secret,
            'marketplace_status' => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $user = User::find($request->id);
        $user->paypal_api_username = $request->paypal_api_username;
        $user->paypal_api_password = $request->paypal_api_password;
        $user->paypal_api_secret = $request->paypal_api_secret;
        $user->marketplace_status = $request->marketplace_status ?? 0;

        if (empty($user->marketplace_token)) {
            $user->marketplace_token = User::generateMarketplaceToken();
        }

        $user->save();


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
