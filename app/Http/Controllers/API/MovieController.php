<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
    private $movieRepository;
    
    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }
    
    public function index()
    {
        $movies = $this->movieRepository->all();
        return response()->json($movies);
    }

    public function show($id)
    {
        try {
            $movie = $this->movieRepository->findById((int) $id); // Conversion en int
            if (!$movie) {
                return response()->json([
                    'message' => 'Movie not found',
                ], 404);
            }
            return response()->json($movie);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'duration' => 'required|integer',
            'min_age' => 'required|integer',
            'trailer_url' => 'nullable|string',
            'genre' => 'required|string',
            'actors' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $movie = $this->movieRepository->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Movie created successfully',
            'movie' => $movie,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'image' => 'nullable|string',
            'duration' => 'integer',
            'min_age' => 'integer',
            'trailer_url' => 'nullable|string',
            'genre' => 'string',
            'actors' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->movieRepository->update($id, $request->all());
        $movie = $this->movieRepository->findById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Movie updated successfully',
            'movie' => $movie,
        ]);
    }

    public function destroy($id)
    {
        $this->movieRepository->deleteById($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Movie deleted successfully',
        ]);
    }
    
    public function getByGenre($genre)
    {
        $movies = $this->movieRepository->getByGenre($genre);
        return response()->json($movies);
    }
    
    public function getPopular()
    {
        $movies = $this->movieRepository->getPopular();
        return response()->json($movies);
    }
}