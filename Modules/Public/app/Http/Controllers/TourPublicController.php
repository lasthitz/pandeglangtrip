<?php

namespace Modules\Public\app\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TourPublicController extends Controller
{
        public function show(Request $request, Tour $tour)
{
    abort_unless($tour->approval_status === 'APPROVED' && $tour->is_active, 404);
    return view('public::tours.show', compact('tour'));
}

}
