<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Contact - Manages user contact data
 *
 * This model represents user contact information.
 */
class Contact extends Model
{
    use HasFactory;

    /** @var string Table name for user contacts */
    public $table = 'users_contacts';
    
    /** @var bool Disable timestamps for this model */
    public $timestamps = false;
}
