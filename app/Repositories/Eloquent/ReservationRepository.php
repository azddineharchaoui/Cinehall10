<?php

namespace App\Repositories\Eloquent;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReservationRepository extends BaseRepository implements ReservationRepositoryInterface
{
    /**
     * ReservationRepository constructor.
     * 
     * @param Reservation $model
     */
    public function __construct(Reservation $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['session.movie', 'session.theater', 'seats'])
            ->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getBySession(int $sessionId): Collection
    {
        return $this->model->where('session_id', $sessionId)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }
    
    /**
     * @inheritDoc
     */
    public function expirePendingReservations(): int
    {
        $expiredReservations = $this->model->where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();
            
        foreach ($expiredReservations as $reservation) {
            $reservation->status = 'expired';
            $reservation->save();
        }
        
        return $expiredReservations->count();
    }
}