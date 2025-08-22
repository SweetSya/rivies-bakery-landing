<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class PaymentController extends Controller
{
    public function createPayment()
    {
        MidtransConfig::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        MidtransConfig::$isProduction = false;
        // Set sanitization on (default)
        MidtransConfig::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        MidtransConfig::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => 10000,
            )
        );

        $snapToken = MidtransSnap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }
}
