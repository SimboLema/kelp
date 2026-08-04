<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->dropForeign(['insurer_id']);
            $table->dropForeign(['insurance_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['coverage_id']);
        });
    }

    public function down(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->foreign('insurer_id')->references('id')->on('insurers')->nullOnDelete();
            $table->foreign('insurance_id')->references('id')->on('insurances')->nullOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('coverage_id')->references('id')->on('coverages')->nullOnDelete();
        });
    }
};
