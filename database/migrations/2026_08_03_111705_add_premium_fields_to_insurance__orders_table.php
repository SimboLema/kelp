<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->decimal('sum_insured', 15, 2)->nullable()->after('coverage_id');
            $table->decimal('premium', 15, 2)->nullable()->after('sum_insured');
            $table->json('premium_breakdown')->nullable()->after('premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance__orders', function (Blueprint $table) {
            //
        });
    }
};
