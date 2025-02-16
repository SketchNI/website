<?php

namespace App\Http\Controllers\Support;

use App\Exceptions\ForbiddenException;
use App\Http\Requests\Support\Ticket\CreateRequest;
use App\Http\Resources\Support\TicketResource;
use App\Mail\Support\Admin\NewTicketMail;
use App\Mail\Support\TicketSentMail;
use App\Models\Support\Ticket;
use App\Traits\Flashable;
use App\Traits\ThrowsException;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TicketController
{
    use ThrowsException;
    use Flashable;

    public function index(): Response
    {
        $tickets = Ticket::with(['status', 'user', 'replies'])
            ->whereUserId(auth()->id())
            ->paginate(15);

        return inertia('Support/Ticket/Index', [
            'tickets' => TicketResource::collection($tickets),
        ]);
    }

    /**
     * @param  CreateRequest  $request
     *
     * @return RedirectResponse
     * @throws ForbiddenException
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $this->forbidden('user::create ticket');

        $ticket = new Ticket()
            ->setSubject($request->get('subject'))
            ->setContent($request->get('content'))
            ->setStatus('new');

        if ($ticket->save()) {
            dispatch(new TicketSentMail($ticket));
            dispatch(new NewTicketMail($ticket));

            $this->flash('Your ticket has been created.');
            return redirect()->route('support.ticket.show', $ticket);
        }

        $this->flash('Your ticket has not been created.', 'error');
        return redirect()->back();
    }

    public function show(Ticket $ticket): Response
    {
        $ticket = Ticket::with(['status', 'user', 'replies'])->find($ticket->id);

        return inertia('Support/Ticket/Show', [
            'ticket' => new TicketResource($ticket)->resolve(),
        ]);
    }
}
