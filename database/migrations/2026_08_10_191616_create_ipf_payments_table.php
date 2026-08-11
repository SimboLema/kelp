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
        Schema::create('ipf_payments', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('ipf_account_id')
                ->constrained('ipf_accounts')
                ->cascadeOnDelete();
        
            $table->foreignId('ipf_installment_id')
                ->nullable()
                ->constrained('ipf_installments')
                ->nullOnDelete();
        
            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
        
            $table->decimal('amount', 15, 2);
        
            $table->string('payment_method')->nullable();
        
            $table->string('transaction_reference')
                ->unique();
        
            $table->enum('status', [
                'pending',
                'successful',
                'failed',
                'reversed'
            ])->default('pending');
        
            $table->timestamp('paid_at')->nullable();
        
            $table->json('payment_response')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipf_payments');
    }
};
