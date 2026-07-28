<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurer;

class InsurerSeeder extends Seeder
{
    public function run(): void
    {
        $insurers = [

            [
                'name' => 'Jubilee Insurance',
                'code' => 'JUB',
                'status' => true,
            ],

            [
                'name' => 'Alliance Insurance',
                'code' => 'ALL',
                'status' => true,
            ],

            [
                'name' => 'NIC Insurance',
                'code' => 'NIC',
                'status' => true,
            ],

            [
                'name' => 'Heritage Insurance',
                'code' => 'HER',
                'status' => true,
            ],

            [
                'name' => 'Sanlam Insurance',
                'code' => 'SAN',
                'status' => true,
            ],

        ];

        foreach ($insurers as $insurer) {
            Insurer::updateOrCreate(
                ['code' => $insurer['code']],
                $insurer
            );
        }
    }
}
