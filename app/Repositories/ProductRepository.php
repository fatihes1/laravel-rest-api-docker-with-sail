<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{

    public function all(): Collection
    {
        return Product::all();
    }

    public function find($id): ?Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update($id, array $data): Product
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id): bool
    {
        $product = $this->find($id);
        return $product->delete();
    }
}
