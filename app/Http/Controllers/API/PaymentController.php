<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    private $paymentRepository;
    private $reservationRepository;
    private $ticketRepository;
    
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        ReservationRepositoryInterface $reservationRepository,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->reservationRepository = $reservationRepository;
        $this->ticketRepository = $ticketRepository;
    }
    
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|exists:reservations,id',
            'payment_method' => 'required|in:stripe,paypal',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $reservation = $this->reservationRepository->findById(
            $request->reservation_id,
            ['*'],
            ['seats']
        );
        
        // Check if reservation belongs to user
        if ($reservation->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        // Check if reservation is pending
        if ($reservation->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation is not pending',
            ], 400);
        }
            
        // Check if reservation has expired
        if (now() > $reservation->expires_at) {
            $reservation->status = 'expired';
            $reservation->save();
            
            return response()->json([
                'status' => 'error',
                'message' => 'Reservation has expired',
            ], 400);
        }
        
        // In a real application, you would process the payment with Stripe or PayPal here
        // For this example, we'll simulate a successful payment
        
        // Create payment record
        $payment = $this->paymentRepository->processPayment(
            $reservation->id,
            $reservation->total_price,
            $request->payment_method,
            'txn_' . Str::random(10)
        );
        
        // Generate tickets
        foreach ($reservation->seats as $seat) {
            $this->ticketRepository->generateTicket(
                $user->id,
                $reservation->id,
                $seat->id
            );
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully',
            'payment' => $payment,
            'reservation' => $reservation->load(['tickets']),
        ]);
    }
    
    public function getUserPayments()
    {
        $user = Auth::user();
        $payments = $this->paymentRepository->getByUser($user->id);
        
        return response()->json($payments);
    }
    
    public function getReservationPayment($reservationId)
    {
        $user = Auth::user();
        $reservation = $this->reservationRepository->findById($reservationId);
        
        // Check if reservation belongs to user
        if ($reservation->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $payment = $this->paymentRepository->getByReservation($reservationId);
        
        return response()->json($payment);
    }
}