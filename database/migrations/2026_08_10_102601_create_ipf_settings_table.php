<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipf_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('down_payment_percent', 5, 2);
            $table->decimal('daily_rate_percent', 5, 2);
            $table->decimal('penalty_percent', 5, 2);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipf_settings');
    }
};
