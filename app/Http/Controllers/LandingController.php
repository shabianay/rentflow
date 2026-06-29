<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('catalog');
        }

        $units = Unit::with('category')
            ->where('is_active', true)
            ->where('status', 'ready')
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::all();

        return view('landing', compact('units', 'categories'));
    }
}
