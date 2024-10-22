<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2CResponse extends Model
{
    use HasFactory;

    protected $table = 'b2c_responses';

    protected $fillable = [
        'originator_conversation_id',
        'conversation_id',
        'result_code',
        'transaction_amount',
        'transaction_receipt',
        'b2c_recipient_is_registered_customer',
        'b2c_charges_paid_account_available_funds',
        'receiver_party_public_name',
        'transaction_completed_datetime',
        'b2c_utility_account_available_funds',
        'b2c_working_account_available_funds',
    ];
}
