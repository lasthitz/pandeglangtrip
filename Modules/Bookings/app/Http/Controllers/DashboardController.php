<?php

namespace Modules\Bookings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;

class DashboardController extends Controller
{
    public function index()
    {
        $total_pemesanan = Booking::where('status', 'PAID')->count();

        $total_destinasi_aktif =
            Ticket::approved()->active()->count()
            + Tour::approved()->active()->count();

        $total_pendapatan = Booking::where('status', 'PAID')
            ->sum('total_price');

        return view('bookings::dashboard.index', compact(
            'total_pemesanan',
            'total_destinasi_aktif',
            'total_pendapatan'
        ));
    }
}
