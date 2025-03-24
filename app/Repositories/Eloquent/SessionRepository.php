<?php

namespace App\Repositories\Eloquent;

use App\Models\Session;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SessionRepository extends BaseRepository implements SessionRepositoryInterface
{
    /**
     * SessionRepository constructor.
     * 
     * @param Session $model
     */
    public function __construct(Session $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByMovie(int $movieId): Collection
    {
        return $this->model->where('movie_id', $movieId)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getByTheater(int $theaterId): Collection
    {
        return $this->model->where('theater_id', $theaterId)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getAvailableSeats(int $sessionId): array
    {
        $session = $this->findById($sessionId, ['*'], ['theater.seats']);
        $theater = $session->theater;
        $seats = $theater->seats;
        
        // Get all reserved seats for this session
        $reservedSeatIds = $session->reservations()
            ->whereIn('status', ['pending', 'paid'])
            ->with('seats')
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id')
            ->toArray();
        
        // Mark seats as available or not
        $seatsWithAvailability = $seats->map(function ($seat) use ($reservedSeatIds) {
            $seat->available = !in_array($seat->id, $reservedSeatIds);
            return $seat;
        });
        
        return [
            'session' => $session,
            'seats' => $seatsWithAvailability,
        ];
    }
}