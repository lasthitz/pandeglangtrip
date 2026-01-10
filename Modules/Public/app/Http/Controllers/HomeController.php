<?php

namespace Modules\Public\app\Http\Controllers;

use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $tickets = Ticket::query()
            ->approved()
            ->active()
            ->latest()
            ->get();

        $tours = Tour::query()
            ->approved()
            ->active()
            ->latest()
            ->get();

        return view('public::home', compact('tickets', 'tours'));
    }
}
