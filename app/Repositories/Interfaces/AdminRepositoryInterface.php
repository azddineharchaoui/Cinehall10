<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AdminRepositoryInterface
{
    /**
     * Get dashboard statistics.
     * 
     * @return array
     */
    public function getDashboardStats(): array;
    
    /**
     * Get session occupancy data.
     * 
     * @return Collection
     */
    public function getSessionOccupancy(): Collection;
    
    /**
     * Get movie revenue data.
     * 
     * @return Collection
     */
    public function getMovieRevenue(): Collection;
    
    /**
     * Get all users with role 'user'.
     * 
     * @return Collection
     */
    public function getUsers(): Collection;
    
    /**
     * Update user role.
     * 
     * @param int $userId
     * @param string $role
     * @return User
     */
    public function updateUserRole(int $userId, string $role): User;
}