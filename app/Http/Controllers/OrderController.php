<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        return response()->json($this->orderRepository->all());
    }

    public function show($id)
    {
        return response()->json($this->orderRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $order = $this->orderRepository->create($data);
        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $order = $this->orderRepository->update($id, $data);
        return response()->json($order);
    }

    public function destroy($id)
    {
        $deleted = $this->orderRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
