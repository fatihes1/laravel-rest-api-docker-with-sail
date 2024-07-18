<?php

namespace App\Http\Controllers;

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
        return response()->json($this->productRepository->all());
    }

    public function show($id)
    {
        return response()->json($this->productRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = $this->productRepository->create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $product = $this->productRepository->update($id, $data);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $deleted = $this->productRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
