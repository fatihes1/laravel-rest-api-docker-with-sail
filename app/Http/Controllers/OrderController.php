<?php

namespace App\Http\Controllers;

use App\Http\Resources\Order\OrderBaseResourceResource;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        return OrderBaseResourceResource::collection($this->orderRepository->all());
    }

    public function show($id)
    {
        return new OrderBaseResourceResource($this->orderRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $order = $this->orderRepository->create($data);

        return new OrderBaseResourceResource($order, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $order = $this->orderRepository->update($id, $data);

        return new OrderBaseResourceResource($order);
    }

    public function destroy($id)
    {
        $deleted = $this->orderRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
