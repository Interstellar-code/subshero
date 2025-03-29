<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * PlanModel - Handles plan-related database operations
 *
 * This model manages:
 * - Plan CRUD operations
 * - Plan retrieval by ID or product
 */
class PlanModel extends BaseModel
{
    /** @var string Database table name */
    private const TABLE = 'plans';

    /**
     * Get a plan by ID
     * @param int $id Plan ID
     * @return object|null Plan object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get a plan by product ID and variation ID
     * @param int $product_id Product ID
     * @param int|null $variation_id Variation ID (optional)
     * @return object|null Plan object or null if not found
     */
    public static function get_by_product_id($product_id, $variation_id = null)
    {
        if ($variation_id === 0) {
            $variation_id = null;
        }
        return DB::table(self::TABLE)
            ->where('product_id', $product_id)
            ->where('variation_id', $variation_id)
            ->get()
            ->first();
    }

    /**
     * Get all plans
     * @return \Illuminate\Support\Collection Collection of all plan objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Create a new plan
     * @param array $data Plan data
     * @return int Inserted plan ID
     */
    public static function create($data)
    {
        return DB::table(self::TABLE)
            ->insertGetId(parent::_add_created($data));
    }

    /**
     * Update an existing plan
     * @param int $id Plan ID
     * @param array $data Plan data to update
     * @return int Number of affected rows
     */
    public static function do_update($id, $data)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete a plan
     * @param int $id Plan ID
     * @return int Number of affected rows
     */
    public static function del($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->delete();
    }
}
