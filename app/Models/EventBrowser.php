<?php

namespace App\Models;

use App\Models\Traits\CheckAlertType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * EventBrowser - Manages event data for browser notifications
 *
 * This model stores event information related to browser notifications.
 */
class EventBrowser extends Model
{
    use HasFactory;
    use CheckAlertType;
    
    /** @var string Table name for event browser data */
    protected $table = 'event_browser';
    
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
}
