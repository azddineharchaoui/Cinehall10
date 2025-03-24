<?php

namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    /**
     * TicketRepository constructor.
     * 
     * @param Ticket $model
     */
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['reservation.session.movie', 'reservation.session.theater', 'seat'])
            ->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getByReservation(int $reservationId): Collection
    {
        return $this->model->where('reservation_id', $reservationId)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function generateTicket(int $userId, int $reservationId, int $seatId): Ticket
    {
        return $this->create([
            'user_id' => $userId,
            'reservation_id' => $reservationId,
            'seat_id' => $seatId,
            'qr_code' => 'ticket_' . Str::random(10),
        ]);
    }
    
    /**
     * @inheritDoc
     */
    public function getTicketWithDetails(int $ticketId, int $userId): ?Ticket
    {
        return $this->model->with(['reservation.session.movie', 'reservation.session.theater', 'seat'])
            ->where('id', $ticketId)
            ->where('user_id', $userId)
            ->first();
    }
}