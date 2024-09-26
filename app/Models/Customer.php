<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Customer extends Model
{
    use HasFactory, UsesUuid;

    public $appends = [
        'sales_representative_name'
    ];

    protected $fillable = [
        'crn',
        'name',
        'address',
        'contact_number',
        'sales_representative_id',
        'subdealer_id',
    ];

    public function getSalesRepresentativeNameAttribute() {
        if($this->salesRepresentative) {
            return $this->salesRepresentative->name;
        } else if($this->subdealer) {
            return $this->subdealer->name;
        }
    }

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
