<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function register(array $userData): User
    {
        $userData['password'] = Hash::make($userData['password']);
        $userData['role'] = $userData['role'] ?? 'user';
        
        $user = User::create($userData);
        return $user;
    }
    
    /**
     * @inheritDoc
     */
    public function login(array $credentials): ?string
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new Exception('Unauthorized');
        }
        
        return $token;
    }
    
    /**
     * @inheritDoc
     */
    public function logout(): bool
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function refresh(): string
    {
        return Auth::refresh();
    }
    
    /**
     * @inheritDoc
     */
    public function getAuthUser(): ?User
    {
        return Auth::user();
    }
    
    /**
     * @inheritDoc
     */
    public function updateProfile(User $user, array $data): bool
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        
        return $user->save();
    }
    
    /**
     * @inheritDoc
     */
    public function deleteAccount(User $user): bool
    {
        return $user->delete();
    }
}