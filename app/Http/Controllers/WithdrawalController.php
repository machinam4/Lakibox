<?php

namespace App\Http\Controllers;

use App\Models\B2CRequest;
use App\Models\B2CResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    public function generateAccessToken() //Active
    {
        // *** Authorization Request in PHP ***|
        $mpesaUrl = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $ch = curl_init($mpesaUrl);
        curl_setopt_array(
            $ch,
            [
                CURLOPT_HTTPHEADER => ['Content-Type:application/json; charset=utf8'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_USERPWD => env('B2C_CONSUMER_KEY').':'.env('B2C_CONSUMER_SECRET'),
            ]
        );
        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response->access_token;
    }

    public function sendRequest($mpesa_url, $curl_post_data)
    {
        $ch = curl_init($mpesa_url);
        curl_setopt($ch, CURLOPT_URL, $mpesa_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->generateAccessToken(), 'Content-Type: application/json']);
        $data_string = json_encode($curl_post_data, JSON_UNESCAPED_SLASHES);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Log::info($data_string);
        $curl_response = curl_exec($ch);
        curl_close($ch);

        // Log::info($curl_response);

        return $curl_response;
    }

    public function b2cPaymentRequest($phone, $amount)
    {
        $mpesaUrl = 'https://api.safaricom.co.ke/mpesa/b2c/v3/paymentrequest';
        $timestamp = now()->format('YmdHis');
        // Payload data to send with the request
        $data = [
            'OriginatorConversationID' => $timestamp,
            'InitiatorName' => 'EMART API',
            'SecurityCredential' => env('B2C_SECURITY_CREDENTIAL'),
            'CommandID' => 'PromotionPayment',
            'Amount' => $amount,
            'PartyA' => env('B2C_SHORTCODE'),
            'PartyB' => $phone,
            'Remarks' => 'winner box 2',
            'QueueTimeOutURL' => 'https://lakibox.ridhishajamii.com/api/b2c/queue',
            'ResultURL' => 'https://lakibox.ridhishajamii.com/api/b2c/result',
            // 'QueueTimeOutURL' => url('').'/api/b2c/queue',
            // 'ResultURL' => url('').'/api/b2c/result',
            'Occasion' => 'Winner Box',
        ];

        try {
            $response = json_decode($this->sendRequest($mpesaUrl, $data));

            if (isset($response->errorCode)) {
                // Log::info(json_encode($response));

                return response()->json('failed', 200);
            }
            if ($response->ResponseCode !== '0') {
                // Log::info(json_encode($response));

                return response()->json('failed', 200);
            } else {
                // Log::info(json_encode($response));

                // Parse the response and store it in the database
                $responseData = $response;

                if (isset($responseData->OriginatorConversationID)) {
                    B2CRequest::create([
                        'originator_conversation_id' => $responseData->OriginatorConversationID,
                        'conversation_id' => $responseData->ConversationID ?? null,
                        'response_code' => $responseData->ResponseCode ?? null,
                        'response_description' => $responseData->ResponseDescription ?? null,
                        'recipient_phone' => $data['PartyB'], // Phone number from the request
                        'amount' => $data['Amount'],         // Amount from the request
                        'transaction_timestamp' => now(),    // Save current timestamp
                    ]);
                }
            }

            // Return the response
            return response()->json($responseData);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    public function handleResult(Request $request)
    {
        // Log::info('B2C Result Callback:', $request->all());

        $data = $request->all();

        if (isset($data['Result'])) {
            $result = $data['Result'];

            if ($result['ResultCode'] !== 0) {
                B2CResponse::create([
                    'originator_conversation_id' => $result['OriginatorConversationID'],
                    'conversation_id' => $result['ConversationID'],
                    'transaction_id' => $result['TransactionID'],
                    'result_code' => $result['ResultCode'],
                    'result_desc' => $result['ResultDesc'],
                ]);

                $SMS = new LidenController;
                $AdminNotif = $SMS->sendSMS($result['ResultDesc'], 254758309015);

                return [
                    'ResultCode' => 'failed',
                    'ResultDesc' => 'Accept Service',
                ];
            }

            // Extract result parameters
            $parameters = collect($result['ResultParameters']['ResultParameter'])
                ->pluck('Value', 'Key');

            // Store the relevant data in the database
            B2CResponse::create([
                'originator_conversation_id' => $result['OriginatorConversationID'] ?? null,
                'conversation_id' => $result['ConversationID'] ?? null,
                'result_code' => $result['ResultCode'] ?? null,
                'result_desc' => $result['ResultDesc'],
                'transaction_id' => $result['TransactionID'],
                'transaction_amount' => $parameters->get('TransactionAmount'),
                'transaction_receipt' => $parameters->get('TransactionReceipt'),
                'b2c_recipient_is_registered_customer' => $parameters->get('B2CRecipientIsRegisteredCustomer'),
                'b2c_charges_paid_account_available_funds' => $parameters->get('B2CChargesPaidAccountAvailableFunds'),
                'receiver_party_public_name' => $parameters->get('ReceiverPartyPublicName'),
                'transaction_completed_datetime' => $parameters->get('TransactionCompletedDateTime'),
                'b2c_utility_account_available_funds' => $parameters->get('B2CUtilityAccountAvailableFunds'),
                'b2c_working_account_available_funds' => $parameters->get('B2CWorkingAccountAvailableFunds'),
            ]);

            return response()->json(['message' => 'B2C result processed successfully'], 200);
        }

        return response()->json(['error' => 'Invalid response format'], 400);

    }

    public function queue(Request $request)
    {
        // Log::info('validation hit');
        // Log::info($request->all());
        return [
            'ResultCode' => 0,
            'ResultDesc' => 'Accept Service',
        ];
    }
}
