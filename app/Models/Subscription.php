<?php

namespace App\Models;

use App\BaseModel;
use App\Library\NotificationEngine;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends BaseModel
{
    use HasFactory;

    protected $fillable = [];
    public $timestamps = false;
    public static $orderByFields = [
        'id',
        'name',
        'description',
        'price',
        'payment_date',
        'created_at',
        'updated_at',
    ];
    public static $sortableColumns = 'id,product_name,description,price,payment_date,created_at,updated_at';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'type_value',
        'recurring_value',
        'billing_cycle_value',
        'billing_type_value',
        'ltdval_cycle_value',
        'status_value',
        'pricing_type_value',
        'sub_addon_value',
    ];

    public function getTypeValueAttribute()
    {
        return table('subscription.type', $this->type, null);
    }
    public function getRecurringValueAttribute()
    {
        return table('subscription.recurring', $this->recurring, null);
    }
    public function getBillingCycleValueAttribute()
    {
        return table('subscription.cycle_ly', $this->billing_cycle, null);
    }
    public function getBillingTypeValueAttribute()
    {
        return table('subscription.billing_type_uppercase', $this->billing_type, null);
    }
    public function getLtdvalCycleValueAttribute()
    {
        return table('subscription.cycle_ly', $this->ltdval_cycle, null);
    }
    public function getStatusValueAttribute()
    {
        return table('subscription.status', $this->status, null);
    }
    public function getPricingTypeValueAttribute()
    {
        return table('subscription.type', $this->pricing_type, null);
    }
    public function getSubAddonValueAttribute()
    {
        return table('subscription.sub_addon', $this->sub_addon, 0);
    }
    // public function getTagsAttribute()
    // {
    //     return Tag::join('subscriptions_tags', 'subscriptions_tags.tag_id', '=', 'tags.id')
    //         ->where('subscriptions_tags.subscription_id', $this->id)
    //         ->select('tags.*')
    //         ->get();
    // }

    /**
     * Get the product.
     */
    public function tags()
    {
        return $this->hasManyThrough(Tag::class, SubscriptionTagMap::class, 'subscription_id', 'id', 'id', 'tag_id')->distinct();
    }

    /**
     * Get the product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'brand_id');
    }

    /**
     * Get the product category.
     */
    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the product type.
     */
    public function product_type()
    {
        return $this->belongsTo(ProductType::class, 'product_type');
    }

    /**
     * Get the product type obj.
     */
    public function product_type_obj()
    {
        return $this->belongsTo(ProductType::class, 'product_type');
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the folder.
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Get the payment method.
     */
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_mode_id');
    }

    /**
     * Get the alert.
     */
    public function alert()
    {
        $default = (object) [
            'id' => 0,
            'alert_types' => [],
            'alert_condition' => [],
        ];
        return $this->belongsTo(UserAlert::class, 'alert_id')->withDefault($default);
    }

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

    public static function queue_push_notification($subscription, string $notification_type = null)
    {
        // Create push notification for user
        $event_browser_notify = NotificationEngine::newModel('browser');
        $event_browser_notify->type = $notification_type;
        $event_browser_notify->user_id = $subscription->user_id;
        $event_browser_notify->image = img_url($subscription->image, null);
        $event_browser_notify->redirect_url = url('subscription');

        // Generate push notification title and message
        $event_browser_notify->title = $subscription->product_name;
        $event_browser_notify->message = $subscription->product_name;
        $types_to_messages = [
            'subscription_create' => ' created',
            'subscription_update' => ' updated',
            'subscription_delete' => ' deleted',
            'subscription_renew' => ' renewed',
            'subscription_refund' => ' refunded',
            'subscription_cancel' => ' cancelled',
        ];
        $event_browser_notify->message .= $types_to_messages[$notification_type] ?? '';

        $event_browser_notify->save();

        return $event_browser_notify->id;
    }

    public static function lookType($searchValue)
    {
        // Predefined search terms
        $terms = [

            // Subscription
            1 => [
                'subscriptions',
            ],

            // Trial
            2 => [
                'trial',
            ],

            // Lifetime
            3 => [
                'lifetimes',
            ],

            // Revenue
            4 => [
                'revenue',
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

    public static function lookRecurring(string $searchValue)
    {
        // Predefined search terms
        $terms = [

            // Once
            0 => [
                'once',
            ],

            // Recurring
            1 => [
                'recurring',
            ],
        ];

        return parent::_filter($searchValue, $terms);
    }

    public static function getDescribedSubscription($id) {
        // Take specific columns from the subscription data
        $subscription = self::where('id', $id)
            ->with(
                'folder:id,name,color,is_default',
                'product:id,product_name,brandname,category_id,product_type,description',
                'product_category:id,name',
                'product_type:id,name',
                'tags:tags.id,tags.name'
            )
            ->first();
        
        if (empty($subscription->id)) {
            return [];
        }

        $subscription_array = self::mergeTags($subscription);

        return $subscription_array;
    }

    public static function mergeTags($subscription) {
        // Take id, name from tags
        $subscription_array = $subscription->toArray();
        if (!empty($subscription_array['tags'])) {
            $tags_collection = collect($subscription_array['tags']);
            $subscription_array['tags'] = $tags_collection->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                ];
            });
        }
        return $subscription_array;
    }

    public function actualize_next_payment_date() {
        $subscription_history = SubscriptionHistory::select('next_payment_date')
            ->where('subscription_id', $this->id)
            ->latest('id')
            ->first();
        $this->next_payment_date = $subscription_history->next_payment_date;
        $this->save();
    }

    public function addTag($tagName)
    {
        $user_tags = TagModel::get_by_user_arr($this->user_id);
        if (!in_array($tagName, $user_tags)) {
            $tag_id = TagModel::create([
                'user_id' => $this->user_id,
                'name' => $tagName,
            ]);
            SubscriptionModel::create_tags([[
                'user_id' => $this->user_id,
                'subscription_id' => $this->id,
                'tag_id' => $tag_id,
            ]]);
            return "The new tag $tagName for the user is attached to the subscription.";
        } else {
            $tag_id = TagModel::where([
                'user_id' => $this->user_id,
                'name' => $tagName
            ])->first()->id;
            $already_attached_tag = SubscriptionTagMap::where([
                'user_id' => $this->user_id,
                'subscription_id' => $this->id,
                'tag_id' => $tag_id,
            ])->first();
            if (!$already_attached_tag) {
                SubscriptionModel::create_tags([[
                    'user_id' => $this->user_id,
                    'subscription_id' => $this->id,
                    'tag_id' => $tag_id,
                ]]);
                return "The old tag $tagName for the user is attached to the subscription.";
            } else {
                return "The tag $tagName is already attached to the subscription.";
            }
        }
    }

    public function updateTags($tagNames)
    {
        if (is_array($tagNames)) {
            SubscriptionModel::delete_tags($this->id);
            foreach ($tagNames as $tagName) {
                $this->addTag($tagName);
            }
            return "The tags are updated for the subscription.";
        } else {
            return "The tag names must be an array.";
        }
    }

    public function removeTag($tagName)
    {
        $tag = TagModel::where([
            'user_id' => $this->user_id,
            'name' => $tagName
        ])->first();
        if ($tag) {
            $tag_id = $tag->id;
            $already_attached_tag = SubscriptionTagMap::where([
                'user_id' => $this->user_id,
                'subscription_id' => $this->id,
                'tag_id' => $tag_id,
            ])->first();
            if ($already_attached_tag) {
                $already_attached_tag->delete();
                return "The tag $tagName is detached from the subscription.";
            } else {
                return "The tag $tagName is not attached to the subscription.";
            }
        } else {
            return "The tag $tagName does not exist for this user.";
        }
    }

    public function addFolder($folderName)
    {
        $folder = Folder::where([
            'user_id' => $this->user_id,
            'name' => $folderName
        ])->first();
        if ($folder) {
            if ($this->folder_id == $folder->id) {
                return "The folder $folderName is already attached to the subscription.";
            } else {
                $this->folder_id = $folder->id;
                $this->save();
                return "The folder $folderName is attached to the subscription.";
            }
        } else {
            return "The folder $folderName does not exist for this user.";
        }
    }

    public function addFolderById($folderId)
    {
        $folder = Folder::where([
            'user_id' => $this->user_id,
            'id' => $folderId
        ])->first();
        if ($folder) {
            if ($this->folder_id == $folder->id) {
                return "The folder $folder->name is already attached to the subscription.";
            } else {
                $this->folder_id = $folder->id;
                $this->save();
                return "The folder $folder->name is attached to the subscription.";
            }
        } else {
            return "The folder with id $folderId does not exist for this user.";
        }
    }

    public function removeFolder($folderName)
    {
        $this->folder_id = 0;
        $this->save();
        return "The folder $folderName is detached from the subscription";
    }

    public function addCategory($categoryName)
    {
        $category = ProductCategory::whereName($categoryName)->first();
        if ($category) {
            if ($this->category_id == $category->id) {
                return "The category $categoryName is already attached to the subscription";
            } else {
                $this->category_id = $category->id;
                $this->save();
                return "The category $categoryName is attached to the subscription";
            }
        } else {
            return "The category $categoryName does not exist";
        }
    }

    public function removeCategory($categoryName)
    {
        $this->category_id = 1;
        $this->save();
        return "The category $categoryName is detached from the subscription";
    }
}
