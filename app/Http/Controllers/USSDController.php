<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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

        // Retrieve or initialize the session state
        $sessionState = Session::get("ussd_session_state_{$sessionId}", 'start');

        // Step 1: Welcome message and box selection
        if (is_null($message) && $sessionState === 'start') {
            Session::put("ussd_session_state_{$sessionId}", 'select_box');

            $sms = "CON Karibu LUCKYBOX!\n**\nCHAGUA BOX MOJA.\n**\nBox 1\nBox 2\nBox 3\nBox 4\nBox 5\n**\nChomoka na PESA USHINDE sasa hivi!";

            return response($sms);

            // Step 2: Box selection
        } elseif ($sessionState === 'select_box' && preg_match("/^(box\s?[1-5]|^[1-5])$/i", $message, $matches)) {
            $box = (int) filter_var($matches[0], FILTER_SANITIZE_NUMBER_INT);

            Session::put("ussd_session_state_{$sessionId}", 'input_stake');
            Session::put("ussd_session_box_choice_{$sessionId}", $box);

            $sms = 'CON Umechagua Box '.$box.'\nTafadhali weka kiasi unachotaka kucheza (Stake) katika KES:';

            return response($sms);

            // Step 3: Request stake amount
        } elseif ($sessionState === 'enter_stake' && is_numeric($message)) {
            $stakeAmount = (float) $message;

            if ($stakeAmount < 40) {
                $response = "CON Kiasi cha stake lazima kiwe angalau KES 40.\nTafadhali jaribu tena:";

                return response($response);
            }

            Session::put("ussd_session_state_{$sessionId}", 'confirm_choice');
            Session::put("ussd_session_stake_amount_{$sessionId}", $stakeAmount);

            $boxChoice = Session::get("ussd_session_box_choice_{$sessionId}");

            $sms = "END Umechagua Box $boxChoice na kiasi cha KES $stakeAmount.\nUjumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao.";

            $DEPOSIT = new BetsController;
            $funds = $DEPOSIT->depositfund($boxChoice, $phoneNumber, $sms_shortcode);

            return response($sms);

        } else {
            // If the keyword "Box" is not found, provide a generic response
            // Log::info('Received SMS without keyword "Box": ' . $message);
            // Respond to the SMS
            $sms = "CON Umekosea!.\n**\nUlichagua $message.\n**\nCheza kwa kuchagua NUMBER (1-5).\n**\nMfano: 1\n**\nChagua TENA USHINDE!\n1:BOX 1\n2:BOX 2\n3:BOX 3\n4:BOX4\n5:BOX5\n**\**\nACC Bal: 0!";

            return response($sms);
        }

        return response('END REQUEST FAILED');
        // $sms = 'Ujumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao. Tafadhali thibitisha malipo ya KES 30 ili kushiriki.';

        // return response()->json([
        //     'result_message' => $sms,
        //     'result_code' => 0,
        // ]);
    }
}
