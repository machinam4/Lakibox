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
        Schema::create('b2c_responses', function (Blueprint $table) {
            $table->id();
            $table->string('originator_conversation_id')->nullable();
            $table->string('conversation_id')->nullable();
            $table->integer('result_code')->nullable();
            $table->decimal('transaction_amount', 10, 2)->nullable();
            $table->string('transaction_receipt')->nullable();
            $table->boolean('b2c_recipient_is_registered_customer')->nullable();
            $table->decimal('b2c_charges_paid_account_available_funds', 15, 2)->nullable();
            $table->string('receiver_party_public_name')->nullable();
            $table->timestamp('transaction_completed_datetime')->nullable();
            $table->decimal('b2c_utility_account_available_funds', 15, 2)->nullable();
            $table->decimal('b2c_working_account_available_funds', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2c_responses');
    }
};
