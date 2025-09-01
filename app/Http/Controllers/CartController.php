<?php

namespace App\Http\Controllers;

use App\useAPIConfig;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    use useAPIConfig;

    public function view()
    {
        return Inertia::render('Checkout');
    }

    public function fetch_prices(Request $request)
    {
        $cart_ids = $request->input('carts');
        if (!$cart_ids) {
            return redirect()->route('cart')->withErrors([
                'warn' => 'Cart kosong'
            ]);
        }
        // This is just a placeholder. Actual cart fetching logic would go here.
        $response = $this->apiPost(
            "products/fetch-prices",
            [
                'carts' => $cart_ids,
            ],
            aborting: false
        );
        dd($response->json());
        return response()->json($response->json());
    }
}
