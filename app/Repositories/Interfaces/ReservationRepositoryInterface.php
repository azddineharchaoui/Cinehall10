<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get reservations by user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get reservations by session.
     * 
     * @param int $sessionId
     * @return Collection
     */
    public function getBySession(int $sessionId): Collection;
    
    /**
     * Get reservations by status.
     * 
     * @param string $status
     * @return Collection
     */
    public function getByStatus(string $status): Collection;
    
    /**
     * Expire pending reservations.
     * 
     * @return int Number of expired reservations
     */
    public function expirePendingReservations(): int;
}