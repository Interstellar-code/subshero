<?php

namespace App\Http\Controllers\Client\Settings;

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
use App\Models\ProductModel;
use App\Models\SubscriptionModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\PaymentMethodModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'slug' => 'contact',
        ];
        return view('client/settings/contact/index', $data);
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

        return Response::json(UserModel::contact_get($request->input('id')));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:' . len()->users_contacts->name,
            'email' => [
                'required',
                'string',
                'max:' . len()->users_contacts->email,
                Rule::unique('users_contacts', 'email')
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
            // abort(419);
        }

        // Check limit
        if (UserModel::limit_reached('contact')) {
            return lib()->do->get_limit_msg($request, 'contact');
        }

        $data = [
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'status' => $request->input('status') ? 1 : 0,
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

    public function edit(Request $request)
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

        $data = [
            'slug' => 'settings/contact',
            'data' => UserModel::contact_get($request->input('id')),
        ];

        return view('client/settings/contact/edit', $data);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'name' => 'required|string|max:' . len()->users_contacts->name,
            'email' => [
                'required',
                'string',
                'max:' . len()->users_contacts->email,
                Rule::unique('users_contacts', 'email')
                    ->ignore($request->input('id'))
                    ->where(function ($query) {
                        return $query->where('user_id', Auth::id());
                    }),
            ],
            'status' => 'nullable|boolean',
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
            'status' => $request->input('status') ? 1 : 0,
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

    public function delete(Request $request)
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
}
