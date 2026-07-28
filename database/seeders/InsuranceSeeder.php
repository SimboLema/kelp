<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insurance;

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        $items = [

            ['name' => 'Motor Insurance'],
            ['name' => 'Medical Insurance'],
            ['name' => 'Travel Insurance'],
            ['name' => 'Fire Insurance'],
            ['name' => 'Marine Insurance'],
            ['name' => 'Engineering Insurance'],
            ['name' => 'Personal Accident'],
            ['name' => 'Life Insurance'],

        ];

        foreach ($items as $item) {
            Insurance::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
