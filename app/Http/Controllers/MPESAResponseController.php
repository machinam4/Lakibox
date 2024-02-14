<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class MPESAResponseController extends Controller
{
    public function confirmation(Request $request)
    {
        // $data = json_decode($request->getContent());
        // Log::info('confirmation hit');
        // Log::info($request->all());
        Deposits::Create($request->all());

        return "success";
    }
    public function validation(Request $request)
    {
        // Log::info('validation hit');
        // Log::info($request->all());       
        return  [
            "ResultCode" => 0,
            "ResultDesc" => "Accept Service"
        ];
    }

    public function express(Request $request)
    {
        // Log::info('validation hit');
        Log::alert($request->all());
        $data = $request->all();
        $stkCallback = $data["Body"]["stkCallback"];
        $CallbackMetadata = $stkCallback["CallbackMetadata"]["Item"];
        // dd($stkCallback["ResultCode"]);
        if ($stkCallback["ResultCode"] !== 0) {
            return  [
                "ResultCode" => "failed",
                "ResultDesc" => "Accept Service"
            ];
        }
        // $result = 
        // dd($result);
        try {
            $bet = Deposits::updateOrCreate(
                [
                    "MerchantRequestID" => $stkCallback["MerchantRequestID"],
                    "CheckoutRequestID" => $stkCallback["CheckoutRequestID"],
                ],
                [
                    "ResultCode" => $stkCallback["ResultCode"],
                    // "TransactionType" => "",
                    "TransID" => $CallbackMetadata[1]["Value"],
                    "TransTime" => $CallbackMetadata[3]["Value"],
                    "TransAmount" => $CallbackMetadata[0]["Value"],
                    // "BusinessShortCode" => "",
                    // "BillRefNumber" => "",
                    // "InvoiceNumber" => "",
                    // "OrgAccountBalance" => "",
                    // "ThirdPartyTransID" => "",
                    "MSISDN" => $CallbackMetadata[4]["Value"],
                    // "FirstName" => "",
                    // "MiddleName" => "",
                    // "LastName" => "",
                ]
            );

            $BETS = new BetsController;
            $bet_result = $BETS->placeBet($bet->BillRefNumber);
            $sms = "Umepoteza!\n**\nUlichagua $bet->BillRefNumber\n**\nBox 1- " . $bet_result['box1'] . "\nBox 2- " . $bet_result['box2'] . "\nBox 3- " . $bet_result['box3'] . "\nBox 4- " . $bet_result['box4'] . "\nBox 5- " . $bet_result['box5'] . "\n**\n**\nChagua tena USHINDE.\nSTOP?*456*9*5#";
            $SMS = new SMSController;
            $sendSMS = $SMS->sendSMS($sms, $bet->MSISDN);
        } catch (\Throwable $th) {
            return $th;
        }

        return  [
            "ResultCode" => 0,
            "ResultDesc" => "Accept Service"
        ];
    }
}
