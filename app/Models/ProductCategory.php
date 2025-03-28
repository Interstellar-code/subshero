<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\ProductRelatedEntitiesTrait;

class ProductCategory extends Model
{
    use HasFactory;
    use ProductRelatedEntitiesTrait;

    public static $sortableColumns = 'id,name,created_at,updated_at';
}
