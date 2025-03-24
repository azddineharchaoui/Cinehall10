<?php

namespace App\Providers;

use App\Models\Movie;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Session;
use App\Models\Theater;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Eloquent\AdminRepository;
use App\Repositories\Eloquent\AuthRepository;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\MovieRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ReservationRepository;
use App\Repositories\Eloquent\SessionRepository;
use App\Repositories\Eloquent\TheaterRepository;
use App\Repositories\Eloquent\TicketRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use App\Repositories\Interfaces\MovieRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Repositories\Interfaces\TheaterRepositoryInterface;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        
        $this->app->bind(UserRepositoryInterface::class, function () {
            return new UserRepository(new User());
        });
        
        $this->app->bind(MovieRepositoryInterface::class, function () {
            return new MovieRepository(new Movie());
        });
        
        $this->app->bind(SessionRepositoryInterface::class, function () {
            return new SessionRepository(new Session());
        });
        
        $this->app->bind(ReservationRepositoryInterface::class, function () {
            return new ReservationRepository(new Reservation());
        });
        
        $this->app->bind(TheaterRepositoryInterface::class, function () {
            return new TheaterRepository(new Theater());
        });
        
        $this->app->bind(TicketRepositoryInterface::class, function () {
            return new TicketRepository(new Ticket());
        });
        
        $this->app->bind(PaymentRepositoryInterface::class, function () {
            return new PaymentRepository(new Payment());
        });
        
        $this->app->bind(AuthRepositoryInterface::class, function () {
            return new AuthRepository();
        });
        
        $this->app->bind(AdminRepositoryInterface::class, function () {
            return new AdminRepository();
        });
        // $this->app->bind('files', 'App\Services\FileService');
        // $this->app->singleton(FileService::class, function ($app) {
        //     return new FileService();
        // });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}