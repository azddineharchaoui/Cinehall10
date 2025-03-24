<?php

namespace App\Repositories\Eloquent;

use App\Models\Movie;
use App\Models\Reservation;
use App\Models\Session;
use App\Models\User;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AdminRepository implements AdminRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getDashboardStats(): array
    {
        // Count total movies, sessions, reservations
        $totalMovies = Movie::count();
        $totalSessions = Session::count();
        $totalReservations = Reservation::where('status', 'paid')->count();
        
        // Calculate revenue
        $totalRevenue = Reservation::where('status', 'paid')->sum('total_price');
        
        // Get popular movies
        $popularMovies = Movie::select('movies.*', DB::raw('COUNT(reservations.id) as ticket_count'))
            ->leftJoin('movie_sessions', 'movies.id', '=', 'movie_sessions.movie_id')
            ->leftJoin('reservations', 'movie_sessions.id', '=', 'reservations.session_id')
            ->where('reservations.status', 'paid')
            ->groupBy('movies.id')
            ->orderBy('ticket_count', 'desc')
            ->limit(5)
            ->get();
            
        return [
            'total_movies' => $totalMovies,
            'total_sessions' => $totalSessions,
            'total_reservations' => $totalReservations,
            'total_revenue' => $totalRevenue,
            'popular_movies' => $popularMovies,
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function getSessionOccupancy(): Collection
    {
        return Session::with(['movie', 'theater'])
            ->withCount(['reservations' => function ($query) {
                $query->where('status', 'paid');
            }])
            ->get()
            ->map(function ($session) {
                $totalSeats = $session->theater->rows * $session->theater->seats_per_row;
                $occupiedSeats = DB::table('reservation_seat')
                    ->join('reservations', 'reservations.id', '=', 'reservation_seat.reservation_id')
                    ->where('reservations.session_id', $session->id)
                    ->where('reservations.status', 'paid')
                    ->count();
                    
                $session->occupancy_rate = $totalSeats > 0 ? ($occupiedSeats / $totalSeats) * 100 : 0;
                return $session;
            });
    }
    
    /**
     * @inheritDoc
     */
    public function getMovieRevenue(): Collection
    {
        return Movie::select('movies.*', DB::raw('SUM(reservations.total_price) as revenue'))
            ->leftJoin('movie_sessions', 'movies.id', '=', 'movie_sessions.movie_id')
            ->leftJoin('reservations', 'movie_sessions.id', '=', 'reservations.session_id')
            ->where('reservations.status', 'paid')
            ->groupBy('movies.id')
            ->orderBy('revenue', 'desc')
            ->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getUsers(): Collection
    {
        return User::where('role', 'user')->get();
    }
    
    /**
     * @inheritDoc
     */
    public function updateUserRole(int $userId, string $role): User
    {
        $user = User::findOrFail($userId);
        $user->role = $role;
        $user->save();
        
        return $user;
    }
}