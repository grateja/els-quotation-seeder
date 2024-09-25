<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class SalesRepresentative extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'alias'
    ];

    protected $fillable = [
        'name',
        'initials',
        'department',
    ];

    public function getAliasAttribute() {
        return "$this->initials - $this->name";
    }

    public function customers() {
        return $this->hasMany(Customes::class);
    }

    public function quotations() {
        return $this->hasMany(Quotation::class);
    }
}
