<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * PaymentMethodModel - Manages user payment methods
 *
 * This model handles:
 * - Payment method CRUD operations
 * - Payment method validation and usage tracking
 * - User-specific payment method management
 * - Integration with subscriptions and billing
 */
class PaymentMethodModel extends BaseModel
{
    /** @var string Database table name for payment methods */
    private const TABLE = 'users_payment_methods';
    
    /** @var int|null Current user ID for session persistence */
    private static $user_id = NULL;

    /**
     * Get a payment method by ID
     * @param int $id Payment method ID
     * @return object|null Payment method object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all payment methods
     * @return \Illuminate\Support\Collection Collection of all payment method objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Get all payment methods for a specific user
     * @param int|null $user_id User ID (defaults to current authenticated user)
     * @return \Illuminate\Support\Collection Collection of payment methods ordered by name
     */
    public static function get_by_user($user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get a payment method by name for a specific user
     * @param string $name Payment method name
     * @param int|null $user_id User ID (defaults to current user)
     * @return object|null Payment method object or null if not found
     */
    public static function get_by_name($name, $user_id = NULL)
    {
        return DB::table(self::TABLE)
            ->where('name', $name)
            ->where('user_id', self::get_user_id($user_id))
            ->get()
            ->first();
    }

    /**
     * Create a new payment method
     * @param array $data Payment method data including:
     *   - user_id: Owner of the payment method
     *   - name: Display name
     *   - payment_type: Type (e.g. 'Credit Card', 'PayPal')
     * @return int ID of the newly created payment method
     * @note Automatically updates the user's total payment method count
     */
    public static function create($data)
    {
        $id = DB::table(self::TABLE)
            ->insertGetId($data);
        UsersPlan::update_total_payment();
        return $id;
    }

    /**
     * Update an existing payment method
     * @param int $id Payment method ID
     * @param array $data Fields to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete a payment method
     * @param int $id Payment method ID
     * @return int Number of affected rows
     * @note Automatically updates the user's total payment method count
     */
    public static function del($id)
    {
        $id = DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
        UsersPlan::update_total_payment();
        return $id;
    }

    /**
     * Find or create a payment method by name
     * @param string $name Payment method name
     * @param int|null $user_id User ID (defaults to current user)
     * @return int ID of existing or newly created payment method
     * @note Creates payment method with default type 'Others' if not found
     */
    public static function get_or_create($name, $user_id = NULL)
    {
        $data = DB::table(self::TABLE)
            ->where('user_id', self::get_user_id($user_id))
            ->where('name', $name)
            ->get()
            ->first();

        if (empty($data->id)) {
            return self::create([
                'user_id' => self::get_user_id($user_id),
                'payment_type' => 'Others',
                'name' => $name,
            ]);
        } else {
            return $data->id;
        }
    }

    /**
     * Get the current authenticated user ID with session persistence
     * @param int|null $user_id User ID (defaults to current authenticated user)
     * @return int User ID
     * @note Caches the user ID in static property for subsequent calls
     */
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

    /**
     * Check if a payment method is being used in active subscriptions or history
     * @param int $id Payment method ID
     * @return bool True if payment method is referenced in:
     *   - Active subscriptions table OR
     *   - Subscription history table
     * @note Helps prevent deletion of payment methods still in use
     */
    public static function is_using($id)
    {
        // Subscription check
        $subscription = DB::table('subscriptions')
            ->where('payment_mode_id', $id)
            ->get()
            ->first();

        if (!empty($subscription->id)) {
            return true;
        }

        // Subscription -> History check
        $subscription_history = DB::table('subscriptions_history')
            ->where('payment_mode_id', $id)
            ->get()
            ->first();

        if (!empty($subscription_history->id)) {
            return true;
        }

        // Return not using
        return false;
    }
}
