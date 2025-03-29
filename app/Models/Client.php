<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Client - Manages client data
 *
 * This model handles:
 * - Client CRUD operations
 * - Retrieval of clients, especially user-specific clients
 */
class Client extends BaseModel
{
    /**
     * Get a client by ID
     * @param int $id Client ID
     * @return object|null Client object or null if not found
     */
    public static function get($id)
    {
        return DB::table('clients')
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all clients
     * @return \Illuminate\Support\Collection Collection of all client objects
     */
    public static function get_all()
    {
        return DB::table('clients')
            ->get();
    }

    /**
     * Get all clients for a specific user
     * @param int|null $user_id User ID (defaults to current user)
     * @return \Illuminate\Support\Collection Collection of client objects for the user
     */
    public static function get_by_user($user_id = NULL)
    {
        if (empty($user_id)) {
            $user_id = Auth::id();
        }
        return DB::table('clients')
            ->where('user_id', $user_id)
            ->select('clients.*', 'clients.name as client_name')
            ->get();
    }
}
