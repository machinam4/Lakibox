<?php

namespace App\Http\Controllers;

use App\Providers\MessagingServiceProvider;
use Illuminate\Http\Request;

class LidenController extends Controller
{
    // protected $messagingService;

    // public function __construct(MessagingServiceProvider $messagingService)
    // {
    //     $this->messagingService = $messagingService;
    // }

    public function sendSMS($message, $phone)
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
}
