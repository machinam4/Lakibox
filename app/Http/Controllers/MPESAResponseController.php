<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESAResponseController extends Controller
{
    public function confirmation(Request $request)
    {
        // $data = json_decode($request->getContent());
        // Log::info('confirmation hit');
        // Log::info($request->all());
        Players::Create($request->all());

        return 'success';
    }

    public function validation(Request $request)
    {
        // Log::info('validation hit');
        // Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
        ];
    }

    public function express(Request $request)
    {
        // Log::info('validation hit');
        // Log::alert($request->all());
        $data = $request->all();
        $stkCallback = $data['Body']['stkCallback'];
        if ($stkCallback['ResultCode'] !== 0) {
            return [
                'ResultCode' => 'failed',
                'ResultDesc' => 'Accept Service',
            ];
        }
        $CallbackMetadata = $stkCallback['CallbackMetadata']['Item'];
        // dd($stkCallback["ResultCode"]);
        // $result =
        // dd($result);
        try {
            $bet = Deposits::updateOrCreate(
                [
                    'MerchantRequestID' => $stkCallback['MerchantRequestID'],
                    'CheckoutRequestID' => $stkCallback['CheckoutRequestID'],
                ],
                [
                    'ResultCode' => $stkCallback['ResultCode'],
                    // "TransactionType" => "",
                    'TransID' => $CallbackMetadata[1]['Value'],
                    'TransTime' => $CallbackMetadata[3]['Value'],
                    'TransAmount' => $CallbackMetadata[0]['Value'],
                    // "BusinessShortCode" => "",
                    // "BillRefNumber" => "",
                    // "InvoiceNumber" => "",
                    // "OrgAccountBalance" => "",
                    // "ThirdPartyTransID" => "",
                    'MSISDN' => $CallbackMetadata[4]['Value'],
                    // "FirstName" => "",
                    // "MiddleName" => "",
                    // "LastName" => "",
                ]
            );

            $BETS = new BetsController;
            $bet_result = $BETS->placeBet($bet->BillRefNumber, $bet->platform);
            // Log::info($bet_result);
            if ($bet_result['status'] == 'win') {
                $sms = "Hongera!\n**\nUlichagua $bet->BillRefNumber\n**\nBox 1- ".$bet_result['values']['box1']."\nBox 2- ".$bet_result['values']['box2']."\nBox 3- ".$bet_result['values']['box3']."\nBox 4- ".$bet_result['values']['box4']."\nBox 5- ".$bet_result['values']['box5']."\n**\n**\nPiga ".$bet->platform->incoming->shortcode.' Kujaribu tena.';
                // send winnings
                $WINS = new WithdrawalController;
                $sendWinnings = $WINS->b2cPaymentRequest($bet->MSISDN, $bet_result['amount_won'], $bet->platform->id);

                // Log::info('wins');

                // send results
                // $SMS = new LidenController;
                $SMS = new OnfonSmsController;
                $smssend = $SMS->sendSMS($sms, $bet->MSISDN, $bet->platform->outgoing);
            } else {
                // Log::info('lost');
                $sms = "Umepoteza!\n**\nUlichagua $bet->BillRefNumber\n**\nBox 1- ".$bet_result['values']['box1']."\nBox 2- ".$bet_result['values']['box2']."\nBox 3- ".$bet_result['values']['box3']."\nBox 4- ".$bet_result['values']['box4']."\nBox 5- ".$bet_result['values']['box5']."\n**\n**\nPiga ".$bet->platform->incoming->shortcode.' Kujaribu tena.';
                // $SMS = new LidenController;
                $SMS = new OnfonSmsController;
                $smssend = $SMS->sendSMS($sms, $bet->MSISDN, $bet->platform->outgoing);
            }

        } catch (\Throwable $th) {
            return $th;
        }

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
        ];
    }
}
