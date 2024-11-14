<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class USSDController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        $message = $data['ussdString'];
        $phoneNumber = $data['msisdn'];
        $sessionId = $data['sessionId'];
        $sms_shortcode = $data['serviceCode'];
        Log::info($data);
        // Check if the message contains the keyword "Box"
        $box = $message; // Convert to lowercase if needed
        if (is_null($box)) {
            // Perform actions based on the content of the message
            // You can customize this part to perform any specific actions you need.
            // Log::info('Received SMS without keyword "Box": ' . $message);
            // Respond to the SMS
            Log::info('null box: '.$box);

            $sms = "CON Karibu LUCKYBOX!\n**\nCHAGUA BOX MOJA.\n**\nBox 1\nBox 2\nBox 3\nBox 4\nBox 5\n**\nChomoka na PESA USHINDE sasa hivi!";
            // $SMS = new SMSController;
            // $SMS = new LidenController;
            // $sendSMS = $SMS->sendSMS($sms, $phoneNumber);

            return response($sms);

            // return response()->json([
            //     'msisdn' => $phoneNumber,
            //     'sessionId' => $sessionId,
            //     'serviceCode' => $sms_shortcode,
            //     'ussdString' => $sms,
            // ]);
        } elseif (preg_match("/^(box\s?[1-5]|^[1-5])$/i", $box, $matches)) { // Use a regular expression to match "box 1" to "box 5" or values from 1 to 5 in a case-insensitive way

            $sms = 'END Ujumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao. Thibitisha malipo ya KES 40 ili kushiriki.';
            response($sms);
            // $SMS = new LidenController;
            // $sendSMS = $SMS->sendSMS($sms, $phoneNumber);

            // Extract and convert the integer part
            if (preg_match("/(\d+)/", $matches[0], $intMatches)) {
                $intValue = (int) $intMatches[0];
            }
            //    echo $intValue; // Output: "3"

            $DEPOSIT = new BetsController;
            $funds = $DEPOSIT->depositfund($intValue, $phoneNumber, $sms_shortcode);

        } else {
            // If the keyword "Box" is not found, provide a generic response
            // Log::info('Received SMS without keyword "Box": ' . $message);
            // Respond to the SMS
            $sms = "CON Umekosea!.\n**\nUlichagua $message.\n**\nCheza kwa kuchagua NUMBER (1-5).\n**\nMfano: 1\n**\nChagua TENA USHINDE!\n1:BOX 1\n2:BOX 2\n3:BOX 3\n4:BOX4\n5:BOX5\n**\**\nACC Bal: 0!";

            return response($sms);
        }

        return response()->json('its okay', 200);
        // $sms = 'Ujumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao. Tafadhali thibitisha malipo ya KES 30 ili kushiriki.';

        // return response()->json([
        //     'result_message' => $sms,
        //     'result_code' => 0,
        // ]);
    }
}
