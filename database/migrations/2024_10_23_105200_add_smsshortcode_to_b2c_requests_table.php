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
        Schema::table('b2c_requests', function (Blueprint $table) {
            $table->string('SmsShortcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('b2c_requests', function (Blueprint $table) {
            $table->string('SmsShortcode')->nullable();
        });
    }
};
