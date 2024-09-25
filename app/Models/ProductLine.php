<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ProductLine extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'id',
        'name',
        'description',
        'sales_representative_id',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
