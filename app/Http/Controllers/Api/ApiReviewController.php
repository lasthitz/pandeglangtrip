<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Reviews\App\Models\Review;

class ApiReviewController extends Controller
{
    /**
     * Submit a review for ticket/tour
     */
    public function store(Request $request, string $type, int $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Get reviewable item
        if ($type === 'ticket') {
            $reviewable = Ticket::find($id);
            $reviewableType = 'App\\Models\\Ticket';
        } else {
            $reviewable = Tour::find($id);
            $reviewableType = 'App\\Models\\Tour';
        }

        if (!$reviewable) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan',
            ], 404);
        }

        // Check if user already reviewed
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('reviewable_type', $reviewableType)
            ->where('reviewable_id', $id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan ulasan untuk item ini',
            ], 422);
        }

        // Create review
        $review = Review::create([
            'user_id' => $request->user()->id,
            'reviewable_type' => $reviewableType,
            'reviewable_id' => $id,
            'rating' => $request->rating,
            'comment' => $request->comment ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim',
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
            ],
        ], 201);
    }
}
