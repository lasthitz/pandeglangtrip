<?php

namespace Modules\Bookings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function ownerId(): int
{
    return (int) Auth::id();
}

    public function index()
    {
        $ownerId = $this->ownerId();

        // 1) Total Pemesanan (PAID) khusus item milik pengelola ini
        $total_pemesanan = Booking::query()
            ->where('status', 'PAID')
            ->whereHasMorph(
                'bookable',
                [Ticket::class, Tour::class],
                function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId);
                }
            )
            ->count();

        // 2) Total Destinasi Aktif khusus milik pengelola ini
        $total_destinasi_aktif =
            Ticket::approved()->active()->where('owner_id', $ownerId)->count()
            + Tour::approved()->active()->where('owner_id', $ownerId)->count();

        // 3) Total Pendapatan khusus item milik pengelola ini
        $total_pendapatan = Booking::query()
            ->where('status', 'PAID')
            ->whereHasMorph(
                'bookable',
                [Ticket::class, Tour::class],
                function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId);
                }
            )
            ->sum('total_price');

        return view('bookings::dashboard.index', compact(
            'total_pemesanan',
            'total_destinasi_aktif',
            'total_pendapatan'
        ));
    }
}
