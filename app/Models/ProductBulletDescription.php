<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ProductBulletDescription extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'product_id',
        'description',
        'unit_price',
        'currency_code',
        'bullet_type',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
