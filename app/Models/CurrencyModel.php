<?php

namespace App\Models;

use App\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * CurrencyModel - Manages currency conversion rates
 *
 * This model provides methods to retrieve currency conversion rates.
 */
class CurrencyModel extends BaseModel
{
    /** @var string Database table name */
    private const TABLE = 'currency_conversion';

    /**
     * Get a currency conversion rate by ID
     * @param int $id Currency ID
     * @return object|null Currency object or null if not found
     */
    public static function get($id)
    {
        return DB::table(self::TABLE)
            ->where('id', $id)
            ->get()
            ->first();
    }

    /**
     * Get all currency conversion rates
     * @return \Illuminate\Support\Collection Collection of all currency objects
     */
    public static function get_all()
    {
        return DB::table(self::TABLE)
            ->get();
    }

    /**
     * Get a currency conversion rate by currency code
     * @param string $currency_code Currency code (e.g., USD, EUR)
     * @return object|null Currency object or null if not found
     */
    public static function get_by_code($currency_code)
    {
        return DB::table(self::TABLE)
            ->where('currency_code', $currency_code)
            ->get()
            ->first();
    }
}
