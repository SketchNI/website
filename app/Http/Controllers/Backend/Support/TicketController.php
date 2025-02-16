<?php

namespace App\Http\Controllers\Backend\Support;

use App\Http\Controllers\Controller;
use App\Http\Resources\Support\TicketResource;
use App\Models\Support\Ticket;
use Inertia\Inertia;
use Inertia\Response;

class TicketController extends Controller
{
    public function index(): Response
    {
        $tickets = Ticket::with(['user', 'status', 'replies'])->paginate(15);

        return inertia('Backend/Support/Ticket/Index', [
            'tickets' => TicketResource::collection($tickets),
        ]);
    }
}
