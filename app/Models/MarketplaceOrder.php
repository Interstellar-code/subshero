<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    use HasFactory;
    public $table = 'marketplace_orders';

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
            case '_product_logo':
                return $this->getRawOriginal('product_logo');
            default:
                return parent::__get($key);
        }
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the marketplace.
     */
    public function marketplace_item()
    {
        return $this->belongsTo(Marketplace::class, 'marketplace_item_id');
    }

    /**
     * Get the subscription.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the product category.
     */
    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    /**
     * Get the product platform.
     */
    public function product_platform()
    {
        return $this->belongsTo(ProductPlatform::class, 'product_platform_id');
    }

    /**
     * Get the product's logo.
     *
     * @param  string  $value
     * @return string
     */
    public function getProductLogoAttribute($value)
    {
        return img_url($value);
    }
}
