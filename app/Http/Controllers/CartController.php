<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends RiviesAPIController
{

    public function __construct()
    {
        parent::__construct();
    }
    public function view()
    {
        if ($this->authenticated) {
            $response = $this->apiGet("vouchers/user-vouchers", aborting: false);
            $vouchers = $response->json()['vouchers'] ?? [];
            if ($vouchers && count($vouchers) > 0) {
                foreach ($vouchers as &$voucher) {
                    $voucher_id = $voucher['voucher']['id'] ?? null;
                    unset($voucher['voucher']['id']);
                    $voucher = [
                        ...$voucher,
                        'voucher_id' => $voucher_id,
                        ...$voucher['voucher']
                    ];
                    unset($voucher['voucher']);
                }
            }
        }
        return Inertia::render('Cart', [
            'vouchers' => $vouchers  ?? []
        ]);
    }
    public function testAuth(Request $request)
    {
        $response = $this->apiGet("test-auth", aborting: false);
        return response()->json($response->json());
    }
    public function validate_cart(Request $request)
    {
        $cart = $request->all();
        $response = $this->apiPost(
            "checkout/validate-cart",
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
    public function apply_voucher(Request $request)
    {
        $data = $request->all();
        $data['cart']['cupon']['id'] = $data['code'] ?? null;
        unset($data['code']);

        $response = $this->apiPost(
            "checkout/validate-cart",
            $data,
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

    public function download_draft_cart(Request $request)
    {
        $cart = $request->all();
        $unique_id = uniqid() . '-' . time();

        // Step 1: Generate the PDF and get the filename
        $response = $this->apiPost(
            "download/draft-cart",
            [
                'unique_id' => $unique_id,
                'cart' => $cart
            ],
            aborting: false
        );

        if ($response->successful()) {
            $data = $response->json();

            // Step 2: Get the actual PDF file using the filename
            if (isset($data['filename'])) {
                $pdfResponse = $this->apiGet(
                    "download/draft-cart",  // Your API endpoint
                    [
                        'filename' => $data['filename'],
                    ],
                    aborting: false
                );
                if ($pdfResponse->successful()) {
                    // Return the PDF content with proper headers
                    return response($pdfResponse->body())
                        ->header('Content-Type', 'application/pdf')
                        ->header('Content-Disposition', 'attachment; filename="invoice.pdf"')
                        ->header('Content-Length', strlen($pdfResponse->body()));
                } else {
                    return response()->json([
                        'error' => 'Failed to retrieve PDF file'
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Filename not provided by API'
                ], 500);
            }
        }

        return response()->json([
            'error' => 'Failed to generate PDF'
        ], 500);
    }
}
