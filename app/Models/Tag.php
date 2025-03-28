<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // public $timestamps = false;
    public static $sortableColumns = 'id,name,created_at,updated_at';
}
