<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TheaterRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TheaterController extends Controller
{
    private $theaterRepository;
    
    public function __construct(TheaterRepositoryInterface $theaterRepository)
    {
        $this->theaterRepository = $theaterRepository;
    }
    
    public function index()
    {
        $theaters = $this->theaterRepository->all();
        return response()->json($theaters);
    }

    public function show($id)
    {
        $theater = $this->theaterRepository->findById($id, ['*'], ['seats']);
        return response()->json($theater);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|in:Normal,VIP',
            'rows' => 'required|integer',
            'seats_per_row' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $theater = $this->theaterRepository->createWithSeats($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Theater created successfully',
            'theater' => $theater,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'type' => 'in:Normal,VIP',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->theaterRepository->update($id, $request->only(['name', 'type']));
        $theater = $this->theaterRepository->findById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Theater updated successfully',
            'theater' => $theater,
        ]);
    }

    public function destroy($id)
    {
        $this->theaterRepository->deleteById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Theater deleted successfully',
        ]);
    }
    
    public function getSeats($id)
    {
        $seats = $this->theaterRepository->getSeats($id);
        return response()->json($seats);
    }
    
    public function getByType($type)
    {
        $theaters = $this->theaterRepository->getByType($type);
        return response()->json($theaters);
    }
}