<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipf_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_order_id')->constrained()->cascadeOnDelete();

            $table->decimal('total_premium', 14, 2);
            $table->decimal('down_payment_percent', 5, 2);
            $table->decimal('down_payment_amount', 14, 2);
            $table->decimal('financed_amount', 14, 2);
            $table->decimal('daily_rate_percent', 5, 2);
            $table->decimal('daily_installment', 14, 2);
            $table->decimal('penalty_percent', 5, 2);

            $table->decimal('outstanding_balance', 14, 2);
            $table->date('start_date');
            $table->date('last_charged_date')->nullable();
            $table->enum('status', ['active', 'completed', 'defaulted'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipf_plans');
    }
};
