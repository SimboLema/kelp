<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Coverage;

class CoverageSeeder extends Seeder
{
    public function run(): void
    {
        $privateMotor = Product::where('code', 'PM001')->first();
        $commercialMotor = Product::where('code', 'CM001')->first();

        $coverages = [

            [
                'product_id' => $privateMotor->id,
                'risk_name' => 'Comprehensive',
                'risk_code' => 'COMP',
                'rate' => 3.50,
                'minimum_amount' => 250000,
                'coverage_type' => 'Motor',
            ],

            [
                'product_id' => $privateMotor->id,
                'risk_name' => 'Third Party',
                'risk_code' => 'TP',
                'rate' => 1.20,
                'minimum_amount' => 120000,
                'coverage_type' => 'Motor',
            ],

            [
                'product_id' => $commercialMotor->id,
                'risk_name' => 'Commercial Comprehensive',
                'risk_code' => 'CCOMP',
                'rate' => 4.20,
                'minimum_amount' => 350000,
                'coverage_type' => 'Motor',
            ],

        ];

        foreach ($coverages as $coverage) {

            Coverage::updateOrCreate(

                [
                    'product_id' => $coverage['product_id'],
                    'risk_code' => $coverage['risk_code'],
                ],

                $coverage

            );

        }
    }
}
