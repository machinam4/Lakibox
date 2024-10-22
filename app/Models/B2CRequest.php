<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2CRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'originator_conversation_id',
        'conversation_id',
        'response_code',
        'response_description',
        'recipient_phone',
        'amount',
        'transaction_timestamp',
    ];
}
