<?php

namespace App\Http\Controllers;

class OnfonSmsController extends Controller
{
    // bulk.ke sms sending
    public function sendSMS($message, $phone, $shortcode)
    {
        $curl = curl_init();

        // Prepare the data as an associative array
        $data = [
            'SenderId' => $shortcode, // Replace with your sender ID
            'IsUnicode' => true,
            'IsFlash' => true,
            // 'ScheduleDateTime' => 'string', // Replace with your date and time in proper format
            'MessageParameters' => [
                [
                    'Number' => $phone, // Replace with the actual number
                    'Text' => $message,       // Replace with the actual message text
                ],
            ],
            'ApiKey' => '5HBZ8e0C7DkbINqlvoUwFOYGaWzSxTdAu23Es46c1gpJM9yh', // Replace with your API key
            'ClientId' => 'emart', // Replace with your client ID
        ];
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.onfonmedia.co.ke/v1/sms/SendBulkSMS',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),  // Encode the array to JSON
            CURLOPT_HTTPHEADER => [
                'AccessKey: 5HBZ8e0C7DkbINqlvoUwFOYGaWzSxTdAu23Es46c1gpJM9yh',
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            // Log the error message
            echo 'Error:'.curl_error($curl);
            // Log::error('Error:'.curl_error($curl));
        }

        curl_close($curl);
        // Log::info($response);

        // Return the response
        return $response;
    }
}
