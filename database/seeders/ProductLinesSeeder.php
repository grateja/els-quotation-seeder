<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductLine;

class ProductLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040a',
                'name' => 'Laundry Management System',
                'description' => 'A comprehensive system designed to streamline and manage the operations of laundry businesses, including order tracking, inventory management, and reporting.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040b',
                'name' => 'Electrolux',
                'description' => 'A range of high-efficiency commercial laundry equipment designed for durability, energy savings, and optimized cleaning performance.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040c',
                'name' => 'Lagoon Advanced Care',
                'description' => 'A professional wet cleaning system that offers a sustainable alternative to dry cleaning, using water and biodegradable detergents for delicate garments.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040d',
                'name' => 'Professional Laundry Solutions',
                'description' => 'An array of specialized products including detergents, fabric conditioners, and stain removers, formulated to provide professional-grade cleaning results.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040e',
                'name' => 'Scarpa',
                'description' => 'Premium shoe cleaning and care services designed to restore footwear to its original condition using advanced cleaning techniques and products.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d011040f',
                'name' => 'Installation',
                'description' => 'Professional installation services for laundry equipment, ensuring proper setup and optimal performance of machines and systems.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d0110400',
                'name' => 'Laundry Essentials',
                'description' => 'A selection of essential products and accessories for laundry operations, including laundry bags, hampers, garment covers, and hangers.'
            ],
            [
                'id' => 'ba887f9c-46ab-4b6d-ae16-0a51d0110401',
                'name' => 'Logistics',
                'description' => 'Comprehensive logistics services for the transportation and delivery of laundry items, ensuring timely and efficient service for customers.'
            ],
        ];

        foreach ($data as $productLine) {
            ProductLine::create($productLine);
        }
    }
}
