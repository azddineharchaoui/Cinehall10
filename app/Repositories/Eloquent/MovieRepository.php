<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MovieRepository extends BaseRepository implements MovieRepositoryInterface
{
    /**
     * MovieRepository constructor.
     * 
     * @param Movie $model
     */
    public function __construct(Movie $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByGenre(string $genre): Collection
    {
        return $this->model->where('genre', $genre)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getPopular(int $limit = 5): Collection
    {
        return $this->model->select('movies.*', DB::raw('COUNT(reservations.id) as ticket_count'))
            ->leftJoin('movie_sessions', 'movies.id', '=', 'movie_sessions.movie_id')
            ->leftJoin('reservations', 'movie_sessions.id', '=', 'reservations.session_id')
            ->where('reservations.status', 'paid')
            ->groupBy('movies.id')
            ->orderBy('ticket_count', 'desc')
            ->limit($limit)
            ->get();
    }
}