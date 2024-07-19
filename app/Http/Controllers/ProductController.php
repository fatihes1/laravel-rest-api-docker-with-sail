<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductBaseResource;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return ProductBaseResource::collection($this->productRepository->all());
    }

    public function show($id)
    {
        return new ProductBaseResource($this->productRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = $this->productRepository->create($data);
        return new ProductBaseResource($product, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = $this->productRepository->update($id, $data);
        return new ProductBaseResource($product);
    }

    public function destroy($id)
    {
        $deleted = $this->productRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
