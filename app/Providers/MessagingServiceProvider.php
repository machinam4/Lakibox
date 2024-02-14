<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;

class MessagingServiceProvider
{
    public function authenticateUser($username, $password)
    {
        $response = Http::post('https://app.apiproxy.co/account/v1/grant_access', [
            'userName' => $username,
            'password' => $password,
            'apigw' => 'API_GW',
            'countryCode' => '254',
        ]);

        return $response->json();
    }

    public function sendMessage($accessToken, $message, $recipient)
    {
        $response = Http::withHeaders([
            'X-Authorization-Key' => $accessToken,
            'X-Requested-with' => 'XMLHttpRequest',
            'Content-Type' => 'application/json',
        ])->post('https://app.apiproxy.co/sms/v1/bulk/api_create', [
            'callbackURL' => 'https://api.test.com/dlr/686153',
            'enqueue' => 1,
            'message' => $message,
            'recipient' => $recipient,
            'shortCode' => '23367',
            'uniqueId' => time(), // or any unique identifier
        ]);

        return $response->json();
    }
}
