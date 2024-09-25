<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Shop extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'name',
        'customer_id',
        'address',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
