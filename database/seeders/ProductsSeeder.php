<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc60',
                'name' => '3 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 67000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc61',
                'name' => '4 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 73000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc62',
                'name' => '5 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 79000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc63',
                'name' => '6 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 85000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc64',
                'name' => '7 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 94000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc65',
                'name' => '8 Sets Laundry Management System',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 100000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc66',
                'name' => 'LMS Module',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 20000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc67',
                'name' => 'Card terminal',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 2500,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
            [
                'id' => '0902d6b3-3a29-466a-adaf-315e190cfc68',
                'name' => 'Thermal Printer',
                'model' => 'LMS',
                'brand' => 'ELS',
                'unit_price' => 4000,
                'currency_code' => 'PHP',
                'product_line_id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
            ],
        ];

        foreach($data as $product) {
            Product::create($product);
        }
    }
}
