<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Product extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'id',
        'name',
        'model',
        'brand',
        'unit_price',
        'currency_code',
        'product_line_id',
    ];

    public function productLine() {
        return $this->belongsTo(ProductLine::class);
    }

    public function productBulletDescriptions() {
        return $this->hasMany(ProductBulletDescription::class);
    }
}
