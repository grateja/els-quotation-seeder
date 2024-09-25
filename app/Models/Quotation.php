<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Quotation extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'quotation_number',
        'user_id',
        'subdealer_id',
        'sales_representative_id',
        'customer_id',
        'shop_id',
        'status',
    ];

    public function preparedBy() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subdealer() {
        return $this->belongsTo(Subdealer::class);
    }

    public function salesRepresentative() {
        return $this->belongsTo(SalesRepresentative::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}
