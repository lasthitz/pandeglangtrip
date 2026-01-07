<?php

namespace Modules\Public\App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourPublicController extends Controller
{
    public function show(Request $request, $tour)
    {
        $tour = Tour::query()
            ->whereKey($tour)
            ->where('approval_status', 'APPROVED')
            ->where('is_active', 1)
            ->firstOrFail();

        return view('public::tours.show', compact('tour'));
    }
}
