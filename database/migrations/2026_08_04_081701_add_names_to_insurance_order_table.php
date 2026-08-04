<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance__orders', function (Blueprint $table) {
            $table->string('insurer_name')->nullable()->after('insurer_id');
            $table->string('insurance_name')->nullable()->after('insurance_id');
            $table->string('product_name')->nullable()->after('product_id');
            $table->string('coverage_name')->nullable()->after('coverage_id');
        });
    }

    public function down(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->dropColumn(['insurer_name', 'insurance_name', 'product_name', 'coverage_name']);
        });
    }
};
