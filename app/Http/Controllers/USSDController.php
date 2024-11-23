<?php

namespace App\Http\Controllers;

use App\Models\Platforms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class USSDController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        $message = $data['ussdString'] ?? null;
        $phoneNumber = $data['msisdn'];
        $sessionId = $data['sessionId'];
        $sms_shortcode = urldecode($data['serviceCode']);
        // Log::info($sms_shortcode);

        $platform = Platforms::whereHas('incoming', function ($query) use ($sms_shortcode) {
            $query->where('shortcode', $sms_shortcode);
        })->first();

        if ($platform) {//if platform in db
            if ($message) {
                $inputs = explode('*', urldecode($message));
                $message = end($inputs); // Safely get the last value
            } else {
                $message = null;
            }

            // Retrieve or initialize the session state
            $sessionState = Cache::get("ussd_session_state_{$sessionId}", 'start');

            // Log::info('session state: '.$sessionState);

            // Step 1: Welcome message and box selection
            if (is_null($message) && $sessionState === 'start') {
                Cache::put("ussd_session_state_{$sessionId}", 'select_box', now()->addMinutes(5)); //expires the already initialized session after five minutes if its inactive

                $sms = "CON SHINDA mpaka 500,000!\n**\nCHAGUA BOX MOJA.\n**\nBox 1\nBox 2\nBox 3\nBox 4\nBox 5\n**\nChomoka na PESA USHINDE sasa hivi!";

                return response($sms);

                // Step 2: Box selection
            } elseif (preg_match("/^(box\s?[1-5]|^[1-5])$/i", $message, $matches) && $sessionState === 'select_box') {

                // Extract and convert the integer part
                if (preg_match("/(\d+)/", $matches[0], $intMatches)) {
                    $box = (int) $intMatches[0];
                }

                $sms = "END Umechagua Box: $box.\nUjumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao.";

                $DEPOSIT = new BetsController;
                $funds = $DEPOSIT->depositfund($box, $phoneNumber, $platform);

                //clear cache
                Cache::forget("ussd_session_state_{$sessionId}");

                return response($sms);

            } else {
                // Respond to the SMS
                $sms = "CON Umekosea!.\n**\nUlichagua $message.\n**\nCheza kwa kuchagua NUMBER (1-5).\n**\nMfano: 1\n**\nChagua TENA USHINDE!\n1:BOX 1\n2:BOX 2\n3:BOX 3\n4:BOX4\n5:BOX5\n**\**\nACC Bal: 0!";

                return response($sms);
            }
        } else {

            return response('END REQUEST FAILED');
        }

    }

    public function handle245(Request $request)
    {
        $data = $request->all();

        // Log::info($data);

        $message = $data['USSD_STRING'] ?? null;
        $phoneNumber = $data['MSISDN'];
        $sessionId = $data['SESSION_ID'];
        $sms_shortcode = urldecode($data['SERVICE_CODE']);
        // Log::info($sms_shortcode);

        $platform = Platforms::whereHas('incoming', function ($query) use ($sms_shortcode) {
            $query->where('shortcode', $sms_shortcode);
        })->first();

        if ($platform) {//if platform in db

            if ($message) {
                $inputs = explode('*', urldecode($message));
                $message = end($inputs); // Safely get the last value
            } else {
                $message = null;
            }

            // Retrieve or initialize the session state
            $sessionState = Cache::get("ussd_session_state_{$sessionId}", 'start');

            // Log::info('session state: '.$sessionState);

            // Step 1: Welcome message and box selection
            if (is_null($message) && $sessionState === 'start') {
                Cache::put("ussd_session_state_{$sessionId}", 'select_box');

                $sms = "CON SHINDA mpaka 500,000!\n**\nCHAGUA BOX MOJA.\n**\nBox 1\nBox 2\nBox 3\nBox 4\nBox 5\n**\nChomoka na PESA USHINDE sasa hivi!";

                return response($sms);

                // Step 2: Box selection
            } elseif (preg_match("/^(box\s?[1-5]|^[1-5])$/i", $message, $matches) && $sessionState === 'select_box') {

                // Extract and convert the integer part
                if (preg_match("/(\d+)/", $matches[0], $intMatches)) {
                    $box = (int) $intMatches[0];
                }

                // $box = (int) filter_var($matches[0], FILTER_SANITIZE_NUMBER_INT);

                Cache::put("ussd_session_state_{$sessionId}", 'input_stake');
                Cache::put("ussd_session_box_choice_{$sessionId}", $box);

                $sms = 'CON Umechagua Box '.$box."\nWeka stake yako (Min. $platform->bet_minimum; Max $platform->bet_maximum) kushiriki: ";

                return response($sms);

                // Step 3: Request stake amount
            } elseif ($sessionState === 'input_stake' && is_numeric($message)) {
                $stakeAmount = (float) $message;

                if ($stakeAmount < $platform->bet_minimum || $stakeAmount > $platform->bet_maximum) {
                    $response = "CON Kiasi cha stake lazima kiwe zaidi KES $platform->bet_minimum na chini ya $platform->bet_maximum.\nJaribu tena:";

                    return response($response);
                }

                // Cache::put("ussd_session_state_{$sessionId}", 'confirm_choice');
                Cache::put("ussd_session_stake_amount_{$sessionId}", $stakeAmount);

                $boxChoice = Cache::get("ussd_session_box_choice_{$sessionId}");

                $sms = "END Umechagua Box: $boxChoice \n Stake: $stakeAmount.\nUjumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao.";

                $DEPOSIT = new BetsController;
                $funds = $DEPOSIT->depositfund($boxChoice, $phoneNumber, $platform, $stakeAmount);

                //clear cache
                Cache::forget("ussd_session_state_{$sessionId}");
                Cache::forget("ussd_session_box_choice_{$sessionId}");
                Cache::forget("ussd_session_stake_amount_{$sessionId}");

                return response($sms);

            } else {
                // If the keyword "Box" is not found, provide a generic response
                // Log::info('Received SMS without keyword "Box": ' . $message);
                // Respond to the SMS
                $sms = "CON Umekosea!.\n**\nUlichagua $message.\n**\nCheza kwa kuchagua NUMBER (1-5).\n**\nMfano: 1\n**\nChagua TENA USHINDE!\n1:BOX 1\n2:BOX 2\n3:BOX 3\n4:BOX4\n5:BOX5\n**\**\nACC Bal: 0!";

                return response($sms);
            }

        } else {

            return response('END REQUEST FAILED');
            // $sms = 'Ujumbe wa M-Pesa utatumwa kwenye simu yako muda mfupi ujao. Tafadhali thibitisha malipo ya KES 30 ili kushiriki.';

            // return response()->json([
            //     'result_message' => $sms,
            //     'result_code' => 0,
            // ]);
        }
    }
}
