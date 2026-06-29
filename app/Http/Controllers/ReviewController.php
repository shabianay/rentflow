<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'unit_id' => 'required|exists:units,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $booking = Auth::user()->bookings()->findOrFail($data['booking_id']);

        if ($booking->status !== 'completed') {
            return back()->with('error', 'Hanya booking yang sudah selesai yang dapat di-review.');
        }

        $unitIds = $booking->items()->pluck('unit_id');
        if ($unitIds->isEmpty()) {
            $unitIds = collect([$booking->unit_id]);
        }

        if (! $unitIds->contains($data['unit_id'])) {
            return back()->with('error', 'Unit tidak ditemukan dalam booking ini.');
        }

        if (Review::where('booking_id', $data['booking_id'])->where('unit_id', $data['unit_id'])->exists()) {
            return back()->with('error', 'Unit ini sudah memiliki review.');
        }

        Review::create([
            'booking_id' => $data['booking_id'],
            'user_id' => Auth::id(),
            'unit_id' => $data['unit_id'],
            'rating' => $data['rating'],
            'review' => $data['review'] ?? null,
        ]);

        NotificationService::sendToAdmins(
            'review',
            'Review Baru',
            Auth::user()->name . " memberikan review pada unit.",
            route('admin.reviews.index')
        );

        return back()->with('success', 'Review berhasil ditambahkan.');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $review->update($data);

        return back()->with('success', 'Review berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }

    public function adminIndex()
    {
        $reviews = Review::with(['user', 'unit', 'booking'])
            ->latest()
            ->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function adminDestroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review berhasil dihapus.');
    }

    public function show(Unit $unit)
    {
        $reviews = Review::with('user')
            ->where('unit_id', $unit->id)
            ->latest()
            ->get();

        return response()->json($reviews);
    }
}
