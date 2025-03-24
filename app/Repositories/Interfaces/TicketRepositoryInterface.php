<?php

namespace App\Repositories\Interfaces;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get tickets by user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get tickets by reservation.
     * 
     * @param int $reservationId
     * @return Collection
     */
    public function getByReservation(int $reservationId): Collection;
    
    /**
     * Generate ticket for a seat in a reservation.
     * 
     * @param int $userId
     * @param int $reservationId
     * @param int $seatId
     * @return Ticket
     */
    public function generateTicket(int $userId, int $reservationId, int $seatId): Ticket;
    
    /**
     * Get ticket with all related data.
     * 
     * @param int $ticketId
     * @param int $userId
     * @return Ticket|null
     */
    public function getTicketWithDetails(int $ticketId, int $userId): ?Ticket;
}