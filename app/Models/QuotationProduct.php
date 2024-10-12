<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class QuotationProduct extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'price',
        'discount_str',
        'discounted_price',
        'discounted_price_str',
    ];

    protected $fillable = [
        'quotation_id',
        'product_id',
        'name',
        'unit_price',
        'currency_code',
        'quantity',
        'discount',
    ];

    public function getPriceAttribute() {
        return $this->currency_code . ' ' . number_format($this->unit_price, 2);
    }

    public function getDiscountStrAttribute() {
        return $this->currency_code . ' -' . number_format($this->discount, 2);
    }

    public function getDiscountedPriceStrAttribute() {
        return $this->currency_code . ' ' . number_format($this->discounted_price, 2);
    }

    public function getDiscountedPriceAttribute() {
        return $this->unit_price - $this->discount;
    }

    public function quotation() {
        return $this->belongsTo(Quotation::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function quotationProductBullets() {
        return $this->hasMany(QuotationProductBullet::class);
    }
}
