<?php

namespace App\Repositories\Interfaces;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;

interface MovieRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get movies by genre.
     * 
     * @param string $genre
     * @return Collection
     */
    public function getByGenre(string $genre): Collection;
    
    /**
     * Get popular movies.
     * 
     * @param int $limit
     * @return Collection
     */
    public function getPopular(int $limit = 5): Collection;
}