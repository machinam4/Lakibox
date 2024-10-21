<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MPESAResponseController extends Controller
{
    public function sendSMS($message, $phone)
    {
        $curl = curl_init();

        // Prepare the data as an associative array
        $data = [
            'mobile' => $phone,
            'response_type' => 'json',
            'sender_name' => '24119',
            'service_id' => 0,
            'message' => $message,
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.bulk.ke/sms/sendsms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),  // Encode the array to JSON
            CURLOPT_HTTPHEADER => [
                'h_api_key: 436534bbd9ef6ab943998c8a73ec15ff64bda81f50b7c27fddd39044841308e0',
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // Log the error message
            echo 'Error:'.curl_error($curl);
            Log::error('Error:'.curl_error($curl));
        }

        curl_close($curl);
        Log::info($response);

        // Return the response
        return $response;
    }

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
        Log::info('validation hit');
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
            $bet_result = $BETS->placeBet($bet->BillRefNumber);
            $sms = "Umepoteza!\n**\nUlichagua $bet->BillRefNumber\n**\nBox 1- ".$bet_result['box1']."\nBox 2- ".$bet_result['box2']."\nBox 3- ".$bet_result['box3']."\nBox 4- ".$bet_result['box4']."\nBox 5- ".$bet_result['box5']."\n**\n**\nChagua tena USHINDE.\nSTOP?*456*9*5#";
            // $SMS = new LidenController;
            $smssend = $this->sendSMS($sms, $bet->MSISDN);
        } catch (\Throwable $th) {
            return $th;
        }

        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
        ];
    }
}
