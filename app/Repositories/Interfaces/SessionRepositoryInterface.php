<?php

namespace App\Repositories\Interfaces;

use App\Models\Session;
use Illuminate\Database\Eloquent\Collection;

interface SessionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get sessions by movie.
     * 
     * @param int $movieId
     * @return Collection
     */
    public function getByMovie(int $movieId): Collection;
    
    /**
     * Get sessions by theater.
     * 
     * @param int $theaterId
     * @return Collection
     */
    public function getByTheater(int $theaterId): Collection;
    
    /**
     * Get sessions by type.
     * 
     * @param string $type
     * @return Collection
     */
    public function getByType(string $type): Collection;
    
    /**
     * Get available seats for a session.
     * 
     * @param int $sessionId
     * @return array
     */
    public function getAvailableSeats(int $sessionId): array;
}