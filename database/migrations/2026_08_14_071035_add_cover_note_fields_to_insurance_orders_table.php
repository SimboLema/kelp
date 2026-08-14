<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->string('cover_note_reference')->nullable();
            $table->string('cover_note_pdf_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('insurance_orders', function (Blueprint $table) {
            $table->dropColumn(['cover_note_reference', 'cover_note_pdf_url']);
        });
    }
};
