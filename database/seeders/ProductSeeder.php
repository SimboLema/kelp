<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Models\KMJ\Product;
use App\Models\Models\KMJ\Insurance;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $motor = Insurance::where('name', 'Motor Insurance')->first();
        $medical = Insurance::where('name', 'Medical Insurance')->first();
        $travel = Insurance::where('name', 'Travel Insurance')->first();

        $products = [

            [
                'insurance_id' => $motor->id,
                'name' => 'Private Motor',
                'code' => 'PM001',
            ],

            [
                'insurance_id' => $motor->id,
                'name' => 'Commercial Motor',
                'code' => 'CM001',
            ],

            [
                'insurance_id' => $medical->id,
                'name' => 'Individual Medical',
                'code' => 'MED001',
            ],

            [
                'insurance_id' => $medical->id,
                'name' => 'Family Medical',
                'code' => 'MED002',
            ],

            [
                'insurance_id' => $travel->id,
                'name' => 'Local Travel',
                'code' => 'TR001',
            ],

            [
                'insurance_id' => $travel->id,
                'name' => 'International Travel',
                'code' => 'TR002',
            ],

        ];

        foreach ($products as $product) {

            Product::updateOrCreate(

                ['code' => $product['code']],

                $product

            );

        }
    }
}
