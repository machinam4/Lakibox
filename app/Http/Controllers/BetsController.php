<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use Illuminate\Support\Facades\Log;

class BetsController extends Controller
{
    public function depositfund($box, $phoneNumber)
    {
        // stk push
        $timestamp = now()->setTimezone('UTC')->format('YmdHis');
        $data = [
            'BusinessShortCode' => env('MPESA_SHORTCODE'),
            'Password' => base64_encode(env('MPESA_SHORTCODE').env('MPESA_PASSKEY').$timestamp),
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => env('GAME_AMOUNT'),
            'PartyA' => $phoneNumber,
            'PartyB' => env('MPESA_SHORTCODE'),
            'PhoneNumber' => $phoneNumber,
            'CallBackURL' => url('').'/api/c2b/express',
            'AccountReference' => "Box $box",
            'TransactionDesc' => 'Lucky Box '.$box,
        ];
        // dd($data);
        // dd(response()->json($data, 200));

        // TO:DO wait for mpesa to finish transaction the send notifi to user

        try {
            $sendStk = new DarajaApiController;
            $response = $sendStk->STKPush($data);
            // Log::info(json_encode($response));
            if ($response->ResponseCode !== '0') {
                return response()->json('failed', 200);
            } else {
                $res_data = [
                    // "ResultCode" => $response->ResultCode,
                    'MerchantRequestID' => $response->MerchantRequestID,
                    'CheckoutRequestID' => $response->CheckoutRequestID,
                    'TransactionType' => 'CustomerPayBillOnline',
                    'BusinessShortCode' => env('MPESA_SHORTCODE'),
                    'BillRefNumber' => "Box $box",
                    'MSISDN' => $phoneNumber,
                ];
                // dd($res_data);
                Deposits::Create($res_data);
                // Log::info('stk sent');
            }

            // session()->flush();
            // dd($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // public function placeBet(string $box)
    // {
    //     $prizes = [
    //         number_format(round(random_int(50000, 100000), -4)),
    //         number_format(round(random_int(100000, 199999), -3)),
    //         number_format(round(random_int(200000, 299999), -3)),
    //         number_format(round(random_int(300000, 399999), -3)),
    //         number_format(round(random_int(400000, 450000), -3)),
    //         // 'SmartPhone',
    //         // 'Motorbike',
    //         // // number_format(random_int(100, 1000)) . " Voucher",
    //         // 'Smart TV',
    //         // 'Water Dispenser',
    //         // 'Standing Cooker',
    //         number_format(0),
    //     ];
    //     // send response
    //     switch ($box) {
    //         case 'Box 1':
    //             $values = [
    //                 'box1' => number_format(0),
    //                 'box2' => $prizes[array_rand($prizes)],
    //                 'box3' => $prizes[array_rand($prizes)],
    //                 'box4' => $prizes[array_rand($prizes)],
    //                 'box5' => $prizes[array_rand($prizes)],
    //                 'box6' => $prizes[array_rand($prizes)],
    //             ];

    //             return $values;
    //             break;
    //         case 'Box 2':
    //             $values = [
    //                 'box1' => $prizes[array_rand($prizes)],
    //                 'box2' => number_format(0),
    //                 'box3' => $prizes[array_rand($prizes)],
    //                 'box4' => $prizes[array_rand($prizes)],
    //                 'box5' => $prizes[array_rand($prizes)],
    //                 'box6' => $prizes[array_rand($prizes)],
    //             ];

    //             return $values;
    //             break;
    //         case 'Box 3':
    //             $values = [
    //                 'box1' => $prizes[array_rand($prizes)],
    //                 'box2' => $prizes[array_rand($prizes)],
    //                 'box3' => number_format(0),
    //                 'box4' => $prizes[array_rand($prizes)],
    //                 'box5' => $prizes[array_rand($prizes)],
    //                 'box6' => $prizes[array_rand($prizes)],
    //             ];

    //             return $values;
    //             break;
    //         case 'Box 4':
    //             $values = [
    //                 'box1' => $prizes[array_rand($prizes)],
    //                 'box2' => $prizes[array_rand($prizes)],
    //                 'box3' => $prizes[array_rand($prizes)],
    //                 'box4' => number_format(0),
    //                 'box5' => $prizes[array_rand($prizes)],
    //                 'box6' => $prizes[array_rand($prizes)],
    //             ];

    //             return $values;
    //             break;
    //         case 'Box 5':
    //             $values = [
    //                 'box1' => $prizes[array_rand($prizes)],
    //                 'box2' => $prizes[array_rand($prizes)],
    //                 'box3' => $prizes[array_rand($prizes)],
    //                 'box4' => $prizes[array_rand($prizes)],
    //                 'box5' => number_format(0),
    //                 'box6' => $prizes[array_rand($prizes)],
    //             ];

    //             return $values;
    //             break;
    //         case 'Box 6':
    //             $values = [
    //                 'box1' => $prizes[array_rand($prizes)],
    //                 'box2' => $prizes[array_rand($prizes)],
    //                 'box3' => $prizes[array_rand($prizes)],
    //                 'box4' => $prizes[array_rand($prizes)],
    //                 'box5' => $prizes[array_rand($prizes)],
    //                 'box6' => number_format(0),
    //             ];

    //             return $values;
    //             break;

    //         default:
    //             $values = [
    //                 'box1' => '!!ERROR!!',
    //                 'box2' => '!!ERROR!!',
    //                 'box3' => '!!ERROR!!',
    //                 'box4' => '!!ERROR!!',
    //                 'box5' => '!!ERROR!!',
    //                 'box6' => '!!ERROR!!',
    //             ];

    //             return $values;
    //             break;
    //     }
    //     // session(['isValid' => '1']);
    //     // return response()->json(['login' => 'accepted'], 200);
    // }

    public function placeBet(string $box)
    {
        $prizes = [
            number_format(round(random_int(50000, 100000), -4)),
            number_format(round(random_int(100000, 199999), -3)),
            number_format(round(random_int(200000, 299999), -3)),
            number_format(round(random_int(300000, 399999), -3)),
            number_format(round(random_int(400000, 450000), -3)),
            number_format(0), // Default 0 prize
        ];

        // Extract the selected box number (e.g., "Box 1" -> 1)
        $selectedBox = (int) filter_var($box, FILTER_SANITIZE_NUMBER_INT);

        // Initialize the box values with random prizes
        $values = [];
        for ($i = 1; $i <= 6; $i++) {
            $values["box$i"] = $prizes[array_rand($prizes)];
        }

        // Step 1: Determine if the player wins
        $isWin = mt_rand(1, 100) <= 7; // 10% chance to win

        if ($isWin) {
            // Generate the win amount and place it in the selected box
            $winAmount = $this->generateWinAmount();
            $values["box$selectedBox"] = number_format($winAmount);

            return [
                'status' => 'win',
                'message' => "Congratulations! You won KES $winAmount.",
                'amount_won' => $winAmount,
                'values' => $values,
            ];
        } else {
            // If the player loses, set the selected box value to 0
            $values["box$selectedBox"] = number_format(0);

            return [
                'status' => 'lose',
                'message' => 'Sorry, you lost. Better luck next time!',
                'amount_won' => 0,
                'values' => $values,
            ];
        }
    }

    // Helper function to generate the winning amount
    private function generateWinAmount()
    {
        // 80% chance the win amount is below 500, 20% chance between 500 and 999
        return mt_rand(1, 100) <= 80 ? mt_rand(30, 100) : mt_rand(101, 299);
    }
}
