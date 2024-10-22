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
        Schema::create('b2c_requests', function (Blueprint $table) {
            $table->id();
            $table->string('originator_conversation_id');
            $table->string('conversation_id')->nullable();
            $table->string('response_code')->nullable();
            $table->text('response_description')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->float('amount', 8, 2)->nullable();
            $table->timestamp('transaction_timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2c_requests');
    }
};
