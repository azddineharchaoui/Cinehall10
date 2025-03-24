<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    /**
     * Register a new user.
     * 
     * @param array $userData
     * @return User
     */
    public function register(array $userData): User;
    
    /**
     * Attempt to login a user.
     * 
     * @param array $credentials
     * @return string|null JWT token or null if failed
     */
    public function login(array $credentials): ?string;
    
    /**
     * Logout the current user.
     * 
     * @return bool
     */
    public function logout(): bool;
    
    /**
     * Refresh the JWT token.
     * 
     * @return string
     */
    public function refresh(): string;
    
    /**
     * Get the authenticated user.
     * 
     * @return User|null
     */
    public function getAuthUser(): ?User;
    
    /**
     * Update user profile.
     * 
     * @param User $user
     * @param array $data
     * @return bool
     */
    public function updateProfile(User $user, array $data): bool;
    
    /**
     * Delete user account.
     * 
     * @param User $user
     * @return bool
     */
    public function deleteAccount(User $user): bool;
}