<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function all()
    {
        return Order::with('orderItems')->get();
    }

    public function find($id)
    {
        return Order::with('orderItems')->find($id);
    }

    public function create(array $data)
    {
        $order = Order::create([
            //'customer_name' => $data['customer_name'],
            //'customer_email' => $data['customer_email'],
            'user_id' => $data['user_id'],
            'address' => $data['address'],
            'total_price' => $data['total_price'],
            'status' => $data['status']
        ]);

        foreach ($data['order_items'] as $item) {
            $order->orderItems()->create($item);
        }

        return $order->load('orderItems');
    }

    public function update($id, array $data)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update($data);
            $order->orderItems()->delete();
            foreach ($data['order_items'] as $item) {
                $order->orderItems()->create($item);
            }
            return $order->load('orderItems');
        }
        return null;
    }

    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return true;
        }
        return false;
    }
}
