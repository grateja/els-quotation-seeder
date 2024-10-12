<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class QuotationProductBullet extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'quotation_product_id',
        'description',
        'bullet_type',
    ];
}
