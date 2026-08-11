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
        Schema::create('ipf_plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->text('description')->nullable();

            $table->unsignedInteger('duration_days');

            $table->decimal('down_payment_percent', 5, 2)
                ->default(20);

            $table->decimal('daily_rate_percent', 5, 2)
                ->nullable();

            $table->enum('calculation_method', [
                'fixed',
                'remaining_balance_percentage'
            ])->default('fixed');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipf_plans');
    }
};
