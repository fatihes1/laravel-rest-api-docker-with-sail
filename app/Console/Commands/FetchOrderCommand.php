<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Offer;
use App\Models\Order;

class FetchOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-order-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch orders from the mock API and store them in the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rateLimit = 1;
        $endpoints = [
            'products' => 'https://run.mocky.io/v3/c448f769-ce16-4bb3-ad6f-44edb15cad08',
            'offers' => 'https://run.mocky.io/v3/c3641475-28a5-4f04-9bdc-e402965134ea',
            'orders' => 'https://run.mocky.io/v3/42e1f728-4d08-4ed7-8e89-b26391c40c51',
        ];

        foreach ($endpoints as $key => $url) {
            try {
                // Fetch data
                $response = Http::get($url);

                if ($response->successful()) {
                    $data = $response->json();

                    switch ($key) {
                        case 'products':
                            $this->processProducts($data);
                            break;
                        case 'offers':
                            $this->processOffers($data);
                            break;
                        case 'orders':
                            $this->processOrders($data);
                            break;
                    }

                    $this->info(ucfirst($key) . ' fetched and processed successfully.');
                } else {
                    Log::error("Failed to fetch $key: " . $response->body());
                    $this->error("Failed to fetch $key.");
                    return 1;
                }
            } catch (\Exception $e) {
                Log::error("Error fetching $key: " . $e->getMessage());
                $this->error("Error fetching $key.");
                return 1;
            }

            sleep(1 / $rateLimit); // Ensure rate limit
        }

        return 0;

    }

    protected function processProducts(array $products): void
    {
        foreach ($products as $product) {
            Product::updateOrCreate(['id' => $product['id']], $product);
        }
    }

    protected function processOffers(array $offers): void
    {
        // Get all products to use for default value if needed
        $allProducts = Product::all();

        foreach ($offers as $offer) {
            // Check if the product exists
            $product = Product::find($offer['product_id']);

            if (!$product) {
                // If product not found, use a random existing product
                if ($allProducts->isEmpty()) {
                    Log::error("No products available to assign to offer with ID {$offer['id']}.");
                    continue; // Skip this offer if no products are available
                }

                // Pick a random product
                $product = $allProducts->random();
            }

            // Update or create the offer
            Offer::updateOrCreate(['id' => $offer['id']], array_merge($offer, ['product_id' => $product->id]));
        }
    }

    protected function processOrders(array $orders): void
    {
        // Get all products to use for default value if needed
        $allProducts = Product::all();

        foreach ($orders as $order) {
            // Create or update user if not exist
            $user = User::updateOrCreate(
                ['email' => $order['customer_email']],
                [
                    'name' => $order['customer_name'],
                    'password' => Hash::make('password') // Set a default hashed password
                ]
            );

            // Create or update order
            $orderData = [
                'user_id' => $user->id,
                'address' => $order['address'],
                'total_price' => $order['total_price'],
                'status' => $order['status'],
            ];

            $orderModel = Order::updateOrCreate(['id' => $order['id']], $orderData);

            // Process order items
            foreach ($order['order_items'] as $item) {
                // Check if the product exists
                $product = Product::find($item['product_id']);

                if (!$product) {
                    // If product not found, use a random existing product
                    if ($allProducts->isEmpty()) {
                        Log::error("No products available to assign to order item with product ID {$item['product_id']}.");
                        continue; // Skip this item if no products are available
                    }

                    // Pick a random product
                    $product = $allProducts->random();
                }

                // Calculate the total price with discount if applicable
                $offer = Offer::where('product_id', $product->id)->first();
                $discount = $offer ? $offer->discount : 0;
                $price = $item['price'] * (1 - $discount / 100);

                // Update or create the order item
                $orderModel->orderItems()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['quantity' => $item['quantity'], 'price' => $price]
                );
            }
        }
    }
}
