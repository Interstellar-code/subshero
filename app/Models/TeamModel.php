<?php

namespace App\Models;

use App\BaseModel;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeamModel extends BaseModel
{
    use HasFactory;

    protected $table = 'users_teams';
    private const TABLE = 'users_teams';
    private static $user_id = NULL;

    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->leftJoin('users', 'users_teams.pro_user_id', '=', 'users.id')
            ->where('users_teams.team_user_id', self::get_user_id($user_id))
            ->select('users_teams.*', 'users.name as user_name', 'users.email as user_email')
            ->get();
    }

    public static function create($data)
    {
        $id = DB::table(self::TABLE)->insertGetId(parent::_add_created($data));

        return $id;
    }

    public static function del($id, $pro_user_id = null)
    {
        if (empty($pro_user_id)) {
            $data = self::get($id);
            if (!empty($data->id)) {
                $pro_user_id = $data->pro_user_id;
            }
        }

        $status = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();

        // Check if deleted successfully
        if ($status && !empty($pro_user_id)) {
            UserModel::set_plan($pro_user_id, FREE_PLAN_ID);
        }
    }

    public static function calc_team_plan($user_id = NULL)
    {
        $user = DB::table('users')
            ->select('users.*', 'users_plans.plan_id')
            ->join('users_plans', 'users.id', '=', 'users_plans.user_id')
            ->whereIn('users_plans.plan_id', TEAM_PLAN_ALL_ID)
            ->where('users.id', self::get_user_id($user_id))
            ->first();

        if (!empty($user->id)) {

            // Get all team members count
            $team_users_count = DB::table('users_teams')
                ->where('team_user_id', $user->id)
                ->count();

            // Update user plan count
            DB::table('users_plans')
                ->where('user_id', $user->id)
                ->update([
                    'total_teams' => $team_users_count,
                ]);

            return $team_users_count;
        }

        return false;
    }

    public static function is_using($email)
    {
        $count = DB::table('users')
            ->where('users.email', $email)
            ->join('users_teams', 'users_teams.pro_user_id', '=', 'users.id')
            ->count();

        // Check if using
        if ($count > 0) {
            return true;
        }

        // Return not using
        return false;
    }

    private static function get_user_id($user_id = NULL)
    {
        if (empty(self::$user_id)) {
            self::$user_id = Auth::id();
        }

        if (empty($user_id)) {
            return self::$user_id;
        } else {
            return $user_id;
        }
    }
}
