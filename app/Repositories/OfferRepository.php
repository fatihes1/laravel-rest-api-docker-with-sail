<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository implements OfferRepositoryInterface
{
    public function all()
    {
        return Offer::all();
    }

    public function find($id)
    {
        return Offer::find($id);
    }

    public function create(array $data)
    {
        return Offer::create($data);
    }

    public function update($id, array $data)
    {
        $offer = Offer::find($id);
        if ($offer) {
            $offer->update($data);
            return $offer;
        }
        return null;
    }

    public function delete($id)
    {
        $offer = Offer::find($id);
        if ($offer) {
            $offer->delete();
            return true;
        }
        return false;
    }
}
