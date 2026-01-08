<?php

namespace Modules\Reviews\app\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Reviews\app\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, string $type, int $id): RedirectResponse
    {
        $user = Auth::user();

        $map = [
            'ticket' => Ticket::class,
            'tour'   => Tour::class,
        ];

        abort_unless(isset($map[$type]), 404);

        $reviewableClass = $map[$type];
        $reviewable = $reviewableClass::query()->findOrFail($id);

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
        ]);

        // Gate: user harus pernah booking item ini (PAID)
        $hasBooked = Booking::query()
            ->where('user_id', $user->id)
            ->where('status', 'PAID')
            ->where('bookable_type', $reviewableClass) // FQCN (Ticket::class / Tour::class)
            ->where('bookable_id', $reviewable->id)
            ->exists();

        if (! $hasBooked) {
            abort(403, 'You are not allowed to review this item.');
        }

        Review::create([
            'user_id'         => $user->id,
            'reviewable_type' => $reviewableClass,
            'reviewable_id'   => $reviewable->id,
            'rating'          => (int) $validated['rating'],
            'comment'         => $validated['comment'],
        ]);

        return back()->with('success', 'Review berhasil dikirim.');
    }
}
