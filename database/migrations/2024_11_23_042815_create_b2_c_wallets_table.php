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
        Schema::create('b2_c_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('shortcode');
            $table->string('initiator');
            $table->text('SecurityCredential');
            $table->string('key');
            $table->string('secret');
            $table->string('passkey')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2_c_wallets');
    }
};