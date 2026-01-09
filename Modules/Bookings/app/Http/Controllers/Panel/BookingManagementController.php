<?php

namespace Modules\Bookings\App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::query()
            ->with([
                'user:id,name,email',
                'bookable',
            ])
            ->where('status', 'PAID')
            ->latest('created_at')
            ->paginate(15);

        return view('bookings::panel.bookings.index', compact('bookings'));
    }
}
