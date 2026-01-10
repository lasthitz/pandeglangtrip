<?php

namespace Modules\Tickets\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    private function ownerId(): int
{
    return (int) Auth::id();
}

    private function assertOwner(Ticket $ticket): void
    {
        abort_unless((int) $ticket->owner_id === $this->ownerId(), 403);
    }

    public function index()
    {
        $tickets = Ticket::query()
            ->where('owner_id', $this->ownerId())
            ->latest()
            ->paginate(10);

        return view('tickets::tickets.index', compact('tickets'));
    }

    public function create()
    {
        $ticket = new Ticket();
        return view('tickets::tickets.create', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'visit_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create([
            'owner_id' => $this->ownerId(),
            'name' => $validated['name'],
            'price' => (int) $validated['price'],
            'description' => $validated['description'],
            'visit_date' => $validated['visit_date'] ?? null,
            'image_path' => $imagePath,
            'approval_status' => 'PENDING',
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('panel.tickets.index')
            ->with('success', 'Tiket berhasil dibuat (status: PENDING).');
    }

    public function edit(Ticket $ticket)
    {
        $this->assertOwner($ticket);
        return view('tickets::tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->assertOwner($ticket);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'visit_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('tickets', 'public');

            if ($ticket->image_path && Storage::disk('public')->exists($ticket->image_path)) {
                Storage::disk('public')->delete($ticket->image_path);
            }

            $ticket->image_path = $newPath;
        }

        $ticket->name = $validated['name'];
        $ticket->price = (int) $validated['price'];
        $ticket->description = $validated['description'];
        $ticket->visit_date = $validated['visit_date'] ?? null;
        $ticket->is_active = (bool) ($validated['is_active'] ?? false);

        $ticket->save();

        return redirect()
            ->route('panel.tickets.index')
            ->with('success', 'Tiket berhasil diupdate.');
    }

    public function destroy(Ticket $ticket)
    {
        $this->assertOwner($ticket);

        if ($ticket->image_path && Storage::disk('public')->exists($ticket->image_path)) {
            Storage::disk('public')->delete($ticket->image_path);
        }

        $ticket->delete();

        return redirect()
            ->route('panel.tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }
}
