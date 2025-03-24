<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class TicketController extends Controller
{
    private $ticketRepository;
    
    public function __construct(TicketRepositoryInterface $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }
    
    public function index()
    {
        $user = Auth::user();
        $tickets = $this->ticketRepository->getByUser($user->id);
        return response()->json($tickets);
    }

    public function show($id)
    {
        $user = Auth::user();
        $ticket = $this->ticketRepository->getTicketWithDetails($id, $user->id);
        
        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found',
            ], 404);
        }
        
        return response()->json($ticket);
    }

    public function downloadPdf($id)
    {
        $user = Auth::user();
        $ticket = $this->ticketRepository->getTicketWithDetails($id, $user->id);
        
        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found',
            ], 404);
        }
        
        $pdf = PDF::loadView('tickets.pdf', ['ticket' => $ticket]);
        
        return $pdf->download('ticket-' . $ticket->id . '.pdf');
    }
    
    public function getByReservation($reservationId)
    {
        $user = Auth::user();
        $tickets = $this->ticketRepository->getByReservation($reservationId);
        
        // Filter tickets by user
        $tickets = $tickets->filter(function ($ticket) use ($user) {
            return $ticket->user_id === $user->id;
        });
        
        return response()->json($tickets);
    }
}