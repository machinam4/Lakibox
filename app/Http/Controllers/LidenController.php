<?php

namespace App\Http\Controllers;

use App\Providers\MessagingServiceProvider;

class LidenController extends Controller
{
    // protected $messagingService;

    // public function __construct(MessagingServiceProvider $messagingService)
    // {
    //     $this->messagingService = $messagingService;
    // }

    public function LidensendSMS($message, $phone)
    {
        $messagingService = new MessagingServiceProvider;
        // Authenticate user
        $authResponse = $messagingService->authenticateUser('758309015', 'Skyline@2030'); //authentication string

        if ($authResponse['code'] !== 'Success') {
            // Handle authentication error
            return response()->json(['error' => 'Authentication failed'], 401);
        }
        // return response()->json($authResponse);

        $accessToken = $authResponse['data']['data']['token'];

        // Send message
        $messageResponse = $messagingService->sendMessage($accessToken, $message, $phone);

        // Handle message sending response
        return response()->json($messageResponse);
    }

    public function sendSMS($message, $phone)
    {
        $curl = curl_init();

        // Prepare the data as an associative array
        $data = [
            'mobile' => $phone,
            'response_type' => 'json',
            'sender_name' => 'BULK_TECHY',
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
        }

        curl_close($curl);

        // Return the response
        return $response;
    }
}
