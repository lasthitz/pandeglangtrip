<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\Request;

class ApiDestinationController extends Controller
{
    /**
     * Get all approved & active destinations (tickets + tours)
     * Format sesuai Flutter Destination model
     */
    public function index(Request $request)
    {
        $destinations = collect();

        // Get approved & active tickets
        $tickets = Ticket::approved()
            ->active()
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'name' => $ticket->name,
                    'type' => 'ticket',
                    'category' => 'Tiket Wisata',
                    'location' => 'Pandeglang',
                    'description' => $ticket->description,
                    'price' => $ticket->price,
                    'quota' => 100, // Default quota
                    'image_url' => $ticket->image_path ? url('storage/' . $ticket->image_path) : url('images/default.jpg'),
                    'itinerary' => null,
                    'tour_guide' => null,
                    'sold_count' => $ticket->bookings()->count() ?? 0,
                    'visit_date' => $ticket->visit_date?->format('Y-m-d'),
                ];
            });

        // Get approved & active tours
        $tours = Tour::approved()
            ->active()
            ->get()
            ->map(function ($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'type' => 'tour',
                    'category' => 'Paket Tur',
                    'location' => 'Pandeglang',
                    'description' => $tour->description,
                    'price' => $tour->price_per_person,
                    'quota' => 20, // Default quota
                    'image_url' => $tour->image_path ? url('storage/' . $tour->image_path) : url('images/default.jpg'),
                    'itinerary' => $tour->itinerary,
                    'tour_guide' => $tour->guide_name,
                    'sold_count' => $tour->bookings()->count() ?? 0,
                    'start_date' => $tour->start_date?->format('Y-m-d'),
                    'end_date' => $tour->end_date?->format('Y-m-d'),
                ];
            });

        $destinations = $tickets->merge($tours);

        return response()->json($destinations->values());
    }

    /**
     * Get single destination by type and id
     */
    public function show(string $type, int $id)
    {
        if ($type === 'ticket') {
            $item = Ticket::approved()->active()->find($id);
            
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Tiket tidak ditemukan'], 404);
            }

            return response()->json([
                'id' => $item->id,
                'name' => $item->name,
                'type' => 'ticket',
                'category' => 'Tiket Wisata',
                'location' => 'Pandeglang',
                'description' => $item->description,
                'price' => $item->price,
                'quota' => 100,
                'image_url' => $item->image_path ? url('storage/' . $item->image_path) : url('images/default.jpg'),
                'itinerary' => null,
                'tour_guide' => null,
                'sold_count' => $item->bookings()->count() ?? 0,
                'visit_date' => $item->visit_date?->format('Y-m-d'),
                'reviews' => $item->reviews->map(fn($r) => [
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'user_name' => $r->user->name ?? 'Anonim',
                    'created_at' => $r->created_at->format('Y-m-d'),
                ]),
            ]);
        }

        if ($type === 'tour') {
            $item = Tour::approved()->active()->find($id);
            
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Tur tidak ditemukan'], 404);
            }

            return response()->json([
                'id' => $item->id,
                'name' => $item->name,
                'type' => 'tour',
                'category' => 'Paket Tur',
                'location' => 'Pandeglang',
                'description' => $item->description,
                'price' => $item->price_per_person,
                'quota' => 20,
                'image_url' => $item->image_path ? url('storage/' . $item->image_path) : url('images/default.jpg'),
                'itinerary' => $item->itinerary,
                'tour_guide' => $item->guide_name,
                'sold_count' => $item->bookings()->count() ?? 0,
                'start_date' => $item->start_date?->format('Y-m-d'),
                'end_date' => $item->end_date?->format('Y-m-d'),
                'reviews' => $item->reviews->map(fn($r) => [
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'user_name' => $r->user->name ?? 'Anonim',
                    'created_at' => $r->created_at->format('Y-m-d'),
                ]),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Tipe tidak valid'], 400);
    }
}
