<?php

namespace App\Repositories\Eloquent;

use App\Models\Payment;
use App\Models\Reservation;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * PaymentRepository constructor.
     * 
     * @param Payment $model
     */
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->whereHas('reservation', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }
    
    /**
     * @inheritDoc
     */
    public function getByReservation(int $reservationId): ?Payment
    {
        return $this->model->where('reservation_id', $reservationId)->first();
    }
    
    /**
     * @inheritDoc
     */
    public function processPayment(int $reservationId, float $amount, string $paymentMethod, string $transactionId): Payment
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'paid';
        $reservation->save();
        
        // Create payment record
        return $this->create([
            'reservation_id' => $reservationId,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'status' => 'completed',
        ]);
    }
}