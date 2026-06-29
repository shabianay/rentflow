<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $units = Unit::with('category')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('asset_number', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%"))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        $categories = Category::all();

        return view('units.index', compact('units', 'search', 'status', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('units.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_week' => 'nullable|numeric|min:0',
            'price_per_month' => 'nullable|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'asset_number' => 'required|string|unique:units',
        ]);

        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(6);
        $data['deposit_amount'] ??= 0;

        if ($request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $paths[] = $photo->store('units', 'public');
            }
            $data['photos'] = $paths;
        }

        Unit::create($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function show(Unit $unit)
    {
        $unit->load('category');
        $reviews = $unit->reviews()->with('user')->latest()->get();
        $avgRating = $reviews->avg('rating');

        $similarUnits = Unit::withAvg('reviews', 'rating')
            ->where('id', '!=', $unit->id)
            ->where('category_id', $unit->category_id)
            ->where('is_active', true)
            ->where('status', 'ready')
            ->latest()
            ->take(4)
            ->get();

        return view('units.show', compact('unit', 'reviews', 'avgRating', 'similarUnits'));
    }

    public function edit(Unit $unit)
    {
        $categories = Category::all();

        return view('units.edit', compact('unit', 'categories'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_week' => 'nullable|numeric|min:0',
            'price_per_month' => 'nullable|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'holiday_price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:ready,booked,on_rent,maintenance,reserved',
            'location' => 'nullable|string|max:255',
            'asset_number' => 'required|string|unique:units,asset_number,' . $unit->id,
            'is_active' => 'boolean',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'string',
        ]);

        $data['deposit_amount'] ??= 0;
        $data['is_active'] ??= false;

        $photos = $unit->photos ?? [];

        // Remove deleted photos
        if ($request->has('delete_photos')) {
            foreach ($request->delete_photos as $del) {
                Storage::disk('public')->delete($del);
                $photos = array_filter($photos, fn ($p) => $p !== $del);
            }
        }

        // Add new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('units', 'public');
            }
        }

        // Reorder photos based on photo_order
        if ($request->filled('photo_order')) {
            $order = array_filter(explode(',', $request->photo_order));
            if (!empty($order)) {
                $ordered = [];
                foreach ($order as $photoPath) {
                    if (in_array($photoPath, $photos)) {
                        $ordered[] = $photoPath;
                    }
                }
                foreach ($photos as $p) {
                    if (!in_array($p, $ordered)) {
                        $ordered[] = $p;
                    }
                }
                $data['photos'] = $ordered;
            } else {
                $data['photos'] = array_values($photos);
            }
        } else {
            $data['photos'] = array_values($photos);
        }

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        // Cek apakah unit memiliki relasi aktif
        if ($unit->bookings()->whereIn('status', ['pending', 'confirmed', 'active'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus unit karena masih memiliki booking aktif.');
        }

        if ($unit->bookings()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus unit karena sudah memiliki riwayat booking. Nonaktifkan unit saja.');
        }

        if ($unit->photos) {
            foreach ($unit->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $unit->delete();

        return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dihapus.');
    }

    public function catalog(Request $request)
    {
        $categorySlug = $request->get('category');
        $search = $request->get('search');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sort = $request->get('sort', 'terbaru');

        $units = Unit::withAvg('reviews', 'rating')
            ->with('category')
            ->where('is_active', true)
            ->where('status', 'ready')
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($q) => $q->where('slug', $categorySlug)))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }))
            ->when($minPrice, fn ($q) => $q->where('price_per_day', '>=', $minPrice))
            ->when($maxPrice, fn ($q) => $q->where('price_per_day', '<=', $maxPrice))
            ->when($sort === 'termurah', fn ($q) => $q->orderBy('price_per_day'))
            ->when($sort === 'termahal', fn ($q) => $q->orderByDesc('price_per_day'))
            ->when($sort === 'terpopuler', fn ($q) => $q->withCount(['reviews as review_count'])->orderByDesc('review_count'))
            ->when($sort === 'terbaru' || !in_array($sort, ['termurah', 'termahal', 'terpopuler']), fn ($q) => $q->latest())
            ->paginate(12)
            ->appends($request->query());
        $categories = Category::all();

        return view('units.catalog', compact('units', 'categories', 'categorySlug', 'search', 'minPrice', 'maxPrice', 'sort'));
    }
}
