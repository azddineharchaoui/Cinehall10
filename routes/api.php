<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\TheaterController;
use App\Http\Controllers\API\TicketController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Movies (public)
Route::get('movies', [MovieController::class, 'index']);
Route::get('movies/popular', [MovieController::class, 'getPopular']);
Route::get('movies/{id}', [MovieController::class, 'show'])->where('id', '[0-9]+');
Route::get('movies/genre/{genre}', [MovieController::class, 'getByGenre']);

// Sessions (public)
Route::get('sessions/movie/{movieId}', [SessionController::class, 'getByMovie']);
Route::get('sessions/theater/{theaterId}', [SessionController::class, 'getByTheater']);
Route::get('sessions', [SessionController::class, 'index']);
Route::get('sessions/{id}', [SessionController::class, 'show']);
Route::get('sessions/{id}/available-seats', [SessionController::class, 'getAvailableSeats']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::delete('profile', [AuthController::class, 'deleteAccount']);
    
    // Reservations
    Route::get('reservations', [ReservationController::class, 'index']);

    Route::get('reservations/{id}', [ReservationController::class, 'show']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::put('reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('reservations/{id}', [ReservationController::class, 'cancel']);

    Route::get('reservations/{id}/tickets', [TicketController::class, 'getByReservation']);
    Route::get('reservations/session/{id}', [ReservationController::class, 'getBySession']);

    // Payments
    Route::post('payments', [PaymentController::class, 'processPayment']);
    Route::get('payments', [PaymentController::class, 'getUserPayments']);
    Route::get('reservations/{id}/payment', [PaymentController::class, 'getReservationPayment']);

    // Tickets
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::get('tickets/{id}/download', [TicketController::class, 'downloadPdf']);
    
    // Admin routes
    Route::middleware('admin')->group(function () {
        // Movies
        Route::post('movies', [MovieController::class, 'store']);
        Route::put('movies/{id}', [MovieController::class, 'update']);
        Route::delete('movies/{id}', [MovieController::class, 'destroy']);
        
        // Theaters
        Route::get('theaters', [TheaterController::class, 'index']);
        Route::get('theaters/{id}', [TheaterController::class, 'show']);
        Route::post('theaters', [TheaterController::class, 'store']);
        Route::put('theaters/{id}', [TheaterController::class, 'update']);
        Route::delete('theaters/{id}', [TheaterController::class, 'destroy']);
        Route::get('theaters/type/{type}', [TheaterController::class, 'getByType']);
        Route::get('theaters/{id}/seats', [TheaterController::class, 'getSeats']);

        // Sessions
        Route::post('sessions', [SessionController::class, 'store']);
        Route::put('sessions/{id}', [SessionController::class, 'update']);
        Route::delete('sessions/{id}', [SessionController::class, 'destroy']);
        
        // Admin dashboard
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('admin/sessions/occupancy', [AdminController::class, 'sessionOccupancy']);
        Route::get('admin/movies/revenue', [AdminController::class, 'movieRevenue']);
        Route::get('admin/users', [AdminController::class, 'users']);
        Route::put('admin/users/{id}/role', [AdminController::class, 'updateUserRole']);
    });
});