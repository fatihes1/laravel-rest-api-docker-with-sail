<?php

namespace App\Http\Controllers;

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
        return response()->json($this->offerRepository->all());
    }

    public function show($id)
    {
        return response()->json($this->offerRepository->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $offer = $this->offerRepository->create($data);
        return response()->json($offer, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $offer = $this->offerRepository->update($id, $data);
        return response()->json($offer);
    }

    public function destroy($id)
    {
        $deleted = $this->offerRepository->delete($id);
        return response()->json(null, $deleted ? 204 : 404);
    }
}
