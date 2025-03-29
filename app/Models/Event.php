<?php

namespace App\Models;

use App\BaseModel;
use App\Models\Traits\CheckAlertType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Event - Manages event data and scheduling
 *
 * This model handles:
 * - Event creation and updates
 * - Setting event statuses (e.g., cancel)
 * - Managing next payment dates for subscriptions
 */
class Event extends BaseModel
{
    use CheckAlertType;
    
    /** @var bool Disable timestamps for this model */
    public $timestamps = false;
    
    /** @var array Fields that are guarded from mass assignment */
    protected $guarded = [];
    
    /** @var array Default attribute values */
    protected $attributes = [
        'event_timezone' => APP_TIMEZONE,
        'event_type_color' => 'green',
        'event_type_schedule' => 0,
        'event_cron' => 0,
        'event_migrate' => 0,
    ];

    /**
     * Constructor: Sets default attribute values
     * @param array $attributes Attributes to set
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes['event_datetime'] = lib()->do->timezone_convert([
            'to_timezone' => APP_TIMEZONE,
        ]);
        $this->attributes['event_url'] = url()->current();
        parent::__construct($attributes);
    }

    /**
     * Update an event
     * @param int $id Event ID
     * @param array $data Data to update
     * @return void
     */
    public static function do_update($id, $data)
    {
        $event = self::find($id);
        if ($event) {
            $event->update($data);
        }
    }

    /**
     * Set cancel status for events related to a subscription
     * @param string $table_name Table name
     * @param int $table_row_id Table row ID
     * @return void
     */
    public static function set_cancel_status($table_name, $table_row_id)
    {
        self::where([
            ['event_type', 'subscription'],
            ['table_name', $table_name],
            ['table_row_id', $table_row_id]
        ])->update([
            'event_cron' => 0,
            'event_type_schedule' => 0,
            'event_timezone' => APP_TIMEZONE,
            'event_datetime' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]),
        ]);
    }

    /**
     * Set the next payment date for a subscription event
     * @param int $subscription_id Subscription ID
     * @param string $next_payment_date Next payment date
     * @return void
     */
    public static function set_next_payment_date($subscription_id, $next_payment_date)
    {
        $history = SubscriptionModel::get_last_history($subscription_id, 1);

        // Update history
        if (!empty($history->id)) {
            SubscriptionHistoryModel::do_update($history->id, [
                'next_payment_date' => $next_payment_date,
            ]);
        }

        self::where([
            ['event_type', 'subscription'],
            ['table_name', 'subscriptions'],
            ['table_row_id', $subscription_id]
        ])->update([
            'event_type_status' => 'update',
            'event_timezone' => APP_TIMEZONE,
            'event_datetime' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
            ]),
            'event_type_scdate' => lib()->do->timezone_convert([
                'to_timezone' => APP_TIMEZONE,
                'date_time' => $next_payment_date,
            ]),
        ]);
    }

    /**
     * Get the event ID by table row ID
     * @param int $table_row_id Table row ID
     * @return int|null Event ID or null if not found
     */
    public static function get_event_id_by_table_row_id($table_row_id)
    {
        return self::where([
            ['event_type', 'subscription'],
            ['event_cron', 0],
            ['table_name', 'subscriptions'],
            ['table_row_id', $table_row_id],
        ])->value('id');
    }
}
