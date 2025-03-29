<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Config - Manages configuration data
 *
 * This model is used to access and manage application configuration settings.
 */
class Config extends Model
{
    use HasFactory;

    /** @var string Table name for configuration settings */
    public $table = 'config';
    
    /** @var bool Disable timestamps for this model */
    public $timestamps = false;
}
