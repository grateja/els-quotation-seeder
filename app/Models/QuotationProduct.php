<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class QuotationProduct extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'unit_price',
        'currency_code',
        'quantity',
        'discount',
    ];

    public function quotation() {
        return $this->belongsTo(Quotation::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
