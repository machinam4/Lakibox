<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SMSController extends Controller
{
    public function sendSMS($msg, $phone)
    {
        $url = "http://user.smsmobivas.co.ke/api/v2/SendBulkSMS";

        $payload = [
            'senderId' => '25250',
            'messageParameters' => [
                [
                    'number' => $phone,
                    'text' => $msg,
                ]
            ],
            'apiKey' => '3/+FWMan4SO5osPJ/LU6Q9swTpYDABVJ0cxngSS80kc=',
            'clientId' => 'fae202b7-9134-43db-a2f0-4a8f6ec8ed62'
        ];
        // Log::info($payload);

        // Convert the payload to JSON
        $jsonPayload = json_encode($payload);

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: text/plain',
        ]);

        // Execute cURL session
        $response = curl_exec($ch);
        // Log::info($response);

        // Check for cURL errors
        if (curl_errno($ch)) {
            return response()->json([
                'error' => curl_error($ch),
            ], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Process the response
        return response()->json([
            'response' => $response,
        ]);
    }
}
