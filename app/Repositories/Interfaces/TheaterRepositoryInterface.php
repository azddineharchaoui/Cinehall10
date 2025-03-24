<?php

namespace App\Repositories\Interfaces;

use App\Models\Theater;
use Illuminate\Database\Eloquent\Collection;

interface TheaterRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get theaters by type.
     * 
     * @param string $type
     * @return Collection
     */
    public function getByType(string $type): Collection;
    
    /**
     * Create theater with seats.
     * 
     * @param array $theaterData
     * @return Theater
     */
    public function createWithSeats(array $theaterData): Theater;
    
    /**
     * Get all seats for a theater.
     * 
     * @param int $theaterId
     * @return Collection
     */
    public function getSeats(int $theaterId): Collection;
}