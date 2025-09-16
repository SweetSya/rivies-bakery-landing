<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentStatusController extends RiviesAPIController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function view()
    {
        // Check for query parameters
        $state = request()->query('state') ?? null;
        $order_id = request()->query('order_id') ?? null;
        if ($state === 'success' && $order_id) {
            // Update order status to paid
            $response = $this->apiGet(
                'order/update-payment-status',
                [
                    'order_id' => $order_id
                ],
                aborting: false
            );
        }

        return Inertia::render('PaymentStatus');
    }
}
