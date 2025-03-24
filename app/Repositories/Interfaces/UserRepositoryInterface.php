<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find user by email.
     * 
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
    
    /**
     * Get users by role.
     * 
     * @param string $role
     * @return Collection
     */
    public function getByRole(string $role): Collection;
}