<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends RiviesAPIController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function view()
    {
        return Inertia::render('Checkout');
    }

    public function validate_checkout(Request $request)
    {
        $cart = $request->all();
        $response = $this->apiPost(
            "checkout/validate",
            $cart,
            aborting: false
        );
        $data = $response->json();
        // dd($response->json());
        // Here you can add your validation logic, e.g., check stock, prices, etc.
        // For demonstration, we'll just return the received cart items.
        return response()->json([
            'valid' => $data['valid'],
            'errors' => $data['errors'],
            'cart' => $data['cart'],
        ]);
    }
}
