<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\Controller;
use App\Library\NotificationEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = NotificationEngine::staticModel('extension')::where([
            ['user_id', Auth::id()],
            ['read', 0],
        ])->orderByDesc('id')
            ->limit(5)
            ->get();

        return $data;
    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists(NotificationEngine::table('extension'), 'id')->where([
                    ['user_id', Auth::id()],
                    ['read', 0]
                ]),
            ],
            'read' => 'required|integer|in:0,1',
        ]);

        $notification = NotificationEngine::staticModel('extension')::find($fields['id']);
        $notification->read = $fields['read'];
        $notification->save();

        return response()->json($notification);
    }

    public function delete(Request $request, $id)
    {
        $fields = $request->mergeIfMissing([
            'id' => $id,
        ])->validate([
            'id' => [
                'required',
                'integer',
                Rule::exists(NotificationEngine::table('extension'), 'id')->where([
                    ['user_id', Auth::id()],
                    ['read', 0]
                ]),
            ],
        ]);

        $notification = NotificationEngine::staticModel('extension')::find($fields['id']);
        $notification->read = 1;
        $notification->save();

        return response()->json($notification);
    }

    public function clear(Request $request)
    {
        NotificationEngine::staticModel('extension')::where('user_id', Auth::id())
            ->where('read', 0)
            ->update(['read' => 1]);

        return response()->json(['success' => true]);
    }
}
