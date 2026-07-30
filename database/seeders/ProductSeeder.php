<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Insurance;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['code' => 'SP015001000000', 'name' => 'Own Transport within Tanzania- All Risks Containerised', 'insurance_id' => 5],
            ['code' => 'SP015002000000', 'name' => 'Own Transport within Tanzania- All Risks Non-Containerised', 'insurance_id' => 5],
            ['code' => 'SP015003000000', 'name' => 'Hauliers Liability within Tanzania- All Risks Containerised', 'insurance_id' => 5],
            ['code' => 'SP015004000000', 'name' => 'Hauliers Liability within Tanzania- All Risks Non-Containerised', 'insurance_id' => 5],
            ['code' => 'SP015005000000', 'name' => 'Standard GIT(Fire,Collision and Overturning cover)Own Transport', 'insurance_id' => 5],
            ['code' => 'SP015006000000', 'name' => 'Standard GIT(Fire,Collision and Overturning cover)Own Transport - Non-Containerised', 'insurance_id' => 5],
            ['code' => 'SP015007000000', 'name' => 'Standard GIT(Fire,Collision and Overturning cover)Hauliers Liability - Containerised', 'insurance_id' => 5],
            ['code' => 'SP015008000000', 'name' => 'Standard GIT(Fire,Collision and Overturning cover)-Hauliers Liability - Non- Containerised', 'insurance_id' => 5],
            ['code' => 'SP015009000000', 'name' => 'Own Transport - All Risks Containerised- Including Hijacking', 'insurance_id' => 5],
            ['code' => 'SP015010000000', 'name' => 'Own Transport - All Risks Non-Containerised-Including Hijacking', 'insurance_id' => 5],
            ['code' => 'SP015011000000', 'name' => 'Hauliers Liability - All Risks Containerised-Including Hijacking', 'insurance_id' => 5],
            ['code' => 'SP015012000000', 'name' => 'Hauliers Liability - All Risks Non-Containerised-Including Hijacking', 'insurance_id' => 5],
            ['code' => 'SP015013000000', 'name' => 'Own Transport outside Tanzania- All Risks Containerised', 'insurance_id' => 5],
            ['code' => 'SP015014000000', 'name' => 'Own Transport outside Tanzania- All Risks Non-Containerised', 'insurance_id' => 5],
            ['code' => 'SP015015000000', 'name' => 'Hauliers Liability outside Tanzania- All Risks Containerised', 'insurance_id' => 5],
            ['code' => 'SP015016000000', 'name' => 'Hauliers Liability outside Tanzania- All Risks Non-Containerised', 'insurance_id' => 5],
            ['code' => 'SP014001000000', 'name' => 'MOTOR PRIVATE VEHICLE', 'insurance_id' => 1],
            ['code' => 'SP014002000000', 'name' => 'MOTOR MOTOR CYCLE', 'insurance_id' => 1],
            ['code' => 'SP014003000000', 'name' => 'MOTOR Commercial Vehicle', 'insurance_id' => 1],
            ['code' => 'SP014004000000', 'name' => 'MOTOR PASSENGER CARRYING', 'insurance_id' => 1],
            ['code' => 'SP014005000000', 'name' => 'MOTOR SPECIAL TYPE VEHICLES', 'insurance_id' => 1],
            ['code' => 'SP013001000000', 'name' => 'BURGLARY', 'insurance_id' => 6],
            ['code' => 'SP013002000000', 'name' => 'EMPLOYERS LIABILITY', 'insurance_id' => 6],
            ['code' => 'SP013003000000', 'name' => 'WORKMEN’S COMPENSATION', 'insurance_id' => 6],
            ['code' => 'SP013004000000', 'name' => 'LIABILITY', 'insurance_id' => 6],
            ['code' => 'SP013005000000', 'name' => 'BONDS', 'insurance_id' => 6],
            ['code' => 'SP013006000000', 'name' => 'MONEY', 'insurance_id' => 6],
            ['code' => 'SP013007000000', 'name' => 'INDIVIDUAL PERSONAL ACCIDENT', 'insurance_id' => 6],
            ['code' => 'SP013008000000', 'name' => 'GROUP PERSONEL ACCIDENT', 'insurance_id' => 6],
            ['code' => 'SP013009000000', 'name' => 'FIDELITY GUARANTEE', 'insurance_id' => 6],
            ['code' => 'SP010001000000', 'name' => 'Machinery breakdown', 'insurance_id' => 4],
            ['code' => 'SP010002000000', 'name' => 'CONTRACTORS’ ALL RISK INSURANCE - Risk with sum Insured up to 1 Billion', 'insurance_id' => 4],
            ['code' => 'SP010003000000', 'name' => 'CONTRACTORS’ ALL RISK INSURANCE - Risk with sum Insured above 1 Billion', 'insurance_id' => 4],
            ['code' => 'SP010004000000', 'name' => 'ERECTION ALL RISKS INSURANCE', 'insurance_id' => 4],
            ['code' => 'SP012001000000', 'name' => 'Fire Class I', 'insurance_id' => 3],
            ['code' => 'SP012002000000', 'name' => 'FIRE LOSS OF PROFIT(For indemity period of one year=fire rate) Class I', 'insurance_id' => 3],
            ['code' => 'SP012003000000', 'name' => 'FIRE LOSS OF PROFIT(For indemity period of less than one year=0.75 * fire rate) Class I', 'insurance_id' => 3],
            ['code' => 'SP012004000000', 'name' => 'Fire other than  Class 1 other than Makuti', 'insurance_id' => 3],
            ['code' => 'SP012005000000', 'name' => 'FIRE LOSS OF PROFIT Other than  Class 1 other than Makuti FOR INDEMNITY PERIOD OF ONE Year', 'insurance_id' => 3],
            ['code' => 'SP012006000000', 'name' => 'FIRE LOSS OF PROFIT Other than  Class 1 other than Makuti FOR INDEMNITY PERIOD OF LESS THAN  ONE Year', 'insurance_id' => 3],
            ['code' => 'SP012007000000', 'name' => 'FIRE GIN', 'insurance_id' => 3],
            ['code' => 'SP012008000000', 'name' => 'FIRE LOSS OF PROFIT GIN(For Indemity period of One year)', 'insurance_id' => 3],
            ['code' => 'SP012009000000', 'name' => 'FIRE LOSS OF PROFIT GIN(For Indemity period of Less than One year)', 'insurance_id' => 3],
            ['code' => 'SP012010000000', 'name' => 'FIRE(RATING FOR MAKUTI/THATCHED CONSTRUCTION)', 'insurance_id' => 3],
            ['code' => 'SP012011000000', 'name' => 'Fire-Class I Floater policies', 'insurance_id' => 3],
            ['code' => 'SP011001000000', 'name' => 'MARINE RATES ICC Clause (A) CONTAINERIZED - MARINE OPEN COVER SUM INSURED LESS THAN TZS 500 Million', 'insurance_id' => 2],
            ['code' => 'SP011002000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) (A) NON CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011003000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC)B - CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011004000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) B- NON CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011005000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) C- CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011006000000', 'name' => 'MARINE RATES Institute Cargo Clause (ICC)C- NON CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011007000000', 'name' => 'MARINE RATES ICC Clause (A) CONTAINERIZED - MARINE OPEN COVER SUM INSURED MORE THAN TZS 500 Million', 'insurance_id' => 2],
            ['code' => 'SP011008000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) (A) NON CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011009000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC)B - CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011010000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) B- NON CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011011000000', 'name' => 'MARINE RATES Institute Cargo Clause(ICC) C- CONTAINERIZED', 'insurance_id' => 2],
            ['code' => 'SP011012000000', 'name' => 'MARINE RATES Institute Cargo Clause (ICC)C- NON CONTAINERIZED', 'insurance_id' => 2]
        ];

        DB::table('products')->insert($products);
    }
}
