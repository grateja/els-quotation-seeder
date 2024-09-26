<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Product extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'price'
    ];

    protected $fillable = [
        'id',
        'name',
        'model',
        'brand',
        'unit_price',
        'currency_code',
        'product_line_id',
    ];

    public function getPriceAttribute() {
        return $this->currency_code . ' ' . number_format($this->unit_price, 2);
    }

    public function productLine() {
        return $this->belongsTo(ProductLine::class);
    }

    public function bullets() {
        return $this->hasMany(ProductBulletDescription::class, 'product_id', 'id')->orderBy('stack_order');
    }
}
