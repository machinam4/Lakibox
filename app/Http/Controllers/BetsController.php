<?php

namespace App\Http\Controllers;

use App\Models\Deposits;
use Illuminate\Http\Request;

class BetsController extends Controller
{
    public function depositfund($box, $phoneNumber)
    {
        // stk push
        $timestamp = now()->format('YmdHis');
        $data = [
            "BusinessShortCode" => env('MPESA_SHORTCODE'),
            "Password" => base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . $timestamp),
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => env('GAME_AMOUNT'),
            "PartyA" => $phoneNumber,
            "PartyB" => env('MPESA_SHORTCODE'),
            "PhoneNumber" => $phoneNumber,
            "CallBackURL" =>  url('') . env('MPESA_CALLBACK'),
            "AccountReference" => "Box $box",
            "TransactionDesc" => "Lucky Box " . $box
        ];
        // dd($data);
        // dd(response()->json($data, 200));

        // TO:DO wait for mpesa to finish transaction the send notifi to user

        try {
            $sendStk = new DarajaApiController;
            $response = $sendStk->STKPush($data);
            // dd($response);
            if ($response->ResponseCode !== "0") {
                return response()->json("failed", 200);
            } else {
                $res_data = [
                    // "ResultCode" => $response->ResultCode,
                    "MerchantRequestID" => $response->MerchantRequestID,
                    "CheckoutRequestID" => $response->CheckoutRequestID,
                    "TransactionType" => "CustomerPayBillOnline",
                    "BusinessShortCode" => env('MPESA_SHORTCODE'),
                    "BillRefNumber" => "Box $box",
                ];
                // dd($res_data);
                Deposits::Create($res_data);
            }

            // session()->flush();
            // dd($response);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function placeBet(string $box)
    {
        $prizes = [
            number_format(round(random_int(50000, 100000), -4)),
            number_format(round(random_int(100000, 199999), -3)),
            number_format(round(random_int(200000, 299999), -3)),
            number_format(round(random_int(300000, 399999), -3)),
            number_format(round(random_int(400000, 450000), -3)),
            "SmartPhone",
            "Motorbike",
            // number_format(random_int(100, 1000)) . " Voucher",
            "Smart TV",
            "Water Dispenser",
            "Standing Cooker",
            number_format(0)
        ];
        // send response
        switch ($box) {
            case "Box 1":
                $values = [
                    'box1' => number_format(0),
                    'box2' => $prizes[array_rand($prizes)],
                    'box3' => $prizes[array_rand($prizes)],
                    'box4' => $prizes[array_rand($prizes)],
                    'box5' => $prizes[array_rand($prizes)],
                    'box6' => $prizes[array_rand($prizes)],
                ];
                return $values;
                break;
            case "Box 2":
                $values = [
                    'box1' => $prizes[array_rand($prizes)],
                    'box2' => number_format(0),
                    'box3' => $prizes[array_rand($prizes)],
                    'box4' => $prizes[array_rand($prizes)],
                    'box5' => $prizes[array_rand($prizes)],
                    'box6' => $prizes[array_rand($prizes)],
                ];
                return $values;
                break;
            case "Box 3":
                $values = [
                    'box1' => $prizes[array_rand($prizes)],
                    'box2' => $prizes[array_rand($prizes)],
                    'box3' => number_format(0),
                    'box4' => $prizes[array_rand($prizes)],
                    'box5' => $prizes[array_rand($prizes)],
                    'box6' => $prizes[array_rand($prizes)],
                ];
                return $values;
                break;
            case "Box 4":
                $values = [
                    'box1' => $prizes[array_rand($prizes)],
                    'box2' => $prizes[array_rand($prizes)],
                    'box3' => $prizes[array_rand($prizes)],
                    'box4' => number_format(0),
                    'box5' => $prizes[array_rand($prizes)],
                    'box6' => $prizes[array_rand($prizes)],
                ];
                return $values;
                break;
            case "Box 5":
                $values = [
                    'box1' => $prizes[array_rand($prizes)],
                    'box2' => $prizes[array_rand($prizes)],
                    'box3' => $prizes[array_rand($prizes)],
                    'box4' => $prizes[array_rand($prizes)],
                    'box5' => number_format(0),
                    'box6' => $prizes[array_rand($prizes)],
                ];
                return $values;
                break;
            case "Box 6":
                $values = [
                    'box1' => $prizes[array_rand($prizes)],
                    'box2' => $prizes[array_rand($prizes)],
                    'box3' => $prizes[array_rand($prizes)],
                    'box4' => $prizes[array_rand($prizes)],
                    'box5' => $prizes[array_rand($prizes)],
                    'box6' => number_format(0),
                ];
                return $values;
                break;

            default:
                $values = [
                    'box1' => "!!ERROR!!",
                    'box2' => "!!ERROR!!",
                    'box3' => "!!ERROR!!",
                    'box4' => "!!ERROR!!",
                    'box5' => "!!ERROR!!",
                    'box6' => "!!ERROR!!",
                ];
                return $values;
                break;
        }
        // session(['isValid' => '1']);
        // return response()->json(['login' => 'accepted'], 200);
    }
}
