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
            'gross_amount' => 30000,
        ),
        'item_details' => array(
            array(
                'id' => 'ITEM1',
                'price' => 10000,
                'quantity' => 2,
                'name' => 'Midtrans Bear',
            ),
            array(
                'id' => 'ITEM1',
                'price' => 10000,
                'quantity' => 1,
                'name' => 'Midtrans Bear 2',
            )
        ),
        'customer_details' => array(
            'first_name' => 'Andri',
            'last_name' => 'Setiawan',
            'email' => 'andri.setiawan@example.com',
            'phone' => '08123456789'
        ),
        "callbacks" =>  array(
            "finish" => "https://rivies-bakery-landing.test/checkout?payment-completed=true&order-id=123"
        )
    );

    $snapToken = MidtransSnap::getSnapToken($params);

    return response()->json([
        'snap_token' => $snapToken
    ]);
}
}
