<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class AdminController extends Controller
{
    private $adminRepository;
    
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
    
    public function dashboard()
    {
        $stats = $this->adminRepository->getDashboardStats();
        return response()->json($stats);
    }

    public function sessionOccupancy()
    {
        $sessions = $this->adminRepository->getSessionOccupancy();
        return response()->json($sessions);
    }

    public function movieRevenue()
    {
        $movies = $this->adminRepository->getMovieRevenue();
        return response()->json($movies);
    }

    public function users()
    {
        $users = $this->adminRepository->getUsers();
        return response()->json($users);
    }

    public function updateUserRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $user = $this->adminRepository->updateUserRole($id, $request->role);
        
        return response()->json([
            'status' => 'success',
            'message' => 'User role updated successfully',
            'user' => $user,
        ]);
    }
}