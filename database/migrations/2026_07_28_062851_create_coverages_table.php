<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coverages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('risk_name');

            $table->string('risk_code')->nullable();

            $table->decimal('rate',12,2)->nullable();

            $table->decimal('minimum_amount',12,2)->nullable();

            $table->decimal('tpp',12,2)->nullable();

            $table->decimal('additional_amount',12,2)->nullable();

            $table->decimal('per_seat',12,2)->nullable();

            $table->string('coverage_type')->nullable();

            $table->string('sub_class')->nullable();

            $table->json('parameters')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coverages');
    }
};
