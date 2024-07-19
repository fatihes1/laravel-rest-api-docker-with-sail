<?php

namespace App\Http\Controllers;

use App\Http\Resources\Offer\OfferBaseResource;
use App\Repositories\OfferRepositoryInterface;
use App\Services\OfferService;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    protected OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index()
    {
        return OfferBaseResource::collection($this->offerService->getAllOffers());
    }

    public function show($id)
    {
        return new OfferBaseResource($this->offerService->getOfferById($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $offer = $this->offerService->createOffer($data);
        return new OfferBaseResource($offer, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $offer = $this->offerService->updateOffer($id, $data);
        return new OfferBaseResource($offer);
    }

    public function destroy($id)
    {
        $deleted = $this->offerService->deleteOffer($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
