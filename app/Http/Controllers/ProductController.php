<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductBaseResource;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        return ProductBaseResource::collection($this->productService->getAllProducts());
    }

    public function show($id)
    {
        return new ProductBaseResource($this->productService->getProductById($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = $this->productService->createProduct($data);
        return new ProductBaseResource($product, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = $this->productService->updateProduct($id, $data);
        return new ProductBaseResource($product);
    }

    public function destroy($id)
    {
        $deleted = $this->productService->deleteProduct($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
