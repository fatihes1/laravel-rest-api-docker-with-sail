<?php

namespace App\Http\Controllers;

use App\Http\Resources\Offer\OfferBaseResource;
use App\Repositories\OfferRepositoryInterface;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected OfferRepositoryInterface $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function index()
    {
        return OfferBaseResource::collection($this->offerRepository->all());
    }

    public function show($id)
    {
        return new OfferBaseResource($this->offerRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $offer = $this->offerRepository->create($data);
        return new OfferBaseResource($offer, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $offer = $this->offerRepository->update($id, $data);
        return new OfferBaseResource($offer);
    }

    public function destroy($id)
    {
        $deleted = $this->offerRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
