<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\ApiTokenModel;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Sanctum\PersonalAccessToken;

class Controller extends BaseController
{
    public function __construct()
    {
        // Authenticate all requests with bearer token
        $currentAccessToken = PersonalAccessToken::findToken(request()->bearerToken());

        // Check if token is invalid
        if (empty($currentAccessToken)) {
            request()->session()->invalidate();
            return;
        }

        // Update token usage
        ApiTokenModel::where('token', $currentAccessToken->token)->update([
            'last_used_at' => Carbon::now(),
        ]);
    }
}
