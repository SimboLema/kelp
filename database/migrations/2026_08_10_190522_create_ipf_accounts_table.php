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
        Schema::create('ipf_accounts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('insurance_order_id')
                ->constrained('insurance_orders')
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('ipf_plan_id')
                ->constrained('ipf_plans')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Snapshot of the agreement
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_premium', 15, 2);

            $table->decimal('down_payment_percent', 5, 2);

            $table->decimal('down_payment_amount', 15, 2);

            $table->decimal('financed_amount', 15, 2);

            $table->decimal('total_paid', 15, 2)
                ->default(0);

            $table->decimal('remaining_amount', 15, 2);

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            $table->date('start_date')->nullable();

            $table->date('expected_end_date')->nullable();

            

            $table->enum('status', [
                'pending',
                'active',
                'completed',
                'defaulted',
                'cancelled'
            ])->default('pending');

            $table->timestamps();

            $table->unique('insurance_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ipf_accounts');
    }
};
