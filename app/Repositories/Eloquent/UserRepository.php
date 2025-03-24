<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     * 
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    
    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
    
    /**
     * @inheritDoc
     */
    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }
}