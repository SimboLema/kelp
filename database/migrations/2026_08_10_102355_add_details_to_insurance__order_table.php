<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {

            $table->json('customer_details')->nullable();

            // Full vehicle details (from verifyMotor()) — same reasoning.
            $table->json('motor_details')->nullable()->after('customer_details');

            $table->date('cover_note_start_date')->nullable()->after('motor_details');
            $table->date('cover_note_end_date')->nullable()->after('cover_note_start_date');

            $table->string('payment_mode')->default('cash')->after('cover_note_end_date'); // 'cash' | 'ipf'

            $table->string('registration_number')->nullable()->index()->after('payment_mode');
        });
    }

    public function down(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_details',
                'motor_details',
                'cover_note_start_date',
                'cover_note_end_date',
                'payment_mode',
                'registration_number',
            ]);
        });
    }
};
