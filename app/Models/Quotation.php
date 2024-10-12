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

    public function quotationProducts() {
        return $this->hasMany(QuotationProduct::class);
    }


    public static function generateQuotationNumber($prefix) {
        // Step 1: Get current month and year
        $currentMonth = strtoupper(date('M')[0]); // First letter of the month
        $currentYear = date('y'); // Last 2 digits of the year (24 for 2024)

        // Step 2: Build the base for the quotation number (e.g., A24-)
        $baseQuotationNumber = $currentMonth . $currentYear . '-';

        // Step 3: Retrieve the last incremental number
        $lastQuotation = self::where('quotation_number', 'like', "$baseQuotationNumber%")
            ->orderBy('quotation_number', 'desc')
            ->first();

        // Step 4: Extract the incremental part from the last quotation number
        $incremental = 1;
        if ($lastQuotation) {
            $lastIncremental = (int) substr($lastQuotation->quotation_number, 4, 4);
            $incremental = $lastIncremental + 1;
        }

        // Step 5: Format the incremental number as 4 digits (e.g., 0007)
        $incrementalFormatted = str_pad($incremental, 4, '0', STR_PAD_LEFT);

        // Step 6: Generate the final quotation number (e.g., A24-0007-LG)
        $quotationNumber = $baseQuotationNumber . $incrementalFormatted . '-' . strtoupper($prefix);

        return $quotationNumber;
    }
}
