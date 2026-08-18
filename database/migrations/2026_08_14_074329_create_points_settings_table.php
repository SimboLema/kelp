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
        Schema::create('points_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('points_per_amount_unit'); // e.g. 1 point
            $table->unsignedInteger('amount_unit_tzs');         // per this many TZS spent, e.g. 10,000
            $table->unsignedInteger('referral_points');          // flat points when a referred user's first purchase completes
            $table->decimal('redemption_rate_tzs_per_point', 10, 2); // cash value of 1 point
            $table->unsignedInteger('min_redeemable_points')->default(0);
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_settings');
    }
};
