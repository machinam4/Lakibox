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
            "CallBackURL" => env('MPESA_CALLBACK'),
            "AccountReference" => "Box $box",
            "TransactionDesc" => "Lucky Box " . $box
        ];
        // dd($data);
        // dd(response()->json($data, 200));

        // TO:DO wait for mpesa to finish transaction the send notifi to user

        try {
            $sendStk = new DarajaApiController;
            $response = $sendStk->STKPush($data);
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
        // send response
        switch ($box) {
            case 1:
                $values = [
                    'box1' => number_format(0),
                    'box2' => number_format(round(random_int(10, 1000), -3)),
                    'box3' => number_format(round(random_int(100, 9999999), -5)),
                    'box4' => number_format(round(random_int(10, 100), -2)),
                    'box5' => number_format(round(random_int(100, 999999), -4)),
                    'box6' => number_format(round(random_int(1, 9999), -2)),
                ];
                return $values;
                break;
            case 2:
                $values = [
                    'box1' => number_format(round(random_int(10, 1000), -3)),
                    'box2' => number_format(0),
                    'box3' => number_format(round(random_int(100, 9999999), -5)),
                    'box4' => number_format(round(random_int(10, 100), -2)),
                    'box5' => number_format(round(random_int(100, 999999), -4)),
                    'box6' => number_format(round(random_int(1, 9999), -2)),
                ];
                return $values;
                break;
            case 3:
                $values = [
                    'box1' => number_format(round(random_int(100, 9999999), -5)),
                    'box2' => number_format(round(random_int(10, 1000), -3)),
                    'box3' => 0,
                    'box4' => number_format(round(random_int(10, 100), -2)),
                    'box5' => number_format(round(random_int(100, 999999), -4)),
                    'box6' => number_format(round(random_int(1, 9999), -2)),
                ];
                return $values;
                break;
            case 4:
                $values = [
                    'box1' => number_format(round(random_int(10, 100), -2)),
                    'box2' => number_format(round(random_int(10, 1000), -3)),
                    'box3' => number_format(round(random_int(100, 9999999), -5)),
                    'box4' => number_format(0),
                    'box5' => number_format(round(random_int(100, 999999), -4)),
                    'box6' => number_format(round(random_int(1, 9999), -2)),
                ];
                return $values;
                break;
            case 5:
                $values = [
                    'box1' => number_format(round(random_int(100, 999999), -4)),
                    'box2' => number_format(round(random_int(10, 1000), -3)),
                    'box3' => number_format(round(random_int(100, 9999999), -5)),
                    'box4' => number_format(round(random_int(10, 100), -2)),
                    'box5' => number_format(0),
                    'box6' => number_format(round(random_int(1, 9999), -2)),
                ];
                return $values;
                break;
            case 6:
                $values = [
                    'box1' => number_format(round(random_int(1, 9999), -2)),
                    'box2' => number_format(round(random_int(10, 1000), -3)),
                    'box3' => number_format(round(random_int(100, 9999999), -5)),
                    'box4' => number_format(round(random_int(10, 100), -2)),
                    'box5' => number_format(round(random_int(100, 999999), -4)),
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
