<?php

namespace Modules\Public\App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TicketPublicController extends Controller
{
    public function show(Request $request, $ticket)
    {
        $ticket = Ticket::query()
            ->whereKey($ticket)
            ->where('approval_status', 'APPROVED')
            ->where('is_active', 1)
            ->firstOrFail();

        return view('public::tickets.show', compact('ticket'));
    }
}
