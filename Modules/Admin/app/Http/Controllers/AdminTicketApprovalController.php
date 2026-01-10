<?php

namespace Modules\Admin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTicketApprovalController extends Controller
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

        $query = Ticket::query()->latest();

        if ($status !== 'ALL') {
            $query->where('approval_status', $status);
        }

        $tickets = $query->paginate(10)->withQueryString();

        return view('admin::tickets.index', compact('tickets', 'status'));
    }

    public function approve(Ticket $ticket): RedirectResponse
    {
        $this->setApprovalStatus($ticket, 'APPROVED');
        return back()->with('success', 'Ticket berhasil di-approve.');
    }

    public function reject(Ticket $ticket): RedirectResponse
    {
        $this->setApprovalStatus($ticket, 'REJECTED');
        return back()->with('success', 'Ticket berhasil di-reject.');
    }

    private function setApprovalStatus(Ticket $ticket, string $status): void
    {
        $status = strtoupper($status);

        // Validasi: hanya APPROVED / REJECTED yang boleh diset lewat action ini
        if (!in_array($status, ['APPROVED', 'REJECTED'], true)) {
            abort(422, 'Invalid approval status.');
        }

        // Aturan T11: hanya update approval_status, jangan ubah is_active
        $ticket->update([
            'approval_status' => $status,
        ]);
    }
}
