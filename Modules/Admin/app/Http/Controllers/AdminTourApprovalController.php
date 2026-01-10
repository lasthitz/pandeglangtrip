<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTourApprovalController extends Controller
{
    private const STATUSES = ['PENDING', 'APPROVED', 'REJECTED'];

    public function index(Request $request): View
    {
        // default: PENDING (sesuai blueprint)
        $status = strtoupper((string) $request->query('status', 'PENDING'));

        // dukung tab sederhana: PENDING/APPROVED/REJECTED/ALL
        if ($status !== 'ALL' && !in_array($status, self::STATUSES, true)) {
            $status = 'PENDING';
        }

        $query = Tour::query()->latest();

        if ($status !== 'ALL') {
            $query->where('approval_status', $status);
        }

        $tours = $query->paginate(10)->withQueryString();

        return view('admin::tours.index', compact('tours', 'status'));
    }

    public function approve(Tour $tour, Request $request)
{
    $this->setApprovalStatus($tour, 'APPROVED');

    return redirect()
        ->route('admin.tours.index', ['status' => $request->get('status', 'PENDING')])
        ->with('success', 'Tour berhasil di-approve.');
}

public function reject(Tour $tour, Request $request)
{
    $this->setApprovalStatus($tour, 'REJECTED');

    return redirect()
        ->route('admin.tours.index', ['status' => $request->get('status', 'PENDING')])
        ->with('success', 'Tour berhasil di-reject.');
}


    private function setApprovalStatus(Tour $tour, string $status): void
    {
        $status = strtoupper($status);

        // Validasi: hanya APPROVED / REJECTED yang boleh diset lewat action ini
        if (!in_array($status, ['APPROVED', 'REJECTED'], true)) {
            abort(422, 'Invalid approval status.');
        }

        // Aturan T11: hanya update approval_status, jangan ubah is_active
        $tour->update([
            'approval_status' => $status,
        ]);
    }
}
