<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    use HasFactory;
    protected $fillable = [
        "ResultCode",
        "MerchantRequestID",
        "CheckoutRequestID",
        "TransactionType",
        "TransID",
        "TransTime",
        "TransAmount",
        "BusinessShortCode",
        "BillRefNumber",
        "InvoiceNumber",
        "OrgAccountBalance",
        "ThirdPartyTransID",
        "MSISDN",
        "FirstName",
        "MiddleName",
        "LastName",
    ];

    public function player()
    {
        return $this->hasOne(Players::class, 'TransID', 'TransID');
    }
}
