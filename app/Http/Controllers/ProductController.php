<?php

namespace App\Http\Controllers;

use App\useAPIConfig;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    use useAPIConfig;
    public function view()
    {
        $response = $this->apiGet('products');
        $data = $response->json();
        return Inertia::render('Product', $data);
    }
    public function fetch(Request $request)
    {
        $params = $request->all();
        $response = $this->apiGet('products/fetch', $params);
        $products = $response->json();
        if (count($products) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Akhir dari produk',
            ]);
        }
        foreach ($products as &$product) {
            // Get price as float
            $price = isset($product['prices'][0]['price']) ? (float)$product['prices'][0]['price'] : 0;

            // Get discount 
            $discount = 0;
            if (isset($product['prices'][0]) && count($product['prices'][0]['discounts']) > 0) {
                $type = $product['prices'][0]['discounts'][0]['type'];
                if ($type === 'percentage') {
                    $discount = $price * ($product['prices'][0]['discounts'][0]['value'] ?? 0) / 100;
                }
                if ($type === 'fixed') {
                    $discount = $product['prices'][0]['discounts'][0]['value'] ?? 0;
                }
            }

            // Get first category
            $category = [];
            foreach ($product['categories'] as $cat) {
                array_push($category, [
                    'id' => $cat['id'] ?? null,
                    'name' => $cat['name'] ?? '',
                    'slug' => $cat['slug'] ?? '',
                    'image' => $cat['image'] ?? '',
                ]);
            }
            // Determine status based on lead_time
            $isReady = isset($product['lead_time']) && $product['lead_time'] <= 1;
            $status = [
                'label' => $isReady ? 'Ready' : $product['lead_time'] . ' Jam',
                'isReady' => $isReady,
            ];
            // Build new product structure
            $product = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $price,
                'image' => $product['image'] ?? '../assets/placeholder/image.png',
                'slug' => $product['slug'],
                'discount' => (float)$discount,
                'status' => $status,
                'category' => $category,
            ];
        }
        return response()->json(
            [
                'success' => true,
                'products' => $products,
            ]
        );
    }
}
