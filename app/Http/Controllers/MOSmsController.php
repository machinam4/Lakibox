<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MOSmsController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        $message = $data['message'];
        $phoneNumber = $data['phone'];
        Log::info($message);
        // Check if the message contains the keyword "Box"
        $box = strtolower($message); // Convert to lowercase if needed
        if ($box == 'box') {
            // Perform actions based on the content of the message
            // You can customize this part to perform any specific actions you need.
            Log::info('Received SMS without keyword "Box": ' . $message);
            // Respond to the SMS
            $sms = "Karibu LAKIBOX!\n**\nPESA TASLIMU na MALI KEMKEM zimewekwa kwenye sanduku TANO.\n**\nBox 1\nBox 2\nBox 3\nBox 4\nBox 5\n**\nChomoka na PESA au MALI.Tuma chaguo lako kwa 25250 USHINDE sasa hivi!\n**\nHelp: 076******3\nSTOP?*456*9*5#";
            $SMS = new SMSController;
            $sendSMS = $SMS->sendSMS($sms, $phoneNumber);
        } else if (preg_match("/^(box\s?[1-5]|^[1-5])$/i", $box, $matches)) { // Use a regular expression to match "box 1" to "box 5" or values from 1 to 5 in a case-insensitive way

            // Extract and convert the integer part
            if (preg_match("/(\d+)/", $matches[0], $intMatches)) {
                $intValue = (int)$intMatches[0];
            }
            //    echo $intValue; // Output: "3"

            $DEPOSIT = new BetsController;
            $funds = $DEPOSIT->depositfund($intValue, $phoneNumber);
        } else {
            // If the keyword "Box" is not found, provide a generic response
            Log::info('Received SMS without keyword "Box": ' . $message);
            // Respond to the SMS
            $sms = "Samahani! Umekosea.\n**\nUlichagua $message.\n**\nCheza kwa kuchagua NUMBER (1-5).\n**\nMfano: 1\n**\nChagua TENA USHINDE!\n1:BOX 1\n2:BOX 2\n3:BOX 3\n4:BOX4\n5:BOX5\n**\**\nCheza Tena  uingie kwa DRAW ya leo!\nSTOP*456*9*5#\n";
            $SMS = new SMSController;
            $sendSMS = $SMS->sendSMS($sms, $phoneNumber);
        }

        return response()->json("its okay", 200);
    }
}
