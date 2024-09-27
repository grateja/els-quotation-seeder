<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Customer extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'crn',
        'name',
        'address',
        'contact_number',
        'sales_representative_id',
        'subdealer_id',
    ];

    public function salesRepresentative() {
        return $this->belongsTo(SalesRepresentative::class);
    }

    public function subdealer() {
        return $this->belongsTo(Subdealer::class);
    }

    public function shops() {
        return $this->hasMany(Shop::class);
    }
}
