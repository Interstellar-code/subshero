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
        // Authenticate all requests with bearer token by retrieving the token from the request header
        $currentAccessToken = PersonalAccessToken::findToken(request()->bearerToken());

        // Check if the retrieved token is invalid
        if (empty($currentAccessToken)) {
            request()->session()->invalidate();
            return;
        }

        // Update the token's last used timestamp in the database
        ApiTokenModel::where('token', $currentAccessToken->token)->update([
            'last_used_at' => Carbon::now(), // Set the last_used_at timestamp to the current time
        ]);
    }
}
