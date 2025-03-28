<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    public $table = 'users_payment_methods';
    // public $timestamps = false;
    public static $sortableColumns = 'id,name,description,expiry,created_at,updated_at';
}
