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
    public function preprocess_product(array $products): array
    {
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
                'images' => $product['images'] ?? ['../assets/placeholder/image.png'],
                'thumbnail' => $product['thumbnail'] ?? '../assets/placeholder/image.png',
                'slug' => $product['slug'],
                'discount' => (float)$discount,
                'status' => $status,
                'category' => $category,
            ];
        }
        return $products;
    }
    public function preprocess_prices(array $prices): array
    {
        foreach ($prices as &$price) {
            // Get price as float
            $priceValue = isset($price['price']) ? (float)$price['price'] : 0;

            // Get discount 
            $discount = 0;
            if (isset($price['discounts']) && count($price['discounts']) > 0) {
                $type = $price['discounts'][0]['type'];
                if ($type === 'percentage') {
                    $discount = $priceValue * ($price['discounts'][0]['value'] ?? 0) / 100;
                }
                if ($type === 'fixed') {
                    $discount = $price['discounts'][0]['value'] ?? 0;
                }
            }
            // Build new product structure
            $price = [
                'id' => $price['id'],
                'tag' => $price['tag'],
                'price' => $price['price'],
                'discount' => $discount,
            ];
        }
        return $prices;
    }
    public function fetch(Request $request)
    {
        $params = $request->all();
        $response = $this->apiGet('products/fetch', $params);
        $products = $response->json();
        $processed_products = $this->preprocess_product($products);
        if (count($processed_products) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Akhir dari produk',
            ]);
        }

        return response()->json(
            [
                'success' => true,
                'products' => $processed_products,
            ]
        );
    }
    public function single(Request $request)
    {
        // get slug from params
        $slug = $request->input('slug');
        if (!$slug) {
            return redirect()->route('products')->withErrors([
                'warn' => 'Produk tidak ditemukan'
            ]);
        }
        $response = $this->apiGet(
            "products/single",
            [
                'slug' => $slug,
            ],
            aborting: false
        );
        $data = $response->json();
        if (!$response->ok()) {
            return redirect()->route('products')->withErrors([
                'warn' => 'Produk tidak ditemukan'
            ]);
        }

        $prices = $data['prices'];
        $processed_prices = $this->preprocess_prices($prices);
        $processed_products = $this->preprocess_product([$data]);
        $product = $processed_products[0] ?? null;
        $product['id'] = $prices[0]['id'] ?? null;
        $product['price'] = $prices[0]['price'] ?? null;
        $product['discount'] = $processed_prices[0]['discount'] ?? null;

        return Inertia::render('ProductDetail', [
            'product' => $product,
            'prices' => $processed_prices,
        ]);
    }
}
