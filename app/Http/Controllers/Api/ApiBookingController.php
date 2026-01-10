<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiBookingController extends Controller
{
    /**
     * Get user's booking history
     */
    public function index(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with('bookable')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                $bookable = $booking->bookable;
                $type = $booking->bookable_type === 'App\\Models\\Ticket' ? 'ticket' : 'tour';

                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'destination_id' => $booking->bookable_id,
                    'destination_name' => $bookable?->name ?? 'Unknown',
                    'destination_type' => $type,
                    'image_url' => $bookable?->image_path ? url('storage/' . $bookable->image_path) : url('images/default.jpg'),
                    'qty' => $booking->qty,
                    'total_price' => $booking->total_price,
                    'payment_method' => $booking->payment_method,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                    'visit_date' => $type === 'ticket' 
                        ? $bookable?->visit_date?->format('Y-m-d') 
                        : $bookable?->start_date?->format('Y-m-d'),
                ];
            });

        return response()->json($bookings);
    }

    /**
     * Create new booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:ticket,tour',
            'destination_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Get bookable item
        if ($request->type === 'ticket') {
            $bookable = Ticket::approved()->active()->find($request->destination_id);
            $bookableType = 'App\\Models\\Ticket';
            $price = $bookable?->price ?? 0;
        } else {
            $bookable = Tour::approved()->active()->find($request->destination_id);
            $bookableType = 'App\\Models\\Tour';
            $price = $bookable?->price_per_person ?? 0;
        }

        if (!$bookable) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan atau tidak aktif',
            ], 404);
        }

        // Create booking
        $booking = Booking::create([
            'booking_code' => 'BK' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'bookable_type' => $bookableType,
            'bookable_id' => $bookable->id,
            'qty' => $request->qty,
            'total_price' => $price * $request->qty,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'booking' => [
                'id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
            ],
        ], 201);
    }
}
