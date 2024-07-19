<?php

namespace App\Repositories;

use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Order::with('orderItems', 'user')->get();
    }

    public function find($id): ?Order
    {
        return Order::with('orderItems', 'user')->findOrFail($id);
    }

    public function create(array $data): Order
    {
        $order = Order::create([
            'user_id' => $data['user_id'],
            'address' => $data['address'],
            'total_price' => 0, // set to 0 for now, will be updated later
            'status' => $data['status'] ?? 'Pending',
        ]);

        $totalPrice = 0;

        foreach ($data['order_items'] as $itemData) {
            $product = Product::find($itemData['product_id']);
            $offer = Offer::where('product_id', $product->id)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            $discount = $offer ? $offer->discount : 0;
            $price = $product->price;
            if ($discount) {
                $price -= ($price * $discount / 100);
            }

            $orderItem = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'price' => $price,
            ]);

            $order->orderItems()->save($orderItem);

            $totalPrice += $price * $itemData['quantity'];
        }
        $order->total_price = $totalPrice;
        $order->save();

        return $order->load('orderItems', 'user');

    }

    public function update($id, array $data): Order
    {
        $order = $this->find($id);

        $order->update([
            'user_id' => $data['user_id'],
            'address' => $data['address'],
            'total_price' => 0, // Total price will be calculated later
            'status' => $data['status'] ?? 'Pending',
        ]);

        $order->orderItems()->delete();

        $totalPrice = 0;

        $data['order_items'] = $data['order_items'] ?? [];

        foreach ($data['order_items'] as $itemData) {
            $product = Product::find($itemData['product_id']);
            $offer = Offer::where('product_id', $product->id)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();


            $discount = $offer ? $offer->discount : 0;

            $price = $product->price;
            if ($discount) {
                $price -= ($price * $discount / 100);
            }

            $orderItem = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'price' => $price,
            ]);

            $order->orderItems()->save($orderItem);
            $totalPrice += $price * $itemData['quantity'];
        }

        $order->total_price = $totalPrice;
        $order->save();

        return $order->load('orderItems', 'user');
    }

    public function delete($id): bool
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return true;
        }
        return false;
    }
}
