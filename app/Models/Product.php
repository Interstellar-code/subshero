<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\BaseModel;

class Product extends BaseModel
{
    use HasFactory;
    public static $sortableColumns = 'id,product_name,brandname,description,rating,launch_year,currency_code,price1_value,refund_days';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        // Return original value for some attributes.
        switch ($key) {
            case '_image':
                return $this->getRawOriginal('image');
            case '_favicon':
                return $this->getRawOriginal('favicon');
            default:
                return parent::__get($key);
        }
    }

    /**
     * Get the product's image.
     *
     * @param  string  $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        return img_url($value);
    }

    /**
     * Get the product's favicon.
     *
     * @param  string  $value
     * @return string
     */
    public function getFaviconAttribute($value)
    {
        return img_url($value);
    }

    public static function lookPricingType($searchValue)
    {
        // Predefined search terms
        $terms = [

            // Subscription
            1 => [
                'subscriptions',
            ],

            // Trial
            2 => [
                'trials',
            ],

            // Lifetime
            3 => [
                'ltd',
                'lifetimes',
            ],

            // Revenue
            4 => [
                'revenues',
            ],
        ];

        return parent::_filter($searchValue, $terms);
    }

    public static function lookBillingCycle(string $searchValue)
    {
        // Predefined search terms
        $terms = [

            // Daily
            1 => [
                'daily',
            ],

            // Weekly
            2 => [
                'weekly',
            ],

            // Monthly
            3 => [
                'monthly',
            ],

            // Yearly
            4 => [
                'yearly',
            ],
        ];

        return parent::_filter($searchValue, $terms);
    }

    public static function lookPaymentType(string $searchValue)
    {
        // Predefined search terms
        $terms = [

            // One time
            1 => [
                'one time',
            ],

            // Recurring
            2 => [
                'recurring',
            ],
        ];

        return parent::_filter($searchValue, $terms);
    }
}
