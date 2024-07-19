<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderBaseResourceResource;
use App\Repositories\OrderRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        return OrderBaseResourceResource::collection($this->orderService->getAllOrders());
    }

    public function show($id)
    {
        return new OrderBaseResourceResource($this->orderService->getOrderById($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $order = $this->orderService->createOrder($data);

        return new OrderBaseResourceResource($order, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $order = $this->orderService->updateOrder($id, $data);

        return new OrderBaseResourceResource($order);
    }

    public function destroy($id)
    {
        $deleted = $this->orderService->deleteOrder($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
