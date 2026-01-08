<?php

namespace Modules\Tours\app\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::query()
            ->latest()
            ->paginate(10);

        return view('tours::panel.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('tours::panel.tours.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'guide_name' => ['nullable', 'string', 'max:255'],
            'itinerary' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // storage/app/public/tours
            $imagePath = $request->file('image')->store('tours', 'public');
        }

        Tour::create([
            'name' => $validated['name'],
            'price_per_person' => (int) $validated['price_per_person'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'guide_name' => $validated['guide_name'] ?? null,
            'itinerary' => $validated['itinerary'] ?? null,
            'image_path' => $imagePath,
            'approval_status' => 'PENDING', // wajib default
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()
            ->route('panel.tours.index')
            ->with('success', 'Paket tur berhasil dibuat (status: PENDING).');
    }

    public function edit(Tour $tour)
    {
        return view('tours::panel.tours.edit', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_per_person' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'guide_name' => ['nullable', 'string', 'max:255'],
            'itinerary' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            // Hapus lama kalau ada
            if ($tour->image_path && Storage::disk('public')->exists($tour->image_path)) {
                Storage::disk('public')->delete($tour->image_path);
            }
            $tour->image_path = $request->file('image')->store('tours', 'public');
        }

        $tour->name = $validated['name'];
        $tour->price_per_person = (int) $validated['price_per_person'];
        $tour->description = $validated['description'];
        $tour->start_date = $validated['start_date'] ?? null;
        $tour->end_date = $validated['end_date'] ?? null;
        $tour->guide_name = $validated['guide_name'] ?? null;
        $tour->itinerary = $validated['itinerary'] ?? null;
        $tour->is_active = (bool) ($validated['is_active'] ?? false);

        // approval_status TIDAK diubah otomatis (sesuai blueprint)
        $tour->save();

        return redirect()
            ->route('panel.tours.index')
            ->with('success', 'Paket tur berhasil diupdate.');
    }

    public function destroy(Tour $tour)
    {
        if ($tour->image_path && Storage::disk('public')->exists($tour->image_path)) {
            Storage::disk('public')->delete($tour->image_path);
        }

        $tour->delete();

        return redirect()
            ->route('panel.tours.index')
            ->with('success', 'Paket tur berhasil dihapus.');
    }
}
