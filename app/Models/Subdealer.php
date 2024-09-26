<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Subdealer extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'alias'
    ];

    protected $fillable = [
        'abbr',
        'name',
        'area'
    ];

    public function getAliasAttribute() {
        return "$this->abbr $this->name";
    }

    public function customers() {
        return $this->hasMany(Customer::class);
    }
}
