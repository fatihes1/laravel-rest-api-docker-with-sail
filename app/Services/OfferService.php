<?php

namespace App\Services;

use App\Repositories\OfferRepositoryInterface;

class OfferService
{
    protected OfferRepositoryInterface $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function getAllOffers() {
        return $this->offerRepository->all();
    }

    public function getOfferById(int $id) {
        return $this->offerRepository->find($id);
    }

    public function createOffer(array $data)
    {
        return $this->offerRepository->create($data);
    }

    public function updateOffer(int $id, array $data)
    {
        return $this->offerRepository->update($id, $data);
    }

    public function deleteOffer(int $id)
    {
        return $this->offerRepository->delete($id);
    }

}
