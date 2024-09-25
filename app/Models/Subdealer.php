<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Subdealer extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'abbr',
        'name',
        'area'
    ];

    public function customers() {
        return $this->hasMany(Customer::class);
    }
}
