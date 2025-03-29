<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ApiToken - Represents a personal access token
 *
 * This model is used to manage API tokens for user authentication.
 */
class ApiToken extends Model
{
    use HasFactory;

    /** @var string Table name for personal access tokens */
    public $table = 'personal_access_tokens';
    
    /** @var bool Disable timestamps for this model */
    public $timestamps = false;
}
