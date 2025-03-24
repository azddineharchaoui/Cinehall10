<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SessionController extends Controller
{
    private $sessionRepository;
    
    public function __construct(SessionRepositoryInterface $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }
    
    public function index(Request $request)
    {
        if ($request->has('type')) {
            $sessions = $this->sessionRepository->getByType($request->type);
        } else {
            $sessions = $this->sessionRepository->all(['*'], ['movie', 'theater']);
        }
        
        return response()->json($sessions);
    }

    public function show($id)
    {
        $session = $this->sessionRepository->findById($id, ['*'], ['movie', 'theater']);
        return response()->json($session);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'theater_id' => 'required|exists:theaters,id',
            'start_time' => 'required|date',
            'language' => 'required|string',
            'type' => 'required|in:Normal,VIP',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $session = $this->sessionRepository->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Session created successfully',
            'session' => $session,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'exists:movies,id',
            'theater_id' => 'exists:theaters,id',
            'start_time' => 'date',
            'language' => 'string',
            'type' => 'in:Normal,VIP',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->sessionRepository->update($id, $request->all());
        $session = $this->sessionRepository->findById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Session updated successfully',
            'session' => $session,
        ]);
    }

    public function destroy($id)
    {
        $this->sessionRepository->deleteById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Session deleted successfully',
        ]);
    }

    public function getAvailableSeats($id)
    {
        $result = $this->sessionRepository->getAvailableSeats($id);
        return response()->json($result);
    }
    
    public function getByMovie($movieId)
    {
        $sessions = $this->sessionRepository->getByMovie($movieId);
        return response()->json($sessions);
    }
    
    public function getByTheater($theaterId)
    {
        $sessions = $this->sessionRepository->getByTheater($theaterId);
        return response()->json($sessions);
    }
}