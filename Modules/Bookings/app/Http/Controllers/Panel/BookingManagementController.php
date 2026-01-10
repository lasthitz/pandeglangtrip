<?php

namespace Modules\Bookings\app\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingManagementController extends Controller
{
   private function ownerId(): int
{
    return (int) Auth::id();
}



    public function index(Request $request)
    {
        $ownerId = $this->ownerId();

        $bookings = Booking::query()
            ->with([
                'user:id,name,email',
                'bookable',
            ])
            ->where('status', 'PAID')
            ->whereHasMorph(
                'bookable',
                [Ticket::class, Tour::class],
                function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId);
                }
            )
            ->latest('created_at')
            ->paginate(15);

        return view('bookings::panel.bookings.index', compact('bookings'));
    }
}
