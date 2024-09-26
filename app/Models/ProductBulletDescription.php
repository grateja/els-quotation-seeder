<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ProductBulletDescription extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'quantity_unit',
        'label',
    ];

    protected $fillable = [
        'product_id',
        'description',
        'quantity',
        'unit',
        'bullet_type',
        'stack_order',
    ];

    public function getLabelAttribute() {
        return "$this->quantity_unit $this->description";
    }

    public function getQuantityUnitAttribute() {
        return "$this->quantity $this->unit";
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
