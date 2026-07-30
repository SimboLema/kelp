<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Insurance;

class InsuranceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('insurances')->insert([
            ['id' => 1, 'name' => 'Motor',                       'code' => 'MOT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Marine',                      'code' => 'MAR', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Fire',                        'code' => 'FIR', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Engineering',                 'code' => 'ENG', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Goods In Transit',            'code' => 'GIT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Miscellaneous & Accidents',   'code' => 'MIS', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
