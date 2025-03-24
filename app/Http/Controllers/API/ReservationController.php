<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    private $reservationRepository;
    private $sessionRepository;

    public function __construct(
        ReservationRepositoryInterface $reservationRepository,
        SessionRepositoryInterface $sessionRepository
    ) {
        $this->reservationRepository = $reservationRepository;
        $this->sessionRepository = $sessionRepository;
    }

    public function index()
    {
        $user = Auth::user();
        $reservations = $this->reservationRepository->getByUser($user->id);
            
        return response()->json($reservations);
    }

    public function show($id)
    {
        $user = Auth::user();
        $reservation = $this->reservationRepository->findById(
            $id, 
            ['*'], 
            ['session.movie', 'session.theater', 'seats', 'payment', 'tickets']
        );
        
        // Vérifier si la réservation appartient à l'utilisateur
        if ($reservation->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
            
        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:movie_sessions,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Auth::user();
        $session = $this->sessionRepository->findById($request->session_id);
        $seats = Seat::whereIn('id', $request->seat_ids)->get();
        
        // Vérifier si les sièges sont disponibles
        $reservedSeatIds = $session->reservations()
            ->whereIn('status', ['pending', 'paid'])
            ->with('seats')
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id')
            ->toArray();
            
        $unavailableSeats = $seats->filter(function ($seat) use ($reservedSeatIds) {
            return in_array($seat->id, $reservedSeatIds);
        });
        
        if ($unavailableSeats->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some seats are already reserved',
                'unavailable_seats' => $unavailableSeats,
            ], 400);
        }
        
        // Pour les séances VIP, vérifier si les sièges de couple sont réservés par paires
        if ($session->type === 'VIP') {
            $coupleSeats = $seats->filter(function ($seat) {
                return $seat->type === 'couple';
            });
            
            if ($coupleSeats->count() % 2 !== 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Couple seats must be reserved in pairs',
                ], 400);
            }
        }
        
        // Calculer le prix total
        $pricePerSeat = $session->type === 'VIP' ? 15.00 : 10.00;
        $totalPrice = $seats->count() * $pricePerSeat;
        
        $reservation = $this->reservationRepository->create([
            'user_id' => $user->id,
            'session_id' => $session->id,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15),
            'total_price' => $totalPrice,
        ]);
        
        $reservation->seats()->attach($request->seat_ids);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created successfully',
            'reservation' => $reservation->load(['session.movie', 'session.theater', 'seats']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $reservation = $this->reservationRepository->findById($id);
        
        if ($reservation->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        if ($reservation->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending reservations can be updated',
            ], 400);
        }
        
        $validator = Validator::make($request->all(), [
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $session = $reservation->session;
        $seats = Seat::whereIn('id', $request->seat_ids)->get();
        
        $reservedSeatIds = $session->reservations()
            ->where('id', '!=', $reservation->id)
            ->whereIn('status', ['pending', 'paid'])
            ->with('seats')
            ->get()
            ->pluck('seats')
            ->flatten()
            ->pluck('id')
            ->toArray();
            
        $unavailableSeats = $seats->filter(function ($seat) use ($reservedSeatIds) {
            return in_array($seat->id, $reservedSeatIds);
        });
        
        if ($unavailableSeats->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some seats are already reserved',
                'unavailable_seats' => $unavailableSeats,
            ], 400);
        }
        
        if ($session->type === 'VIP') {
            $coupleSeats = $seats->filter(function ($seat) {
                return $seat->type === 'couple';
            });
            
            if ($coupleSeats->count() % 2 !== 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Couple seats must be reserved in pairs',
                ], 400);
            }
        }
        
        $pricePerSeat = $session->type === 'VIP' ? 15.00 : 10.00;
        $totalPrice = $seats->count() * $pricePerSeat;
        
        $this->reservationRepository->update($reservation->id, [
            'total_price' => $totalPrice,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);
        
        $reservation->seats()->sync($request->seat_ids);
        
        $updatedReservation = $this->reservationRepository->findById(
            $id, 
            ['*'], 
            ['session.movie', 'session.theater', 'seats']
        );
        
        return response()->json([
            'status' => 'success',
            'message' => 'Reservation updated successfully',
            'reservation' => $updatedReservation,
        ]);
    }

    public function cancel($id)
    {
        $user = Auth::user();
        $reservation = $this->reservationRepository->findById($id);
        
        if ($reservation->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        if ($reservation->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending reservations can be cancelled',
            ], 400);
        }
        
        $this->reservationRepository->update($id, ['status' => 'cancelled']);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Reservation cancelled successfully',
        ]);
    }
    
    public function getByStatus($status)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $reservations = $this->reservationRepository->getByStatus($status);
        return response()->json($reservations);
    }
    
    public function getBySession($sessionId)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        
        $reservations = $this->reservationRepository->getBySession($sessionId);
        return response()->json($reservations);
    }
}