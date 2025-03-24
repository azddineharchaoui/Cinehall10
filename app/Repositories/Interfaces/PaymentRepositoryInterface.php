<?php

namespace App\Repositories\Interfaces;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get payments by user.
     * 
     * @param int $userId
     * @return Collection
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get payments by reservation.
     * 
     * @param int $reservationId
     * @return Payment|null
     */
    public function getByReservation(int $reservationId): ?Payment;
    
    /**
     * Process payment for a reservation.
     * 
     * @param int $reservationId
     * @param float $amount
     * @param string $paymentMethod
     * @param string $transactionId
     * @return Payment
     */
    public function processPayment(int $reservationId, float $amount, string $paymentMethod, string $transactionId): Payment;
}