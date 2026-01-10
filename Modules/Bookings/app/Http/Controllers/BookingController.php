<?php

namespace Modules\Bookings\App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::query()
            ->with('bookable')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request, string $type, int $id)
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:QRIS,DANA,OVO,GOPAY,TRANSFER_BANK'],
        ]);

        $type = Str::lower($type);

        $modelClass = match ($type) {
            'ticket' => Ticket::class,
            'tour'   => Tour::class,
            default  => null,
        };

        if (!$modelClass) {
            abort(404);
        }

        $bookable = $modelClass::query()->findOrFail($id);

        /**
         * === HARGA ===
         * Ticket  -> price / harga
         * Tour    -> price_per_person
         */
        if ($bookable instanceof Tour) {
            $price = (int) ($bookable->price_per_person ?? 0);
        } else {
            $price = (int) ($bookable->price ?? $bookable->harga ?? 0);
        }

        abort_if($price <= 0, 500, 'Harga item tidak valid.');

        $qty   = (int) $validated['qty']; // jumlah tiket / jumlah orang
        $total = $qty * $price;

        Booking::create([
            'booking_code'   => $this->generateBookingCode(),
            'user_id'        => Auth::id(),
            'bookable_type'  => $modelClass,
            'bookable_id'    => $bookable->getKey(),
            'qty'            => $qty,
            'total_price'    => $total,
            'payment_method' => $validated['payment_method'],
            'status'         => 'PAID', // dummy
        ]);

        return redirect('/my-bookings')
            ->with('success', 'Booking berhasil dibuat (Dummy PAID).');
    }

    private function generateBookingCode(): string
    {
        do {
            $suffix = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $code = 'PT-' . now()->format('Ymd') . '-' . $suffix;
        } while (
            Booking::query()->where('booking_code', $code)->exists()
        );

        return $code;
    }
}
