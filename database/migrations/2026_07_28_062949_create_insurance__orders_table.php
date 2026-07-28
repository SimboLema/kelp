<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insurance_orders', function (Blueprint $table) {

            $table->id();

            $table->string('reference_no')->unique();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('insurer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('insurance_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('coverage_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Free-text request if the customer doesn't know the product
            $table->text('description')->nullable();

            $table->enum('status',[
                'Pending',
                'Submitted',
                'Processing',
                'Approved',
                'Rejected',
                'Cancelled'
            ])->default('Pending');

            // Integration fields
            $table->enum('transmission_status',[
                'Pending',
                'Sent',
                'Failed'
            ])->default('Pending');

            $table->string('external_reference')->nullable();

            $table->json('request_payload')->nullable();

            $table->json('response_payload')->nullable();

            $table->timestamp('sent_at')->nullable();

            $table->unsignedInteger('retry_count')->default(0);

            $table->text('last_error')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_orders');
    }
};
