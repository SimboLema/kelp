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
        Schema::create('ipf_installments', function (Blueprint $table) {

            $table->id();
        
            $table->foreignId('ipf_account_id')
                ->constrained('ipf_accounts')
                ->cascadeOnDelete();
        
            $table->unsignedInteger('installment_number');
        
            $table->date('due_date');
        
            $table->decimal('amount_due', 15, 2);
        
            $table->decimal('amount_paid', 15, 2)
                ->default(0);
        
            $table->decimal('remaining_amount', 15, 2);
        
            $table->enum('status', [
                'pending',
                'partial',
                'paid',
                'overdue'
            ])->default('pending');
        
            $table->timestamp('paid_at')->nullable();
        
            $table->timestamps();
        
            $table->unique([
                'ipf_account_id',
                'installment_number'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipf_installments');
    }
};
