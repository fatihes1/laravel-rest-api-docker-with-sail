<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Offer;

class OfferRepository implements OfferRepositoryInterface
{
    public function all(): Collection
    {
        return Offer::all();
    }

    public function find($id): ?Offer
    {
        return Offer::findOrFail($id);
    }

    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    public function update($id, array $data): Offer
    {
        $offer = $this->find($id);
        $offer->update($data);
        return $offer;
    }

    public function delete($id): bool
    {
        $offer = $this->find($id);
        return $offer->delete();
    }
}
