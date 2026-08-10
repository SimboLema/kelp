<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipf_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipf_plan_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['down_payment', 'installment', 'penalty']);
            $table->decimal('amount', 14, 2);
            $table->decimal('balance_after', 14, 2);
            $table->date('transaction_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipf_transactions');
    }
};
